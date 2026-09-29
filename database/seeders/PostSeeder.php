<?php

namespace Database\Seeders;

use App\Models\Platform;
use App\Models\Post;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $platformIds = Platform::pluck('id');
        $hasActivity = Schema::hasTable('user_activities');

        $topics = [
            'Product launch', 'Weekly update', 'Behind the scenes', 'Customer story',
            'Team spotlight', 'Tips and tricks', 'Event recap', 'Feature announcement',
            'Holiday greetings', 'Industry news', 'Case study', 'Q&A session',
            'Roadmap preview', 'Community shoutout', 'Webinar invite',
        ];

        $statuses = ['draft', 'scheduled', 'scheduled', 'published', 'published', 'failed'];

        foreach (User::all() as $user) {
            if ($user->posts()->exists()) continue;

            foreach ($topics as $i => $topic) {
                $status = $statuses[array_rand($statuses)];
                $title = "{$topic} #" . ($i + 1);

                $time = match ($status) {
                    'draft' => null,
                    'scheduled' => now()->addDays(rand(0, 14))->setTime(rand(8, 20), [0, 15, 30, 45][rand(0, 3)]),
                    default => now()->subDays(rand(1, 30))->setTime(rand(8, 20), [0, 15, 30, 45][rand(0, 3)]),
                };

                $post = Post::create([
                    'user_id' => $user->id,
                    'title' => $title,
                    'content' => "Sample content for {$title}. Written by {$user->name}.",
                    'image_url' => null,
                    'status' => $status,
                    'schedule_time' => $time,
                ]);

                // Pivot rows (post_platforms)
                if ($platformIds->isNotEmpty()) {
                    $ids = $platformIds->random(min(rand(1, 3), $platformIds->count()))->all();
                    $sync = [];
                    foreach ($ids as $id) {
                        $sync[$id] = ['platform_status' => rand(0, 4) ? 'active' : 'inactive'];
                    }
                    $post->platforms()->attach($sync);
                }

                // Activity log
                if ($hasActivity) {
                    UserActivity::create([
                        'user_id' => $user->id,
                        'action' => 'created',
                        'model_type' => Post::class,
                        'model_id' => $post->id,
                        'description' => "Created post \"{$title}\"",
                    ]);

                    if ($status === 'published') {
                        UserActivity::create([
                            'user_id' => $user->id,
                            'action' => 'published',
                            'model_type' => Post::class,
                            'model_id' => $post->id,
                            'description' => "Published post \"{$title}\"",
                        ]);
                    }
                }
            }
        }
    }
}