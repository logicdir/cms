<?php

namespace App\Modules\Content\Repositories;

use App\Modules\Content\Models\Category;
use App\Modules\Content\Models\Content;
use App\Modules\Content\Models\Revision;
use App\Modules\Content\Models\Tag;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ContentRepository
{
    public function findBySlug(string $slug, string $type, ?string $locale = null): ?Content
    {
        $query = Content::where('slug', $slug)
            ->where('type', $type)
            ->with(['author', 'tags', 'meta']);

        if ($type === 'post') {
            $query->with('post.category');
        } elseif ($type === 'page') {
            $query->with('page.parent');
        }

        $content = $query->first();

        if ($locale && $content) {
            $translation = $content->translate($locale);
            if ($translation) {
                // Merge translation data
                $content->title = $translation->title;
                $content->slug = $translation->slug;
                $content->excerpt = $translation->excerpt;
                $content->content = $translation->content;
                $content->meta_title = $translation->meta_title;
                $content->meta_description = $translation->meta_description;
            }
        }

        return $content;
    }

    public function getPublished(string $type, int $limit = 15, string $order = 'desc'): LengthAwarePaginator
    {
        return Content::ofType($type)
            ->published()
            ->with(['author:id,name,email', 'tags:id,name,slug'])
            ->when($type === 'post', fn($q) => $q->with('post.category:id,name,slug'))
            ->orderBy('published_at', $order)
            ->paginate($limit);
    }

    public function getByCategory(int $categoryId, bool $paginate = true)
    {
        $query = Content::ofType('post')
            ->published()
            ->whereHas('post', fn($q) => $q->where('category_id', $categoryId))
            ->with(['author:id,name', 'post.category', 'tags'])
            ->orderBy('published_at', 'desc');

        return $paginate ? $query->paginate(15) : $query->get();
    }

    public function getByTag(int $tagId, bool $paginate = true)
    {
        $query = Content::published()
            ->whereHas('tags', fn($q) => $q->where('tags.id', $tagId))
            ->with(['author:id,name', 'tags'])
            ->orderBy('published_at', 'desc');

        return $paginate ? $query->paginate(15) : $query->get();
    }

    public function search(string $query, array $filters = []): LengthAwarePaginator
    {
        $builder = Content::search($query)
            ->with(['author:id,name', 'tags']);

        if (!empty($filters['type'])) {
            $builder->ofType($filters['type']);
        }

        if (!empty($filters['status'])) {
            $builder->where('status', $filters['status']);
        } else {
            $builder->published();
        }

        return $builder->orderBy('published_at', 'desc')->paginate(15);
    }

    public function createRevision(int $contentId): Revision
    {
        $content = Content::findOrFail($contentId);
        
        return Revision::create([
            'content_id' => $content->id,
            'title' => $content->title,
            'content' => $content->content,
            'excerpt' => $content->excerpt,
            'user_id' => auth()->id(),
            'created_at' => now(),
        ]);
    }

    public function restoreRevision(int $revisionId): Content
    {
        $revision = Revision::findOrFail($revisionId);
        $content = $revision->content;

        // Create a new revision of current state before restoring
        $this->createRevision($content->id);

        $content->update([
            'title' => $revision->title,
            'content' => $revision->content,
            'excerpt' => $revision->excerpt,
        ]);

        $this->clearCache($content->id, $content->type);

        return $content->fresh();
    }

    public function schedule(int $contentId, Carbon $date): Content
    {
        $content = Content::findOrFail($contentId);
        
        $content->update([
            'status' => 'scheduled',
            'published_at' => $date,
        ]);

        $this->clearCache($content->id, $content->type);

        return $content;
    }

    public function publish(int $contentId): Content
    {
        $content = Content::findOrFail($contentId);
        
        $content->update([
            'status' => 'published',
            'published_at' => $content->published_at ?? now(),
        ]);

        $this->clearCache($content->id, $content->type);

        return $content;
    }

    public function draft(int $contentId): Content
    {
        $content = Content::findOrFail($contentId);
        
        $content->update(['status' => 'draft']);
        $this->clearCache($content->id, $content->type);

        return $content;
    }

    public function trash(int $contentId): bool
    {
        $content = Content::findOrFail($contentId);
        $this->clearCache($content->id, $content->type);
        return $content->delete();
    }

    public function bulkPublish(array $ids): int
    {
        $count = Content::whereIn('id', $ids)->update([
            'status' => 'published',
            'published_at' => DB::raw('COALESCE(published_at, NOW())'),
        ]);

        $this->clearCache();
        return $count;
    }

    public function bulkTrash(array $ids): int
    {
        $count = Content::whereIn('id', $ids)->delete();
        $this->clearCache();
        return $count;
    }

    public function getRelated(int $contentId, int $limit = 5): Collection
    {
        $content = Content::findOrFail($contentId);
        
        $tagIds = $content->tags->pluck('id');

        return Content::published()
            ->where('id', '!=', $contentId)
            ->where('type', $content->type)
            ->whereHas('tags', fn($q) => $q->whereIn('tags.id', $tagIds))
            ->with(['author:id,name', 'tags'])
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    protected function clearCache(?int $id = null, ?string $type = null): void
    {
        try {
            if ($id) {
                Cache::tags(['content', "content.{$id}"])->flush();
            }
            if ($type) {
                Cache::tags(['content', "content.{$type}"])->flush();
            }
            if (!$id && !$type) {
                Cache::tags(['content'])->flush();
            }
        } catch (\Throwable $e) {
            // Fallback for drivers that don't support tags
            Cache::forget("content.{$id}");
            Cache::forget("content.list.{$type}");
        }
    }
}
