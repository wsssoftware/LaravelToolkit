<?php


use LaravelToolkit\Tests\Model\User;

it('test base functionality', function () {
    User::factory()->count(100)->create();
    Route::getAndPost('/', function () {
        return response()->json([
            'users' => User::query()->primevueData('foo')
        ]);
    });

    $response = $this->post('/', [
        'foo' => 2,
        'foo-options' => [
            'global_filter_name' => 'global',
            'rows' => 22
        ],
    ]);

    $response->assertSuccessful();

    expect($response->content())
        ->toBeJson()
        ->and($response->json('users'))
        ->toBeArray()
        ->toHaveKeys([
            'page_name', 'current_page', 'data', 'first_page_url', 'from', 'last_page', 'last_page_url',
            'links', 'next_page_url', 'path', 'per_page', 'prev_page_url', 'to', 'total',
        ])
        ->and($response->json('users.total'))
        ->toEqual(101)
        ->and($response->json('users.page_name'))
        ->toEqual('foo')
        ->and($response->json('users.current_page'))
        ->toEqual(2)
        ->and($response->json('users.data'))
        ->toBeArray()
        ->toHaveCount(22);
});

it('test asc sort', function () {
    User::factory()->count(100)->create();
    Route::getAndPost('/', function () {
        return response()->json([
            'users' => User::query()->primevueData()
        ]);
    });

    $response = $this->post('/', [
        'page' => 1,
        'page-options' => [
            'global_filter_name' => 'global',
            'rows' => 15,
            'sort' => 'id:asc'
        ],
    ]);
    $response->assertSuccessful();
    expect($response->content())
        ->toBeJson()
        ->and($response->json('users'))
        ->toBeArray()
        ->and($response->json('users.data'))
        ->toBeArray()
        ->and($response->json('users.data.0.id'))
        ->toBe(1);
});

it('test desc sort', function () {
    User::factory()->count(100)->create();
    Route::getAndPost('/', function () {
        return response()->json([
            'users' => User::query()->primevueData()
        ]);
    });

    $response = $this->post('/', [
        'page' => 1,
        'page-options' => [
            'global_filter_name' => 'global',
            'rows' => 15,
            'sort' => 'id:desc'
        ],
    ]);
    $response->assertSuccessful();
    expect($response->content())
        ->toBeJson()
        ->and($response->json('users'))
        ->toBeArray()
        ->and($response->json('users.data'))
        ->toBeArray()
        ->and($response->json('users.data.0.id'))
        ->toBe(101);
});

it('test global filter', function () {
    User::factory()->count(100)->create();
    Route::getAndPost('/', function () {
        return response()->json([
            'users' => User::query()->primevueData()
        ]);
    });

    $response = $this->post('/', [
        'page' => 1,
        'page-options' => [
            'global_filter_name' => 'global',
            'rows' => 15,
            'filters' => ['global' => ['value' => 'Foo Bar', 'matchMode' => 'contains']]
        ],
    ]);
    $response->assertSuccessful();
    expect($response->content())
        ->toBeJson()
        ->and($response->json('users'))
        ->toBeArray()
        ->and($response->json('users.data'))
        ->toBeArray()
        ->toHaveCount(1)
        ->and($response->json('users.data.0.id'))
        ->toBe(1)
        ->and($response->json('users.data.0.name'))
        ->toBe('Foo Bar')
        ->and($response->json('users.data.0.email'))
        ->toBe('foo@bar.com');
});

it('test global filter with invalid matchMode', function () {
    User::factory()->count(100)->create();
    Route::getAndPost('/', function () {
        return response()->json([
            'users' => User::query()->primevueData()
        ]);
    });

    $response = $this->post('/', [
        'page' => 1,
        'page-options' => [
            'global_filter_name' => 'global',
            'rows' => 15,
            'filters' => ['global' => ['value' => 'Foo Bar', 'matchMode' => 'abc']]
        ],
    ]);
    $response->assertSuccessful();
    expect($response->content())
        ->toBeJson()
        ->and($response->json('users'))
        ->toBeArray()
        ->and($response->json('users.data'))
        ->toBeArray()
        ->toHaveCount(15);
});

it('test id filter with contains', function () {
    User::factory()->count(100)->create();
    Route::getAndPost('/', function () {
        return response()->json([
            'users' => User::query()->primevueData()
        ]);
    });
    $response = $this->post('/', [
        'page' => 1,
        'page-options' => [
            'global_filter_name' => 'global',
            'rows' => 15,
            'filters' => ['id' => ['value' => '1', 'matchMode' => 'contains']]
        ],
    ]);
    $response->assertSuccessful();
    expect($response->content())
        ->toBeJson()
        ->and($response->json('users'))
        ->toBeArray()
        ->and($response->json('users.total'))
        ->toEqual(21);
});

it('test id filter with starts with', function () {
    User::factory()->count(100)->create();
    Route::getAndPost('/', function () {
        return response()->json([
            'users' => User::query()->primevueData()
        ]);
    });
    $response = $this->post('/', [
        'page' => 1,
        'page-options' => [
            'global_filter_name' => 'global',
            'rows' => 15,
            'filters' => ['id' => ['value' => '1', 'matchMode' => 'startsWith']]
        ],
    ]);
    $response->assertSuccessful();
    expect($response->content())
        ->toBeJson()
        ->and($response->json('users'))
        ->toBeArray()
        ->and($response->json('users.total'))
        ->toEqual(13);
});

it('test id filter with not contains', function () {
    User::factory()->count(100)->create();
    Route::getAndPost('/', function () {
        return response()->json([
            'users' => User::query()->primevueData()
        ]);
    });
    $response = $this->post('/', [
        'page' => 1,
        'page-options' => [
            'global_filter_name' => 'global',
            'rows' => 15,
            'filters' => ['id' => ['value' => '1', 'matchMode' => 'notContains']]
        ],
    ]);
    $response->assertSuccessful();
    expect($response->content())
        ->toBeJson()
        ->and($response->json('users'))
        ->toBeArray()
        ->and($response->json('users.total'))
        ->toEqual(80);
});

it('test id filter with ends with', function () {
    User::factory()->count(100)->create();
    Route::getAndPost('/', function () {
        return response()->json([
            'users' => User::query()->primevueData()
        ]);
    });
    $response = $this->post('/', [
        'page' => 1,
        'page-options' => [
            'global_filter_name' => 'global',
            'rows' => 15,
            'filters' => ['id' => ['value' => '1', 'matchMode' => 'endsWith']]
        ],
    ]);
    $response->assertSuccessful();
    expect($response->content())
        ->toBeJson()
        ->and($response->json('users'))
        ->toBeArray()
        ->and($response->json('users.total'))
        ->toEqual(11);
});

it('test id filter with equals', function () {
    User::factory()->count(100)->create();
    Route::getAndPost('/', function () {
        return response()->json([
            'users' => User::query()->primevueData()
        ]);
    });
    $response = $this->post('/', [
        'page' => 1,
        'page-options' => [
            'global_filter_name' => 'global',
            'rows' => 15,
            'filters' => ['id' => ['value' => '80', 'matchMode' => 'equals']]
        ],
    ]);
    $response->assertSuccessful();
    expect($response->content())
        ->toBeJson()
        ->and($response->json('users'))
        ->toBeArray()
        ->and($response->json('users.total'))
        ->toEqual(1);
});

it('test id filter with not equals', function () {
    User::factory()->count(100)->create();
    Route::getAndPost('/', function () {
        return response()->json([
            'users' => User::query()->primevueData()
        ]);
    });
    $response = $this->post('/', [
        'page' => 1,
        'page-options' => [
            'global_filter_name' => 'global',
            'rows' => 15,
            'filters' => ['id' => ['value' => '101', 'matchMode' => 'notEquals']]
        ],
    ]);
    $response->assertSuccessful();
    expect($response->content())
        ->toBeJson()
        ->and($response->json('users'))
        ->toBeArray()
        ->and($response->json('users.total'))
        ->toEqual(100);
});
