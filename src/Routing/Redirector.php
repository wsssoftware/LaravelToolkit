<?php

namespace LaravelToolkit\Routing;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class Redirector extends \Illuminate\Routing\Redirector
{
    public function __construct(UrlGenerator $generator)
    {
        parent::__construct($generator);
        $session = session()?->driver();
        if ($session) {
            $this->setSession($session);
        }
    }

    protected function createRedirect($path, $status, $headers): Response
    {
        $host = $this->generator->getRequest()->getSchemeAndHttpHost();
        $isHttp = str($path)->startsWith(['http://', 'https://']);
        $isSameHost = str($path)->startsWith($host);

        return match (true) {
            Request::inertia() && $isHttp && ! $isSameHost => Inertia::location($path),
            default => parent::createRedirect($path, $status, $headers),
        };
    }
}
