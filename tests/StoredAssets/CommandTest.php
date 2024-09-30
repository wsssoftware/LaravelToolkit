<?php

it('can create recipe', function () {
    $path = base_path('/app/StoreRecipes/FooRecipe.php');
    unlink($path);

    expect($path)
        ->not
        ->toBeFile();

    $this->artisan('make:store-recipe FooRecipe')
        ->assertSuccessful()
        ->expectsOutputToContain('created successfully.');

    $this->artisan('make:store-recipe FooRecipe')
        ->assertSuccessful()
        ->expectsOutputToContain('Store recipe already exists.');

    expect($path)
        ->toBeFile();

});
