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

    public function __construct(GoodRepository $goodRepository, UnitRepository $unitRepository)
    {
        $this->goodRepository = $goodRepository;
        $this->unitRepository = $unitRepository;
    }

    public function newIngredientList(array $ingredientList): Collection
    {
        $itemCollection = new Collection();
        foreach ($ingredientList as $ingredientItem) {
            $item = $this->newIngredient(
                $ingredientItem['unitId'],
                $ingredientItem['goodId'],
                $ingredientItem['amount']
            );
            $itemCollection->add($item);
        }
        return $itemCollection;
    }

    public function newIngredient(int $unitId, int $goodId, int $amount): Ingredient
    {
        $ingredient = new Ingredient(['unit_amount' => $amount]);

        $good = $this->goodRepository->findByIdOrSlug($goodId);
        $unit = $this->unitRepository->findByIdOrSlug($unitId);
        $ingredient->good()->associate($good);
        $ingredient->unit()->associate($unit);
        return $ingredient;
    }
}
