<?php
use App\Repositories\StepRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(
    DatabaseTransactions::class,
    DatabaseMigrations::class
);

beforeEach(function () {
    $this->seed('DatabaseSeeder');
});

it('Can create steps in correct order', function () {
    $subject = new StepRepository();

    $steps = $subject->prepareByDescriptionArray([
        'step 1',
        'step 2'
    ]);

    expect($steps[0]['sort'])->toBe(10);
    expect($steps[0]['description'])->toBe('step 1');
    expect($steps[1]['sort'])->toBe(20);
    expect($steps[1]['description'])->toBe('step 2');
});

it('Can sync steps with an updated list of step-descriptions', function () {
    $subject = new StepRepository();

    $stepsBeforeSync = $subject->findByRecipeId(1);
    $stepsAfterSync = $subject->syncStepsWithDescriptionList(
        $stepsBeforeSync,
        [
        'step 1',
        'Gewürze hinzufügen',
        'step 3'
    ]
    );
    expect($stepsAfterSync[0]['description'])->toBe('step 1');
    expect($stepsAfterSync[1]['description'])->toBe('Gewürze hinzufügen');
    expect($stepsAfterSync[2]['description'])->toBe('step 3');
    expect($stepsAfterSync)->not->toHaveKey(3);
});
