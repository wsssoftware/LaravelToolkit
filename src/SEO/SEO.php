<?php

namespace LaravelToolkit\SEO;

class SEO
{

    private Payload $payload;

    public function __construct()
    {
        $this->payload = new Payload();
    }

    public function payload(): SEOResource
    {
        return SEOResource::make($this->payload);
    }

    public function withDescription(string $title, ?bool $propagate = null): self
    {
        $this->payload->description = $title;
        if ($propagate ?? $this->payload->propagation) {
            $this->payload->twitterCard->title = $title;
        }
        return $this;
    }

    public function withTitle(string $title, ?bool $propagate = null): self
    {
        $this->payload->title = $title;
        if ($propagate ?? $this->payload->propagation) {
            $this->payload->twitterCard->title = $title;
        }
        return $this;
    }
}
