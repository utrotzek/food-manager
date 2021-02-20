<?php
namespace App\Repositories;

use App\Interfaces\RepositoryInterfaces\TagRepositoryInterface;
use App\Models\Tag;

class TagRepository implements TagRepositoryInterface
{
    /**
     * @codeCoverageIgnore
     */
    public function all()
    {
        return Tag::query()->orderBy('title')->get();
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function create($attributes): Tag
    {
        $tag = new Tag($attributes);
        $tag->save();
        return $tag->fresh();
    }

    /**
     * @codeCoverageIgnore
     * Just glue code. No tests necessary
     */
    public function update(array $attributes, Tag $tag): Tag
    {
        $tag->fill($attributes);
        $tag->save();
        return $tag->fresh();
    }
}
