<?php
namespace App\Repositories;

use App\Models\Meal;
use App\Utilities\ClassUtilities;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected $sortColumn = 'id';

    /**
     * @codeCoverageIgnore
     */
    public function findById(int $id): Model
    {
        return $this->getModel()::find($id);
    }

    /**
     * @codeCoverageIgnore
     */
    public function all(): Collection
    {
        return $this->getModel()::query()->orderBy($this->sortColumn)->get();
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function create($attributes): Model
    {
        $model = $this->getModel()->make($attributes);
        $model->save();
        return $model->fresh();
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function update(array $attributes, Model $model): Model
    {
        $model->fill($attributes);
        $model->save();
        return $model->fresh();
    }

    private function getModel(): Model
    {
        $modelName = ClassUtilities::extractModelFromRepositoryName(get_class($this));
        return new $modelName;
    }
}
