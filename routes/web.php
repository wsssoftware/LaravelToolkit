<?php

use LaravelToolkit\Actions\Flash\GetMessages;

Route::middleware('web')->get('/lt/flash-get-messages', GetMessages::class)
    ->name('lt.flash.get_messages');
