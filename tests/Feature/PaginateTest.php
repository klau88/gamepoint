<?php

use App\Models\Payment;
use App\Services\PaginateService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can paginate orders and sort them by user_id', function () {
    Payment::factory()->count(20)->create();
    $usersPagination = $this->app->make(PaginateService::class)->payments(orderBy: 'user_id', pageName: 'usersPage');

    expect($usersPagination)->toBeArray();

    expect(array_keys($usersPagination))->toBe([
        'current_page',
        'data',
        'first_page_url',
        'from',
        'last_page',
        'last_page_url',
        'links',
        'next_page_url',
        'path',
        'per_page',
        'prev_page_url',
        'to',
        'total'
    ]);

    expect(array_keys(reset($usersPagination['data'])))->toBe(['amount', 'currency', 'date']);
});

it('can paginate orders and sort them by currency', function () {
    Payment::factory()->count(20)->create();
    $currencyPagination = $this->app->make(PaginateService::class)->payments(orderBy: 'currency', pageName: 'usersPage');

    expect($currencyPagination)->toBeArray();

    expect(array_keys($currencyPagination))->toBe([
        'current_page',
        'data',
        'first_page_url',
        'from',
        'last_page',
        'last_page_url',
        'links',
        'next_page_url',
        'path',
        'per_page',
        'prev_page_url',
        'to',
        'total'
    ]);

    expect(array_keys(reset($currencyPagination['data'])))->toBe(['amount', 'currency', 'date']);
});

it('can paginate orders and sort them by date', function () {
    Payment::factory()->count(20)->create();
    $datePagination = $this->app->make(PaginateService::class)->payments(orderBy: 'date', pageName: 'usersPage', order: 'desc');

    expect($datePagination)->toBeArray();

    expect(array_keys($datePagination))->toBe([
        'current_page',
        'data',
        'first_page_url',
        'from',
        'last_page',
        'last_page_url',
        'links',
        'next_page_url',
        'path',
        'per_page',
        'prev_page_url',
        'to',
        'total'
    ]);

    expect(array_keys(reset($datePagination['data'])))->toBe(['amount', 'currency', 'date']);
});
