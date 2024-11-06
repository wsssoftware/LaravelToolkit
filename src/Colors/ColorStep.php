<?php

namespace LaravelToolkit\Colors;

enum ColorStep: int
{
    case STEP_50 = 50;
    case STEP_100 = 100;
    case STEP_200 = 200;
    case STEP_300 = 300;
    case STEP_400 = 400;
    case STEP_500 = 500;
    case STEP_600 = 600;
    case STEP_700 = 700;
    case STEP_800 = 800;
    case STEP_900 = 900;
    case STEP_950 = 950;

    /**
     * @return \LaravelToolkit\Colors\ColorStep[]
     */
    public function afterSteps(): array
    {
        $items = [];
        foreach (self::cases() as $case) {
            if ($case->value <= $this->value) {
                continue;
            }
            $items[] = $case;
        }

        return $items;
    }

    /**
     * @return \LaravelToolkit\Colors\ColorStep[]
     */
    public function beforeSteps(): array
    {
        $items = [];
        foreach (self::cases() as $case) {
            if ($case === $this) {
                break;
            }
            $items[] = $case;
        }

        return $items;
    }
}
