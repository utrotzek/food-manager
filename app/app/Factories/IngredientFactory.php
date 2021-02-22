<?php
namespace App\Factories;

use App\Models\Ingredient;
use App\Repositories\GoodRepository;
use App\Repositories\UnitRepository;
use Illuminate\Database\Eloquent\Collection;

class IngredientFactory
{
    protected GoodRepository $goodRepository;
    protected UnitRepository $unitRepository;

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function __construct(GoodRepository $goodRepository, UnitRepository $unitRepository)
    {
        $this->goodRepository = $goodRepository;
        $this->unitRepository = $unitRepository;
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function createIngredientList(array $ingredientList): Collection
    {
        $itemCollection = new Collection();
        foreach ($ingredientList as $ingredientItem) {
            $item = $this->createIngredient(
                $ingredientItem['unitId'],
                $ingredientItem['goodId'],
                $ingredientItem['amount']
            );
            $itemCollection->add($item);
        }
        return $itemCollection;
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function createIngredient(int $unitId, int $goodId, int $amount): Ingredient
    {
        $ingredient = new Ingredient(['unit_amount' => $amount]);

        $good = $this->goodRepository->findByIdOrSlug($goodId);
        $unit = $this->unitRepository->findByIdOrSlug($unitId);
        $ingredient->good()->associate($good);
        $ingredient->unit()->associate($unit);
        return $ingredient;
    }
}
