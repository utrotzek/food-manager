<?php
namespace App\Repositories;

use App\Interfaces\RepositoryInterfaces\DayRepositoryInterface;
use App\Models\Day;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class DayRepository implements DayRepositoryInterface
{
    /**
     * @codeCoverageIgnore
     */
    public function all()
    {
        return Day::all();
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function create($attributes): Day
    {
        $day = new Day($attributes);
        $day->save();
        return $day->fresh();
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function update(array $attributes, Day $day): Day
    {
        $day->fill($attributes);
        $day->save();
        return $day->fresh();
    }

    public function setDone(Day $day)
    {
        $day['done'] = true;
        $day->save();
        return $day->fresh();
    }

    public function findByIdOrDate(string $idOrDate): ?Day
    {
        $date = null;
        $id = null;
        if (!is_numeric($idOrDate)) {
            $date = new Carbon($idOrDate);
        } else {
            $id = (int)$idOrDate;
        }

        /** @var Day $day */
        $day = Day::query()
            ->when($id, function ($query) use ($id) {
                return $query->where('id', $id);
            })
            ->when($date, function ($query) use ($date) {
                return $query->orWhereDate('date', $date->toDateString());
            })
            ->first();
        return $day;
    }

    public function findByRange(Carbon $from, Carbon $to): Collection
    {
        $dayCollection = new Collection();
        do {
            $day = Day::where('date', $from)->get()->first();
            if (!$day) {
                $day = Day::create([
                   'date' => $from,
                   'done' => false
                ]);
            }

            $dayCollection->add($day);
            $from->addDay();
        } while ($from <= $to);

        return $dayCollection;
    }
}
