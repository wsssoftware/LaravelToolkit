<?php

namespace LaravelToolkit\Actions\Sitemap;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use LaravelToolkit\Facades\Sitemap;
use LaravelToolkit\Sitemap\Index;
use LaravelToolkit\Sitemap\SitemapRequestedEvent;
use LaravelToolkit\Sitemap\Url;
use Saloon\XmlWrangler\Data\RootElement;
use Saloon\XmlWrangler\XmlWriter;

class RenderSitemap
{
    protected Request $request;

    protected ?string $group;

    protected ?int $lastModified;

    public function __invoke(Request $request, ?string $group = null)
    {
        $this->bootstrap($request, $group);
        $cacheTtl = config('laraveltoolkit.sitemap.cache');
        $cacheKey = 'lt.sitemap'.sha1($request->getHost().'::'.($group ?? '').'::'.$this->lastModified);
        $xml = $cacheTtl !== false
            ? Cache::remember($cacheKey, $cacheTtl, fn () => $this->write())
            : $this->write();
        SitemapRequestedEvent::dispatch($request->getHost(), $group);
        return response($xml)->header('Content-Type', 'text/xml');
    }

    protected function bootstrap(Request $request, $group): void
    {
        $this->request = $request;
        $this->group = $group;
        $sitemapConfig = base_path('routes/sitemap.php');
        abort_if(! file_exists($sitemapConfig), 404);
        $this->lastModified = filemtime($sitemapConfig);
        require $sitemapConfig;
        abort_if(! Sitemap::groupExists($group), 404);
    }

    protected function getData(Collection $items, bool $urlsetType): array
    {
        $key = $urlsetType ? 'url' : 'sitemap';

        return $items->isEmpty() ? [] : [
            $key => $items->map(fn (Index|Url $item) => $item->toXml())->toArray(),
        ];
    }

    protected function write(): string
    {
        $items = Sitemap::process($this->request->getHost(), $this->group);
        $urlsetType = $items->filter(fn (Index|Url $item) => $item instanceof Index)->count() === 0;
        $root = $urlsetType ? RootElement::make('urlset') : RootElement::make('sitemapindex');

        return XmlWriter::make()->write(
            $root->addNamespace('', 'http://www.sitemaps.org/schemas/sitemap/0.9'),
            $this->getData($items, $urlsetType),
            ! config('app.debug')
        );
    }
}
