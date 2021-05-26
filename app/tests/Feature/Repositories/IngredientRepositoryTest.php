<?php
use App\Repositories\IngredientRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(
    DatabaseTransactions::class,
    DatabaseMigrations::class
);

beforeEach(function () {
    $this->seed('DatabaseSeeder');
});

it('Will delete items when not present in actual array', function () {
    $ingredientRepository = new IngredientRepository();
    $existingItems = new Collection();
    $existingItems->add($ingredientRepository->findById(1));
    $existingItems->add($ingredientRepository->findById(2));
    $existingItems->add($ingredientRepository->findById(3));

    $actualItems = new Collection();
    $actualItems->add($ingredientRepository->findById(2));
    $actualItems->add($ingredientRepository->findById(3));

    $ingredientRepository->deleteRemovedItems($existingItems, $actualItems);
    expect($ingredientRepository->findById(1))->toBeNull();
});
