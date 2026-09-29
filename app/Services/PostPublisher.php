<?php

namespace App\Services;

use App\Models\Post;
use App\Services\Publishers\FacebookPublisher;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;
class PostPublisher
{
    public function __construct(private FacebookPublisher $facebook) {}

    public function publish(Post $post): void
    {
        $failed = false;

        foreach ($post->platforms as $platform) {
            if ($platform->pivot->publish_status === 'published') {
                continue;
            }

           try {
    $externalId = match ($platform->driver) {
        'facebook' => $this->facebook->publish($post, $platform),
        default => throw new RuntimeException("No publisher for '{$platform->name}'."),
    };

    $post->platforms()->updateExistingPivot($platform->id, [
        'publish_status' => 'published',
        'external_post_id' => $externalId,
        'published_at' => now(),
        'error' => null,
    ]);

    Log::info('Post published', [
        'post_id' => $post->id,
        'platform' => $platform->name,
        'external_post_id' => $externalId,
    ]);
} catch (Throwable $e) {
    $failed = true;

    Log::error('Post publish failed', [
        'post_id' => $post->id,
        'platform_id' => $platform->id,
        'platform' => $platform->name,
        'message' => $e->getMessage(),
        'facebook_response' => $e instanceof RequestException ? $e->response->body() : null,
    ]);

    $post->platforms()->updateExistingPivot($platform->id, [
        'publish_status' => 'failed',
        'error' => Str::limit($e->getMessage(), 500),
    ]);
}
        }

        $post->update(['status' => $failed ? 'failed' : 'published']);
    }
}