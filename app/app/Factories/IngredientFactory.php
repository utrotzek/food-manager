<?php
namespace App\Factories;

use App\Models\Ingredient;
use App\Repositories\GoodRepository;
use App\Repositories\IngredientCategoryRepository;
use App\Repositories\UnitRepository;
use Illuminate\Database\Eloquent\Collection;

class IngredientFactory
{
    protected GoodRepository $goodRepository;
    protected UnitRepository $unitRepository;
    protected IngredientCategoryRepository $ingredientCategoryRepository;

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function __construct(
        GoodRepository $goodRepository,
        UnitRepository $unitRepository,
        IngredientCategoryRepository $ingredientCategoryRepository
    ) {
        $this->goodRepository = $goodRepository;
        $this->unitRepository = $unitRepository;
        $this->ingredientCategoryRepository = $ingredientCategoryRepository;
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function createIngredientList(array $ingredientList, array $ingredientCategories): Collection
    {
        $itemCollection = new Collection();

        foreach ($ingredientList as $ingredientItem) {
            $categoryId = null;
            if (isset($ingredientItem['category'])) {
                $categoryId = $ingredientCategories[$ingredientItem['category']]['id'] ?? null;
            }

            $item = $this->createIngredient(
                $ingredientItem['unitId'],
                $ingredientItem['goodId'],
                $ingredientItem['amount'],
                $categoryId,
            );
            $itemCollection->add($item);
        }
        return $itemCollection;
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function createIngredient(int $unitId, int $goodId, string $amount, int $categoryId = null): Ingredient
    {
        $ingredient = new Ingredient(['unit_amount' => $amount]);
        $good = $this->goodRepository->findByIdOrSlug($goodId);
        $unit = $this->unitRepository->findByIdOrSlug($unitId);
        $ingredient->good()->associate($good);
        $ingredient->unit()->associate($unit);
        if ($categoryId) {
            $ingredient->category()->associate($this->ingredientCategoryRepository->findById($categoryId));
        }
        return $ingredient;
    }
}
