<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\Post;

class CommentsSeeder extends Seeder
{
    public function run()
    {
        $posts = Post::all();
        
        $commenters = [
            ['name' => 'Sarah Johnson', 'email' => 'sarah@example.com'],
            ['name' => 'Michael Chen', 'email' => 'michael@example.com'],
            ['name' => 'Emma Rodriguez', 'email' => 'emma@example.com'],
            ['name' => 'David Kim', 'email' => 'david@example.com'],
            ['name' => 'Lisa Thompson', 'email' => 'lisa@example.com'],
            ['name' => 'Alex Wilson', 'email' => 'alex@example.com'],
            ['name' => 'Maria Garcia', 'email' => 'maria@example.com'],
            ['name' => 'James Brown', 'email' => 'james@example.com'],
            ['name' => 'Jennifer Lee', 'email' => 'jennifer@example.com'],
            ['name' => 'Robert Taylor', 'email' => 'robert@example.com'],
        ];

        $comments = [
            'Excellent article! This really helped me understand the concept better.',
            'Thanks for sharing this detailed explanation. Very useful information!',
            'I had been struggling with this topic, but your tutorial made it crystal clear.',
            'Great guide! Looking forward to more content like this in the future.',
            'Well written and easy to follow. Keep up the fantastic work!',
            'This is exactly what I was looking for. Thank you for the comprehensive guide.',
            'Amazing tutorial! The examples really helped me grasp the concepts.',
            'Very informative post. I learned a lot from reading this.',
            'Clear explanations and practical examples. Bookmarked for future reference!',
            'Outstanding content! This will definitely help me in my projects.',
            'Thank you for taking the time to write such a detailed post.',
            'Perfect timing! I was just working on something similar.',
            'The step-by-step approach makes this very easy to understand.',
            'Great work! Your explanations are always so clear and helpful.',
            'This article saved me hours of research. Much appreciated!',
        ];

        foreach ($posts as $post) {
            // Create 5 random comments for each post
            for ($i = 0; $i < 5; $i++) {
                $commenter = $commenters[array_rand($commenters)];
                $commentText = $comments[array_rand($comments)];
                
                Comment::create([
                    'post_id' => $post->id,
                    'author_name' => $commenter['name'],
                    'author_email' => $commenter['email'],
                    'body' => $commentText,
                    'is_approved' => rand(0, 10) > 1, // 90% approved, 10% pending
                    'created_at' => $post->published_at->addHours(rand(1, 168)), // Comments within a week of post
                ]);
            }
        }
    }
}
