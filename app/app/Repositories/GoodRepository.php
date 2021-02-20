<?php
namespace App\Repositories;

use App\Interfaces\RepositoryInterfaces\GoodRepositoryInterface;
use App\Models\Good;
use App\Models\GoodGroup;

class GoodRepository implements GoodRepositoryInterface
{
    /**
     * @codeCoverageIgnore
     */
    public function all()
    {
        return Good::all();
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function create($attributes, GoodGroup $goodGroup): Good
    {
        $good = new Good($attributes);
        $good->goodGroup()->associate($goodGroup);
        $good->save();
        return $good->fresh();
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function update(array $attributes, Good $good, GoodGroup $goodGroup): Good
    {
        $good->fill($attributes);
        $existingGroup = $good->goodGroup()->get();
        if ($existingGroup !== $goodGroup) {
            $good->goodGroup()->disassociate();
            $good->goodGroup()->associate($goodGroup);
        }
        $good->save();
        return $good->fresh();
    }
}
