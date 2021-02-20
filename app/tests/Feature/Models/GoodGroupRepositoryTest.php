<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use function Pest\Laravel\get;

uses(
    \Illuminate\Foundation\Testing\DatabaseTransactions::class,
    DatabaseMigrations::class
);

/** @var \App\Models\GoodGroupRepository $subject */
$subject = null;

beforeEach(function () {
    $this->seed('DevelopmentSeeder');
});

it('Can insert entries sorted first', function () {
    $subject = new \App\Models\GoodGroupRepository();
    $subject->createFirst('TestTitle');

    $goodGorup = $subject->findByTitle('TestTitle');
    expect($goodGorup['sort'])->toBe(10);
});

it('Can insert entries sorted last', function () {
    $subject = new \App\Models\GoodGroupRepository();
    $subject->createLast('TestTitle');

    $goodGorup = $subject->findByTitle('TestTitle');
    expect($goodGorup['sort'])->toBe(80);
});

it('Can insert after sort entry', function () {
    $subject = new \App\Models\GoodGroupRepository();

    $itemTAfter = $subject->findById(3);
    $itemToSortAfter = $subject->findById(2);
    $createdItem = $subject->createAfter('TestTitle', $itemToSortAfter);

    $itemTAfter = $itemTAfter->fresh();
    expect($createdItem['sort'])->toBe(30);
    expect($itemTAfter['sort'])->toBe(40);
});

it('Can update the title', function () {
    $subject = new \App\Models\GoodGroupRepository();
    $goodGroupToUpdate = $subject->findByTitle('Obst und Gemüse');
    $subject->updateTitle('A new title', $goodGroupToUpdate);

    expect($subject->findByTitle('Obst und Gemüse'))->toBeNull();
    expect($subject->findByTitle('A new title'))->not()->toBeNull();
});

it('Can resort item as first element', function () {
    $subject = new \App\Models\GoodGroupRepository();
    $goodGroupToResort = $subject->findById(2);
    expect($goodGroupToResort['sort'])->toBe(20);

    $subject->resortFirst($goodGroupToResort);
    $goodGroupResorted = $subject->findById(2);
    expect($goodGroupResorted['sort'])->toBe(10);
});

it('Can resort item as last element', function () {
    $subject = new \App\Models\GoodGroupRepository();
    $goodGroupToResort = $subject->findById(2);
    expect($goodGroupToResort['sort'])->toBe(20);

    $subject->resortLast($goodGroupToResort);
    $goodGroupResorted = $subject->findById(2);
    expect($goodGroupResorted['sort'])->toBe(80);
});

it('Can resort item after certain item', function () {
    $subject = new \App\Models\GoodGroupRepository();
    $itemToResort = $subject->findById(6);
    $itemToSortAfter = $subject->findById(4);

    $itemToResort = $subject->resortAfter($itemToResort, $itemToSortAfter);

    $itemToResort = $itemToResort->fresh();
    $itemToSortAfter = $itemToSortAfter->fresh();

    expect($itemToResort['sort'])->toBe(50);
    expect($itemToSortAfter['sort'])->toBe(40);
});
