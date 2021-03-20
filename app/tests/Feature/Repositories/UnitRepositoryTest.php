<?php
use App\Repositories\UnitRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(
    DatabaseTransactions::class,
    DatabaseMigrations::class
);

beforeEach(function () {
    $this->seed('DatabaseSeeder');
});

it('Can find items by id or slug', function () {
    $subject = new UnitRepository();
    $itemById = $subject->findByIdOrSlug('2');
    $itemBySlug = $subject->findByIdOrSlug('Gramm');
    expect($itemById)->toEqual($itemBySlug);
});
