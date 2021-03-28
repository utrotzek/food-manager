<?php
namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;

trait DiffDeletableTrait
{
    /**
     * Deletes all objects of a repository in $existingItems which are not present in $actualItems anymore
     *
     * @throws \Exception
     */
    public function deleteDiffObjects(Collection $existingItems, Collection $actualItems): void
    {
        foreach ($existingItems as $existingItem) {
            $found = false;
            foreach ($actualItems as $actualItem) {
                if ($existingItem == $actualItem) {
                    $found = true;
                }
            }

            if (!$found) {
                $existingItem->delete();
            }
        }
    }
}
