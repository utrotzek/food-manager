<?php
namespace App\Repositories;

use App\Interfaces\RepositoryInterfaces\RecipeRepositoryInterface;
use App\Models\Recipe;
use Illuminate\Contracts\Pagination\Paginator;

class RecipeRepository implements RecipeRepositoryInterface
{
    /**
     * @codeCoverageIgnore
     */
    public function all()
    {
        return Recipe::query()->get();
    }

    /**
     * @codeCoverageIgnore
     * Already tested by postman tests
     */
    public function searchPaginated(
        ?string $query,
        ?bool $favorites,
        ?bool $remembered,
        ?string $rating,
        ?bool $unrated,
        ?bool $random
    ): Paginator {
        $qb = Recipe::query();
        $qb->leftJoin('ingredients', 'recipes.id', 'ingredients.recipe_id');
        $qb->leftJoin('goods', 'goods.id', 'ingredients.good_id');
        $qb->leftJoin('recipe_tag', 'recipes.id', 'recipe_tag.recipe_id');
        $qb->leftJoin('tags', 'tags.id', 'recipe_tag.tag_id');

        if ($query) {
            $qb->where(function ($qb) use ($query) {
                $qb->where('recipes.title', 'like', '%'.$query.'%');
                $qb->orWhere('goods.title', 'like', '%'.$query.'%');
                $qb->orWhere('tags.title', 'like', '%'.$query.'%');
            });
        }

        if ($favorites) {
            $qb->where('recipes.favorite', 1);
        }

        if ($remembered) {
            $qb->where('recipes.remember', 1);
        }

        if ($rating !== null) {
            $rating = str_replace(',', '.', $rating);
            if (strstr($rating, '>') !== false) {
                $rating = (float)str_replace('>', '', $rating);
                $qb->where('recipes.rating', '>', $rating);
            } elseif (strstr($rating, '<') !== false) {
                $rating = (float)str_replace('<', '', $rating);
                $qb->where('recipes.rating', '<', $rating);
            } else {
                $qb->where('recipes.rating', $rating);
            }
        }

        if ($unrated) {
            $qb->where('recipes.rating', null);
        }

        if ($random) {
            $qb->inRandomOrder();
        }

        $qb->select('recipes.*');
        $qb->distinct();
        return $qb->orderBy('recipes.title')
            ->simplePaginate(9);
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function randomPaginated(): Paginator
    {
        return Recipe::query()->inRandomOrder()->paginate(9);
    }
    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function create($attributes): Recipe
    {
        $tag = new Recipe($attributes);
        $tag->save();
        return $tag->fresh();
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function update(array $attributes, Recipe $recipe): Recipe
    {
        $recipe->fill($attributes);
        $recipe->save();
        return $recipe->fresh();
    }

    public function findByIdOrSlug(string $idOrTitle): ?Recipe
    {
        /** @var Recipe $recipe */
        $recipe = Recipe::query()
            ->where('id', $idOrTitle)
            ->orWhere('title', $idOrTitle)
            ->first();
        return $recipe;
    }
}
