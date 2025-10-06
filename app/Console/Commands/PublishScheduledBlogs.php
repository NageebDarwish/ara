<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Blog;
use Carbon\Carbon;

class PublishScheduledBlogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blogs:publish-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish blog posts that are scheduled and their publish time has arrived';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Current time: ' . Carbon::now()->format('Y-m-d H:i:s'));

        // Find all scheduled posts where published_at is now or in the past
        $scheduledBlogs = Blog::where('status', 'scheduled')
            ->where('published_at', '<=', Carbon::now())
            ->get();

        // Also show all scheduled posts for debugging
        $allScheduled = Blog::where('status', 'scheduled')->get();
        if ($allScheduled->isNotEmpty()) {
            $this->info('Total scheduled posts: ' . $allScheduled->count());
            foreach ($allScheduled as $s) {
                $this->info("  - {$s->title} scheduled for: " . ($s->published_at ? $s->published_at->format('Y-m-d H:i:s') : 'NULL'));
            }
        }

        if ($scheduledBlogs->isEmpty()) {
            $this->info('No scheduled blogs ready to publish.');
            return 0;
        }

        $count = 0;
        foreach ($scheduledBlogs as $blog) {
            $blog->update(['status' => 'published']);
            $count++;
            $this->info("✓ Published: {$blog->title}");
        }

        $this->info("Successfully published {$count} blog post(s).");
        return 0;
    }
}

