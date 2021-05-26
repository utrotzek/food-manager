<?php
namespace App\Repositories;

use App\Interfaces\RepositoryInterfaces\AppStateRepositoryInterface;
use App\Models\AppState;

class AppStateRepository implements AppStateRepositoryInterface
{
    /**
     * @codeCoverageIgnore
     */
    public function all()
    {
        return AppState::query()->orderBy('state_name')->get();
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function create($attributes): AppState
    {
        $model = new AppState($attributes);
        $model->save();
        return $model->fresh();
    }

    /**
     * Updates a certain state entry or creates it, if its not exist
     *
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function updateOrCreate(string $stateName, string $stateValue): AppState
    {
        return AppState::firstOrCreate(
            [
                'state_name' => $stateName
            ],
            [
                'state_value' => $stateValue
            ]
        );
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function update(string $value, AppState $model): AppState
    {
        $model->fill(['state_value' => $value]);
        $model->save();
        return $model->fresh();
    }

    public function findByName(string $stateName): ?AppState
    {
        /** @var ?AppState $model */
        $model = AppState::query()
            ->where('state_name', $stateName)
            ->first();
        return $model;
    }
}
