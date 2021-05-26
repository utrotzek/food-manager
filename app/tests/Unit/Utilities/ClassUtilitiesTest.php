<?php
use App\Utilities\ClassUtilities;

it('can extract model name by a given repository name', function () {
    $repositoryName = 'App\Repositories\MyModelRepository';
    $expectedName = 'App\Models\MyModel';
    expect(ClassUtilities::extractModelFromRepositoryName($repositoryName))->toBe($expectedName);
});
