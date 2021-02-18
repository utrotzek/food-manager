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
    $subject->createAfter('TestTitle', 20);

    $goodGorup = $subject->findByTitle('TestTitle');
    expect($goodGorup['sort'])->toBe(30);

    $goodGorup = $subject->findBySort(40);
    expect($goodGorup['title'])->toBe('Fleisch');
});
