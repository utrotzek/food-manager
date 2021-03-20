<?php
use App\Repositories\RecipeRepository;
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
    $subject = new RecipeRepository();
    $itemById = $subject->findByIdOrSlug('1');
    $itemBySlug = $subject->findByIdOrSlug('Chili con Carne');
    expect($itemById)->toEqual($itemBySlug);
});
