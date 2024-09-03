<?php

namespace LaravelToolkit\Flash;

use PHPUnit\Framework\Assert as PHPUnit;

trait FlashTests
{


    public function assertFlashed(?Severity $severity = null, null|int|string $countOrMessage = null): void
    {
        $messages = $this->messages;
        if ($severity) {
            $messages = $messages->filter(fn (Message $message) => $message->severity === $severity);
        }
        if (is_string($countOrMessage)) {
            $messages = $messages->filter(fn (Message $message) => $message->detail === $countOrMessage);
        }
        if (is_int($countOrMessage)) {
            PHPUnit::assertCount(
                $countOrMessage, $messages,
                sprintf(
                    'Was expected %s flashes from "%s" severity but was found %s',
                    $countOrMessage,
                    $severity?->value ?? 'any',
                    $messages->count()
                )
            );
        } elseif (is_string($countOrMessage)) {
            PHPUnit::assertTrue(
                $messages->isNotEmpty(),
                sprintf(
                    'Was expected a flash of "%s" severity with detail of "%s" but was not found',
                    $severity?->value ?? 'any',
                    $countOrMessage,
                )
            );
        } else {
            PHPUnit::assertTrue(
                $messages->isNotEmpty(),
                sprintf(
                    'Was expected a flash of "%s" severity but was not found',
                    $severity?->value ?? 'any',
                )
            );
        }

    }

    public function assertNotFlashed(?Severity $severity = null): void
    {
        $messages = $this->messages;
        if ($severity) {
            $messages = $messages->filter(fn (Message $message) => $message->severity === $severity);
        }
        PHPUnit::assertTrue(
            $messages->isEmpty(),
            sprintf(
                'Was expected none flashes of "%s" severity but was found %s',
                $severity?->value ?? 'any',
                $messages->count()
            )
        );
    }
}
