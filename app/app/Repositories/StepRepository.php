<?php
namespace App\Repositories;

use App\Models\Step;

class StepRepository implements StepRepositoryInterface
{
    /**
     * @codeCoverageIgnore
     */
    public function all()
    {
        return Step::query()->orderBy('sort')->get();
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function prepare(array $attributes): Step
    {
        return new Step($attributes);
    }

    /**
     * Creates a new array of step objects by the given array of descriptions
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function prepareByDescriptionArray(array $descriptions): array
    {
        $steps = [];
        $sort = 10;
        foreach ($descriptions as $description) {
            $attributes = [
                'description'  => $description,
                'sort' => $sort
            ];
            $steps[] = $this->prepare($attributes);
            $sort += 10;
        }
        return $steps;
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function create($attributes): Step
    {
        $step = new Step($attributes);
        $step->save();
        return $step->fresh();
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function update(array $attributes, Step $step): Step
    {
        $step->fill($attributes);
        $step->save();
        return $step->fresh();
    }
}
