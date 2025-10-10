<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NewsletterTemplate;

class NewsletterTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Load week-1 newsletter template
        $week1Html = $this->getWeek1NewsletterHtml();

        NewsletterTemplate::updateOrCreate(
            ['name' => 'week_1_newsletter'],
            [
                'subject' => 'Your Weekly Arabic Learning Update 📚',
                'html_content' => $week1Html,
                'variables' => json_encode(['USER_NAME', 'VIDEO_TITLE', 'VIDEO_URL', 'VIDEO_DESCRIPTION']),
                'is_active' => true,
                'description' => 'Weekly newsletter template with featured video and acquisition tips',
            ]
        );

        // Load week-1 newsletter template (alternative version)
        $week1AltHtml = $this->getWeek1NewsletterAltHtml();

        NewsletterTemplate::updateOrCreate(
            ['name' => 'week_1_newsletter_alt'],
            [
                'subject' => 'Arabic All The Time - Week 1 Newsletter 🚀',
                'html_content' => $week1AltHtml,
                'variables' => json_encode(['USER_NAME', 'VIDEO_TITLE', 'VIDEO_URL', 'VIDEO_DESCRIPTION']),
                'is_active' => true,
                'description' => 'Alternative weekly newsletter template with enhanced design',
            ]
        );

        $this->command->info('Newsletter templates seeded successfully!');
    }

    private function getWeek1NewsletterHtml()
    {
        // Try to load from downloads folder
        $downloadsPath = 'C:\Users\Nageeb\Downloads\week-1-newsletter.html';
        if (file_exists($downloadsPath)) {
            return file_get_contents($downloadsPath);
        }

        // Fallback template
        return $this->getDefaultNewsletterTemplate();
    }

    private function getWeek1NewsletterAltHtml()
    {
        // Try to load from downloads folder
        $downloadsPath = 'C:\Users\Nageeb\Downloads\week-1-newsletter1.html';
        if (file_exists($downloadsPath)) {
            return file_get_contents($downloadsPath);
        }

        // Fallback template
        return $this->getDefaultNewsletterTemplate();
    }

    private function getDefaultNewsletterTemplate()
    {
        return '<!DOCTYPE html><html><body><h1>Weekly Newsletter</h1><p>Hello [USER_NAME]!</p><p>Check out this week\'s video!</p></body></html>';
    }
}
