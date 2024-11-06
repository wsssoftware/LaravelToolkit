<?php

namespace LaravelToolkit\Colors;

use LaravelToolkit\Facades\Colors;

class PaletteGenerator
{
    public function __construct(
        protected readonly int $h,
        protected readonly int $s,
        protected readonly int $l,
        protected readonly ColorStep $baseStep,
        protected readonly ColorFormat $outputFormat,
        protected readonly float $thresholdLightest,
        protected readonly float $thresholdDarkest,
    ) {
        //
    }

    public function __invoke(): array
    {
        $palette = collect();

        $lighterSteps = $this->baseStep->beforeSteps();
        $lighterRangeStep = (100 - $this->thresholdLightest - $this->l) / count($lighterSteps);
        foreach ($lighterSteps as $index => $step) {
            $level = $step->value;
            $baseLevel = $this->baseStep->value;
            $palette->put($level, [
                $this->h,
                round(max(0, $this->s - ($this->s * (($baseLevel - $level) / $baseLevel) * 0.5))),
                round($this->l + ($lighterRangeStep * (count($lighterSteps) - $index))),
            ]);
        }

        $palette->put($this->baseStep->value, [$this->h, $this->s, $this->l]);

        $darkerSteps = $this->baseStep->afterSteps();
        $darkerRangeStep = ($this->l - $this->thresholdDarkest) / count($darkerSteps);
        foreach ($darkerSteps as $index => $step) {
            $level = $step->value;
            $baseLevel = $this->baseStep->value;
            $palette->put($step->value, [
                $this->h,
                round(min(100, $this->s + ($this->s * (($level - $baseLevel) / $baseLevel) * 0.4))),
                round($this->l - ($darkerRangeStep * ($index + 1))),
            ]);
        }

        return $palette
            ->map(fn (array $hsl) => match ($this->outputFormat) {
                ColorFormat::HEX => Colors::hslToHex(...$hsl),
                ColorFormat::RGB => Colors::hslToRgb(...$hsl),
                default => $hsl
            })->toArray();
    }
}
