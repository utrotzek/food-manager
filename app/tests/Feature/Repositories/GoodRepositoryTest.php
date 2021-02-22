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

it('Can find items by id or slug', function () {
    $subject = new GoodRepository();
    $itemById = $subject->findByIdOrSlug('1');
    $itemBySlug = $subject->findByIdOrSlug('Mehl');
    expect($itemById)->toEqual($itemBySlug);
});
