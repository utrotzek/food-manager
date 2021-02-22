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
     */
    public function findByTitle(string $title): ?Tag
    {
        return Tag::where('title', $title)->first();
    }

    public function findByIdOrTitle(string $slugOrId): ?Tag
    {
        /** @var Tag $tag */
        $tag = Tag::query()
            ->where('title', $slugOrId)
            ->orWhere('id', $slugOrId)
            ->first();
        return $tag;
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
