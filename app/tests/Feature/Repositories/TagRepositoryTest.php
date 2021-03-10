<?php
use App\Repositories\TagRepository;
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
    $subject = new TagRepository();
    $itemById = $subject->findByIdOrTitle('3');
    $itemBySlug = $subject->findByIdOrTitle('IQs Kitchen');
    expect($itemById)->toEqual($itemBySlug);
});
