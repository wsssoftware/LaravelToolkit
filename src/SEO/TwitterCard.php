<?php

namespace LaravelToolkit\SEO;

use Illuminate\Support\Arr;

class TwitterCard
{
    readonly public string $card;
    public ?string $site;
    public ?string $title;
    public ?string $description;
    public ?Image $image;

    public function __construct(
        public bool $format,
        public bool $propagation,
    ) {
        $this->card = 'summary_large_image';
        $this->site = config('laravel_toolkit.seo.defaults.twitter_card.site');
        $this->title = config(
            'laravel_toolkit.seo.defaults.twitter_card.title',
            $this->propagation ? config('laravel_toolkit.seo.defaults.title') : null,
        );
        $this->description = config(
            'laravel_toolkit.seo.defaults.twitter_card.title',
            $this->propagation ? config('laravel_toolkit.seo.defaults.description') : null,
        );
        $imageConfig = config('laravel_toolkit.seo.defaults.twitter_card.image', ['disk' => null, 'path' => null, 'alt' => null]);
        if (!empty($imageConfig['disk']) && !empty($imageConfig['path'])) {
            $this->image = new Image(
                Arr::get($imageConfig, 'disk'),
                Arr::get($imageConfig, 'path'),
                Arr::get($imageConfig, 'alt'),
            );
        }
    }
}
