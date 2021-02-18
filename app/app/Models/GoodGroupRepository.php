<?php


namespace App\Models;

use App\Interfaces\RepositoryInterfaces\GoodGroupRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class GoodGroupRepository implements GoodGroupRepositoryInterface
{
    const SORT_TYPYE_FIRST = 'first';
    const SORT_TYPYE_LAST = 'last';
    const SORT_TYPYE_AFTER = 'after';

    /**
     * @codeCoverageIgnore
     */
    public function all()
    {
        return GoodGroup::all();
    }

    public function findByTitle(string $title): ?GoodGroup
    {
        return GoodGroup::where('title', $title)->first();
    }

    public function findBySort(int $sort): ?GoodGroup
    {
        return GoodGroup::where('sort', $sort)->first();
    }

    public function findById(int $id): GoodGroup
    {
        return GoodGroup::find($id);
    }

    public function updateTitle(string $title, GoodGroup $goodGroup): GoodGroup
    {
        $goodGroup['title'] = $title;
        $goodGroup->save();
        return $goodGroup->refresh();
    }

    public function resortFirst(GoodGroup $goodGroup): GoodGroup
    {
        $this->shiftAllSortIndexes();
        $goodGroup->fresh();

        $sort = 0;
        $goodGroup['sort'] = $sort;
        $goodGroup->save();
        $this->resortAll();

        return $goodGroup->fresh();
    }

    public function resortLast(GoodGroup $goodGroup): GoodGroup
    {
        $sort = $this->getHighestSort();

        $goodGroup['sort'] = $sort;
        $goodGroup->save();
        return $goodGroup->fresh();
    }

    public function resortAfter(GoodGroup $goodGroup, int $afterSort): GoodGroup
    {
        $newSort = $afterSort + 10;
        $this->shiftAfterSortIndex($newSort);
        $goodGroup['sort'] = $newSort;
        $goodGroup->save();

        //resort all items to get a fresh sort index
        $this->resortAll();
        return $goodGroup->refresh();
    }

    /**
     * Creates a new good group item after a given sort index
     *
     * @param string $title
     * @param int $afterSort
     * @return GoodGroup
     */
    public function createFirst(string $title): GoodGroup
    {
        $this->shiftAllSortIndexes();
        $sort = 0;
        $goodGroup = new GoodGroup(['title' => $title]);
        $goodGroup['sort'] = $sort;
        $goodGroup->save();

        //resort all items to get a fresh sort index
        $this->resortAll();
        $goodGroup->refresh();

        return GoodGroup::where('title', $title)->first();
    }


    /**
     * Creates a new good group item after a given sort index
     *
     * @param string $title
     * @param int $afterSort
     * @return GoodGroup
     */
    public function createLast(string $title): GoodGroup
    {
        $sort = $this->getHighestSort();
        $goodGroup = new GoodGroup(['title' => $title]);
        $goodGroup['sort'] = $sort;
        $goodGroup->save();

        return GoodGroup::where('title', $title)->first();
    }

    /**
     * Creates a new good group item after a given sort index
     *
     * @param string $title
     * @param int $afterSort
     * @return GoodGroup
     */
    public function createAfter(string $title, int $afterSort): GoodGroup
    {
        $newSort = $afterSort + 10;
        $this->shiftAfterSortIndex($newSort);
        $goodGroup = new GoodGroup(['title' => $title]);
        $goodGroup['sort'] = $newSort;
        $goodGroup->save();

        //resort all items to get a fresh sort index
        $this->resortAll();
        $goodGroup->refresh();
        return GoodGroup::where('title', $title)->first();
    }

    /**
     *
     * Generates a fresh and clean sort index for all existing entries
     *
     */
    protected function resortAll()
    {
        $sort =  10;
        $allItems = GoodGroup::query()->orderBy('sort')->get();
        foreach ($allItems as $item) {
            $item['sort'] = $sort;
            $item->save();
            $sort += 10;
        }
    }

    /**
     * Increments all sort indexes by 10
     */
    protected function shiftAllSortIndexes()
    {
        $itemsAfterSort = GoodGroup::orderBy('sort', 'desc')->get();

        /** @var Model $item */
        foreach ($itemsAfterSort as $item) {
            $item['sort'] += 10;
            $item->save();
        }
    }

    /**
     * Gets the highest sort index of the database table
     *
     * @return int
     */
    protected function getHighestSort(): int
    {
        $highestSort = GoodGroup::orderBy('sort', 'desc')->value('sort');
        return $highestSort + 10;
    }

    protected function shiftAfterSortIndex($sortIndex)
    {
        $itemsAfterSort = GoodGroup::where('sort', '>=', $sortIndex)
            ->orderBy('sort', 'desc')
            ->get();

        /** @var Model $item */
        foreach ($itemsAfterSort as $item) {
            $item['sort'] += 10;
            $item->save();
        }
    }
}
