<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Services\PostPublisher;
use Illuminate\Console\Command;

class PublishDuePosts extends Command
{
    protected $signature = 'posts:publish-due';
    protected $description = 'Publish scheduled posts whose time has come';

    public function handle(PostPublisher $publisher): int
    {
        $posts = Post::with('platforms')
            ->where('status', 'scheduled')
            ->where('schedule_time', '<=', now())
            ->limit(20)
            ->get();

        foreach ($posts as $post) {
    $publisher->publish($post);
    $post->refresh()->load('platforms');
    $this->info("Post #{$post->id}: {$post->status}");

    foreach ($post->platforms as $platform) {
        if ($platform->pivot->error) {
            $this->error("  {$platform->name}: {$platform->pivot->error}");
        }
    }
}

        return self::SUCCESS;
    }
}