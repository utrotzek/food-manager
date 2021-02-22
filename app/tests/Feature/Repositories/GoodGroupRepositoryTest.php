<?php
use App\Repositories\GoodGroupRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(
    DatabaseTransactions::class,
    DatabaseMigrations::class
);


beforeEach(function () {
    $this->seed('DevelopmentSeeder');
});


it('Can insert entries sorted first', function () {
    $subject = new GoodGroupRepository();
    $subject->createFirst('TestTitle');

    $goodGorup = $subject->findByTitle('TestTitle');
    expect($goodGorup['sort'])->toBe(10);
});

it('Can insert entries sorted last', function () {
    $subject = new GoodGroupRepository();
    $subject->createLast('TestTitle');

    $goodGorup = $subject->findByTitle('TestTitle');
    expect($goodGorup['sort'])->toBe(100);
});

it('Can insert after sort entry', function () {
    $subject = new GoodGroupRepository();

    $itemTAfter = $subject->findById(3);
    $itemToSortAfter = $subject->findById(2);
    $createdItem = $subject->createAfter('TestTitle', $itemToSortAfter);

    $itemTAfter = $itemTAfter->fresh();
    expect($createdItem['sort'])->toBe(30);
    expect($itemTAfter['sort'])->toBe(40);
});

it('Can update the title', function () {
    $subject = new GoodGroupRepository();
    $goodGroupToUpdate = $subject->findByTitle('Obst und Gemüse');
    $subject->updateTitle('A new title', $goodGroupToUpdate);

    expect($subject->findByTitle('Obst und Gemüse'))->toBeNull();
    expect($subject->findByTitle('A new title'))->not()->toBeNull();
});

it('Can resort item as first element', function () {
    $subject = new GoodGroupRepository();
    $goodGroupToResort = $subject->findById(2);
    expect($goodGroupToResort['sort'])->toBe(20);

    $subject->resortFirst($goodGroupToResort);
    $goodGroupResorted = $subject->findById(2);
    expect($goodGroupResorted['sort'])->toBe(10);
});

it('Can resort item as last element', function () {
    $subject = new GoodGroupRepository();
    $goodGroupToResort = $subject->findById(2);
    expect($goodGroupToResort['sort'])->toBe(20);

    $subject->resortLast($goodGroupToResort);
    $goodGroupResorted = $subject->findById(2);
    expect($goodGroupResorted['sort'])->toBe(100);
});

it('Can resort item after certain item', function () {
    $subject = new GoodGroupRepository();
    $itemToResort = $subject->findById(6);
    $itemToSortAfter = $subject->findById(4);

    $itemToResort = $subject->resortAfter($itemToResort, $itemToSortAfter);

    $itemToResort = $itemToResort->fresh();
    $itemToSortAfter = $itemToSortAfter->fresh();

    expect($itemToResort['sort'])->toBe(50);
    expect($itemToSortAfter['sort'])->toBe(40);
});
