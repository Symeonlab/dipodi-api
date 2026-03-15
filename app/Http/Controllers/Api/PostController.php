<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    /**
     * Allowed HTML tags for post content.
     */
    private const ALLOWED_TAGS = '<p><br><strong><b><em><i><u><s><h1><h2><h3><h4><h5><h6><ul><ol><li><a><img><blockquote><pre><code><hr><table><thead><tbody><tr><th><td><span><div><figure><figcaption>';

    /**
     * Sanitize HTML content — strip dangerous tags and event handler attributes.
     */
    private function sanitizeHtml(?string $html): ?string
    {
        if (empty($html)) {
            return $html;
        }

        $clean = strip_tags($html, self::ALLOWED_TAGS);
        $clean = preg_replace('/\s+on\w+\s*=\s*["\'][^"\']*["\']/i', '', $clean);
        $clean = preg_replace('/\s+on\w+\s*=\s*\S+/i', '', $clean);
        $clean = preg_replace('/\b(href|src)\s*=\s*["\']?\s*javascript\s*:/i', '$1="', $clean);
        $clean = preg_replace('/\bsrc\s*=\s*["\']?\s*data\s*:/i', 'src="', $clean);

        return $clean;
    }

    /**
     * Get all published posts (feed). Uses model accessors for locale resolution.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = min($request->input('per_page', 10), 50);

        $posts = Post::where('is_published', true)
            ->with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $transformed = $posts->getCollection()->map(function ($post) {
            $content = $post->content;

            return [
                'id' => $post->id,
                'title' => e($post->title),
                'content' => $this->sanitizeHtml($content),
                'excerpt' => \Str::limit(strip_tags($content), 150),
                'slug' => $post->slug,
                'featured_image' => $post->featured_image,
                'author' => $post->user?->name,
                'published_at' => $post->created_at->toIso8601String(),
                'reading_time' => $this->calculateReadingTime($content),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $transformed,
            'meta' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
            ],
        ]);
    }

    /**
     * Get a single post by slug.
     */
    public function show(Request $request, string $slug): JsonResponse
    {
        $post = Post::where('slug', $slug)
            ->where('is_published', true)
            ->with('user:id,name')
            ->first();

        if (!$post) {
            return ApiResponse::notFound(__('api.post_not_found'));
        }

        $content = $post->content;

        return ApiResponse::success([
            'id' => $post->id,
            'title' => e($post->title),
            'content' => $this->sanitizeHtml($content),
            'slug' => $post->slug,
            'featured_image' => $post->featured_image,
            'author' => $post->user?->name,
            'published_at' => $post->created_at->toIso8601String(),
            'reading_time' => $this->calculateReadingTime($content),
        ]);
    }

    /**
     * Get latest posts for home screen.
     */
    public function latest(Request $request): JsonResponse
    {
        $limit = min($request->input('limit', 5), 20);

        $posts = Post::where('is_published', true)
            ->with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => e($post->title),
                    'excerpt' => \Str::limit(strip_tags($post->content), 100),
                    'slug' => $post->slug,
                    'featured_image' => $post->featured_image,
                    'published_at' => $post->created_at->toIso8601String(),
                ];
            });

        return ApiResponse::success($posts);
    }

    /**
     * Calculate estimated reading time in minutes.
     */
    private function calculateReadingTime(?string $content): int
    {
        if (!$content) {
            return 1;
        }

        $wordCount = str_word_count(strip_tags($content));
        $readingTime = ceil($wordCount / 200);

        return max(1, (int) $readingTime);
    }
}
