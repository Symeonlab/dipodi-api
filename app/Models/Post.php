<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id', // <-- ADD THIS LINE
        'title_en',
        'title_fr',
        'title_ar',
        'content_en',
        'content_fr',
        'content_ar',
        'slug',
        'featured_image',
        'is_published',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_published' => 'boolean',
    ];

    /**
     * Get the user (author) that owns the post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Accessors — resolve locale automatically via app()->getLocale()
    public function getTitleAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"title_{$locale}"} ?? $this->title_en;
    }

    public function getContentAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"content_{$locale}"} ?? $this->content_en;
    }
}
