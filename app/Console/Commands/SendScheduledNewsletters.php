<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Newsletter;
use App\Models\User;
use App\Mail\NewsLetter as NewsLetterMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendScheduledNewsletters extends Command
{
    protected $signature = 'newsletters:send-scheduled';
    protected $description = 'Send newsletters that are scheduled and their send time has arrived';

    public function handle()
    {
        $this->info('Current time: ' . Carbon::now()->format('Y-m-d H:i:s'));

        // Show all scheduled newsletters for debugging
        $allScheduled = Newsletter::where('status', 'scheduled')->get();
        if ($allScheduled->isNotEmpty()) {
            $this->info('Total scheduled newsletters: ' . $allScheduled->count());
            foreach ($allScheduled as $s) {
                $this->info("  - {$s->subject} scheduled for: " . ($s->scheduled_at ? $s->scheduled_at->format('Y-m-d H:i:s') : 'NULL'));
            }
        }

        // Find all scheduled newsletters where scheduled_at is now or in the past
        $scheduledNewsletters = Newsletter::where('status', 'scheduled')
            ->where('scheduled_at', '<=', Carbon::now())
            ->get();

        if ($scheduledNewsletters->isEmpty()) {
            $this->info('No scheduled newsletters ready to send.');
            return 0;
        }

        foreach ($scheduledNewsletters as $newsletter) {
            $this->info("Sending: {$newsletter->subject}");

            // Get recipients based on newsletter settings
            $users = $this->getRecipients($newsletter);
            $this->info("Found {$users->count()} recipients for recipient_type: {$newsletter->recipient_type}");

            $count = 0;
            $failed = 0;

            foreach ($users as $user) {
                try {
                    $this->info("Attempting to send to: {$user->email}");
                    Mail::to($user->email)->send(new NewsLetterMail($newsletter->subject, $newsletter->body));
                    $count++;
                    $this->info("✓ Sent to {$user->email}");
                } catch (\Exception $e) {
                    $failed++;
                    $this->error("✗ Failed to send to {$user->email}: " . $e->getMessage());
                }
            }

            // Update newsletter status
            $newsletter->update([
                'status' => 'sent',
                'sent_at' => now(),
                'recipients_count' => $count
            ]);

            $this->info("✓ Successfully sent to {$count} recipients ({$failed} failed)");
        }

        $this->info("Successfully processed " . $scheduledNewsletters->count() . " newsletter(s).");
        return 0;
    }

    private function getRecipients($newsletter)
    {
        $query = User::where('role', 'user');

        switch ($newsletter->recipient_type) {
            case 'premium':
                $query->where('is_premium', 1);
                break;
            case 'free':
                $query->where('is_premium', 0);
                break;
            case 'selected':
                if ($newsletter->selected_users) {
                    $query->whereIn('id', $newsletter->selected_users);
                }
                break;
            case 'all':
            default:
                // No additional filter
                break;
        }

        return $query->get();
    }
}

