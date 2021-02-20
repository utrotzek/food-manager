<?php
namespace App\Repositories;

use App\Interfaces\RepositoryInterfaces\GoodGroupRepositoryInterface;
use App\Models\GoodGroup;
use Illuminate\Database\Eloquent\Model;

class GoodGroupRepository implements GoodGroupRepositoryInterface
{
    const SORT_TYPE_FIRST = 'first';
    const SORT_TYPE_LAST = 'last';
    const SORT_TYPE_AFTER = 'after';

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

    public function updateTitle(string $title, GoodGroup $item): GoodGroup
    {
        $item['title'] = $title;
        $item->save();
        return $item->refresh();
    }

    public function resortFirst(GoodGroup $item): GoodGroup
    {
        $this->shiftAllSortIndexes();
        $item->fresh();

        $sort = 0;
        $item['sort'] = $sort;
        $item->save();
        $this->resortAll();

        return $item->fresh();
    }

    public function resortLast(GoodGroup $item): GoodGroup
    {
        $sort = $this->getHighestSort();

        $item['sort'] = $sort;
        $item->save();
        return $item->fresh();
    }

    /**
     * Sorts a given item after the $itemToSortAfter item
     */
    public function resortAfter(GoodGroup $item, GoodGroup $itemToSortAfter): GoodGroup
    {
        $afterSort = $itemToSortAfter['sort'];
        $newSort = $afterSort + 10;
        $this->shiftAfterSortIndex($newSort);
        $item['sort'] = $newSort;
        $item->save();

        //resort all items to get a fresh sort index
        $this->resortAll();
        return $item->refresh();
    }

    /**
     * Creates a new good group item after a given sort index
     */
    public function createFirst(string $title): GoodGroup
    {
        $this->shiftAllSortIndexes();
        $sort = 0;
        $item = new GoodGroup(['title' => $title]);
        $item['sort'] = $sort;
        $item->save();

        //resort all items to get a fresh sort index
        $this->resortAll();
        $item->refresh();

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
        $item = new GoodGroup(['title' => $title]);
        $item['sort'] = $sort;
        $item->save();

        return GoodGroup::where('title', $title)->first();
    }

    /**
     * Creates a new good group item after a given sort index
     *
     * @param string $title
     * @param int $afterSort
     * @return GoodGroup
     */
    public function createAfter(string $title, GoodGroup $item): GoodGroup
    {
        $afterSort = $item['sort'];
        $newSort = $afterSort + 10;
        $this->shiftAfterSortIndex($newSort);
        $item = new GoodGroup(['title' => $title]);
        $item['sort'] = $newSort;
        $item->save();

        //resort all items to get a fresh sort index
        $this->resortAll();
        return $item->refresh();
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
