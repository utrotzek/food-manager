<?php
namespace App\Repositories;

use App\Interfaces\RepositoryInterfaces\UnitRepositoryInterface;
use App\Models\Unit;

class UnitRepository implements UnitRepositoryInterface
{
    /**
     * @codeCoverageIgnore
     */
    public function all()
    {
        return Unit::query()->orderBy('title')->get();
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function create($attributes): Unit
    {
        $unit = new Unit($attributes);
        $unit->save();
        return $unit->fresh();
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function update(array $attributes, Unit $unit): Unit
    {
        $unit->fill($attributes);
        $unit->save();
        return $unit->fresh();
    }

    public function findByIdOrSlug(string $idOrTitle): ?Unit
    {
        /** @var Unit $unit */
        $unit = Unit::query()
            ->where('id', $idOrTitle)
            ->orWhere('title', $idOrTitle)
            ->first();
        return $unit;
    }
}
