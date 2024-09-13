<?php

namespace LaravelToolkit\Actions\Sitemap;

use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use LaravelToolkit\Facades\Sitemap;
use LaravelToolkit\Sitemap\Index;
use LaravelToolkit\Sitemap\SitemapRequestedEvent;
use LaravelToolkit\Sitemap\Url;

class RenderSitemap
{
    protected Request $request;

    protected ?string $group;

    protected ?int $lastModified;

    public function __invoke(Request $request, ?string $group = null)
    {
        $timeout = config('laraveltoolkit.sitemap.timeout');
        if (is_int($timeout)) {
            set_time_limit($timeout);
        }
        $this->bootstrap($request, $group);
        $cacheTtl = config('laraveltoolkit.sitemap.cache');
        $cacheKey = 'lt.sitemap'.sha1($request->getHost().'::'.($group ?? '').'::'.$this->lastModified);
        $xml = $cacheTtl !== false
            ? Cache::remember($cacheKey, $cacheTtl, fn () => $this->write())
            : $this->write();
        SitemapRequestedEvent::dispatch($request->getHost(), $group, $request->userAgent());

        return response($xml)->header('Content-Type', 'text/xml');
    }

    protected function bootstrap(Request $request, $group): void
    {
        $this->request = $request;
        $this->group = $group;
        $sitemapConfig = base_path('routes/sitemap.php');
        abort_if(! file_exists($sitemapConfig), 404);
        $this->lastModified = filemtime($sitemapConfig);
        if (file_exists(base_path('routes/sitemap.php'))) {
            require $sitemapConfig;
        }
        abort_if(! Sitemap::groupExists($group), 404);
    }

    protected function write(): string
    {
        $items = Sitemap::process($this->request->getHost(), $this->group);
        $type = $items->filter(fn (Index|Url $item) => $item instanceof Index)->count() === 0 ? 'urlset' : 'sitemapindex';
        $xml = new DOMDocument('1.0', 'utf-8');
        $xmlns = 'http://www.sitemaps.org/schemas/sitemap/0.9';
        $xmlRoot = $xml->appendChild($xml->createElementNS($xmlns, $type));
        $items->each(fn (Index|Url $item) => $item->toXml($xml, $xmlRoot));

        return $xml->saveXML();
    }
}
