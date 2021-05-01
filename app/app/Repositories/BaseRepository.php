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
     * @returns Model
     */
    public function findById(int $id)
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
     * @returns Model
     */
    public function create($attributes)
    {
        $model = $this->getModel()->make($attributes);
        $model->save();
        return $model->fresh();
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     * @return Model
     */
    public function update(array $attributes, Model $model)
    {
        $model->fill($attributes);
        $model->save();
        return $model->fresh();
    }

    /**
     * @return Model
     */
    private function getModel()
    {
        $modelName = ClassUtilities::extractModelFromRepositoryName(get_class($this));
        return new $modelName;
    }
}
