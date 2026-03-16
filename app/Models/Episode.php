<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Episode extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'body',
        'apple_podcast_episode_id',
        'published_at',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'date',
            'is_published' => 'boolean',
        ];
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where('published_at', '<=', now());
    }

    public function getTagsAsClassesAttribute(): string
    {
        return $this->tags->pluck('slug')->implode(' ');
    }

    public function renderedBody(): string
    {
        $html = Str::markdown($this->body);

        // Wrap [Label] patterns at end of <li> content in <small> tags
        $html = preg_replace(
            '/\[([^\]]+)\](<\/li>)/',
            '<small>[$1]</small>$2',
            $html
        );

        return $html;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
