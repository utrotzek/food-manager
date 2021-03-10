<?php
namespace App\Repositories;

use App\Interfaces\RepositoryInterfaces\StepRepositoryInterface;
use App\Models\Step;
use Illuminate\Database\Eloquent\Collection;

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
     */
    public function findByRecipeId(int $recipeId): Collection
    {
        return Step::query()->orderBy('sort')->where('recipe_id', $recipeId)->get();
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
     * TODO: needs to be moved to an "factory"
     * Updates (or inserts new entries) the given steps by comparing the scription with the given description list
     * @param array $steps The existing steps to compare the description list with
     * @param array $descriptions The list of description with the an eventually updated order
     */
    public function syncStepsWithDescriptionList(Collection $steps, array $descriptions): ?Collection
    {
        $actualSteps = new Collection();

        $sort = 10;

        //determine steps to update
        foreach ($descriptions as $description) {
            $found = false;
            foreach ($steps as $step) {
                if (trim($step['description']) === trim($description)) {
                    $step['sort'] = $sort;
                    $actualSteps->add($step);
                    $found = true;
                }
            }

            if (!$found) {
                $actualSteps->add(
                    $this->prepare([
                        'description' => $description,
                        'sort' => $sort
                    ])
                );
            }
            $sort += 10;
        }

        //delete not existing steps
        foreach ($steps as $step) {
            $found = false;
            foreach ($descriptions as $description) {
                if ($step['description'] === $description) {
                    $found = true;
                }
            }

            if (!$found) {
                $step->delete();
            }
        }
        return $actualSteps;
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
