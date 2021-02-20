<?php

use App\Repositories\GoodRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(
    DatabaseTransactions::class,
    DatabaseMigrations::class
);

beforeEach(function () {
    $this->seed('DevelopmentSeeder');
});

it('Can find all', function () {
    $subject = new GoodRepository();
    $allItems = $subject->all();
    expect($allItems)->toHaveCount(7);
});
