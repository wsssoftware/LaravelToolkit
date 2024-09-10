<?php

namespace LaravelToolkit\SEO;

class Payload
{
    public bool $format;
    public bool $propagation;
    public ?string $title;
    public ?string $description;
    public ?string $canonical;
    public ?array $robots;
    public ?OpenGraph $openGraph;
    public ?TwitterCard $twitterCard;

    public function __construct()
    {
        $this->format = boolval(config('laraveltoolkit.seo.format', false));
        $this->propagation = boolval(config('laraveltoolkit.seo.propagation', false));
        $this->title = config('laraveltoolkit.seo.defaults.title');
        $this->description = config('laraveltoolkit.seo.defaults.description');
        $this->canonical = config('laraveltoolkit.seo.defaults.canonical');
        $this->robots = config('laraveltoolkit.seo.defaults.robots');

        $this->openGraph = new OpenGraph($this->format, $this->propagation);
        $this->twitterCard = new TwitterCard($this->format, $this->propagation);
    }
}
