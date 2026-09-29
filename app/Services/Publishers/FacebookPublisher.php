<?php

namespace App\Services\Publishers;

use App\Models\Platform;
use App\Models\Post;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class FacebookPublisher
{
    public function publish(Post $post, Platform $platform): string
    {
        if (!$platform->external_id || !$platform->access_token) {
            throw new RuntimeException('Facebook Page ID or access token is missing.');
        }

        $base = 'https://graph.facebook.com/' . config('services.facebook.graph_version');
        $message = trim($post->title . "\n\n" . $post->content);
        $params = ['access_token' => $platform->access_token];

        if ($post->image_url) {
            $path = Storage::disk('public')->path(Str::after($post->image_url, '/storage/'));
            if (!is_file($path)) {
                throw new RuntimeException('Image file not found.');
            }
            $response = Http::attach('source', fopen($path, 'r'), basename($path))
                ->post("$base/{$platform->external_id}/photos", $params + ['caption' => $message]);
        } else {
            $response = Http::asForm()
                ->post("$base/{$platform->external_id}/feed", $params + ['message' => $message]);
        }

        $response->throw();

        return (string) ($response->json('post_id') ?? $response->json('id'));
    }
}