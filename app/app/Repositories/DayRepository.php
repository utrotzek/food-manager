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

    public function findByIdOrDate(string $idOrDate): ?Day
    {
        /** @var Day $day */
        $day = Day::query()
            ->where('id', $idOrDate)
            ->orWhere('date', $idOrDate)
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
