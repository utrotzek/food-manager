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

    public function findByTitle(string $title): GoodGroup
    {
        return GoodGroup::where('title', $title)->first();
    }

    public function findBySort(int $sort): GoodGroup
    {
        return GoodGroup::where('sort', $sort)->first();
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
        $itemsAfterSort = GoodGroup::orderBy('sort', 'desc')->get();

        /** @var Model $item */
        foreach ($itemsAfterSort as $item) {
            $item['sort'] += 10;
            $item->save();
        }
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
        $highestSort = GoodGroup::orderBy('sort', 'desc')->value('sort');
        $sort = $highestSort + 10;
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
        $itemsAfterSort = GoodGroup::
            where('sort', '>', $afterSort)
            ->orderBy('sort', 'desc')
            ->get();

        /** @var Model $item */
        foreach ($itemsAfterSort as $item) {
            $item['sort'] += 10;
            $item->save();
        }
        $newSort = $afterSort + 10;
        $goodGroup = new GoodGroup(['title' => $title]);
        $goodGroup['sort'] = $newSort;
        $goodGroup->save();

        //resort all items to get a fresh sort index
        $this->resortAll();
        $goodGroup->refresh();
        return GoodGroup::where('title', $title)->first();
    }

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
}
