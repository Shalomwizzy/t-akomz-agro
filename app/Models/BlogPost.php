<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'cover_image', 'author_name',
        'category', 'tags', 'is_published', 'published_at',
        'meta_title', 'meta_description', 'views',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'tags'         => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (BlogPost $post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->whereNotNull('published_at');
    }

    public function getReadingTimeAttribute(): int
    {
        $wordCount = str_word_count(strip_tags($this->content));
        return (int) max(1, ceil($wordCount / 200));
    }

    public function getCoverImageUrlAttribute(): string
    {
        return $this->cover_image
            ? asset('storage/' . $this->cover_image)
            : asset('images/blog-placeholder.jpg');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
