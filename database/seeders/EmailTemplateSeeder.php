<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the email template files
        $welcomeFreeHtml = $this->getWelcomeFreeHtml();
        $welcomePremiumHtml = $this->getWelcomePremiumHtml();

        // Create or update Welcome Free Member template
        EmailTemplate::updateOrCreate(
            ['name' => 'welcome_free'],
            [
                'subject' => 'Welcome to Arabic All The Time! 🎉',
                'html_content' => str_replace("[USER'S NAME]", '[USER_NAME]', $welcomeFreeHtml),
                'variables' => json_encode(['USER_NAME', 'EMAIL']),
                'trigger_event' => 'user_registered',
                'is_active' => true,
                'description' => 'Sent automatically when a new user registers (Free membership)',
            ]
        );

        // Create or update Welcome Premium Member template
        EmailTemplate::updateOrCreate(
            ['name' => 'welcome_premium'],
            [
                'subject' => 'Welcome to Premium! 👑',
                'html_content' => str_replace("[USER'S NAME]", '[USER_NAME]', $welcomePremiumHtml),
                'variables' => json_encode(['USER_NAME', 'EMAIL']),
                'trigger_event' => 'user_upgraded_premium',
                'is_active' => true,
                'description' => 'Sent automatically when a user upgrades to Premium membership',
            ]
        );

        $this->command->info('Email templates seeded successfully!');
    }

    private function getWelcomeFreeHtml()
    {
        // Try to load from resources first
        $resourcePath = resource_path('views/emails/welcome-free.html');
        if (file_exists($resourcePath)) {
            return str_replace("[USER'S NAME]", '[USER_NAME]', file_get_contents($resourcePath));
        }

        // Try downloads folder
        $downloadsPath = 'C:\Users\Nageeb\Downloads\welcome-email-free-member.html';
        if (file_exists($downloadsPath)) {
            return str_replace("[USER'S NAME]", '[USER_NAME]', file_get_contents($downloadsPath));
        }

        // Fallback template
        return $this->getDefaultWelcomeFreeTemplate();
    }

    private function getWelcomePremiumHtml()
    {
        // Try to load from resources first
        $resourcePath = resource_path('views/emails/welcome-premium.html');
        if (file_exists($resourcePath)) {
            return str_replace("[USER'S NAME]", '[USER_NAME]', file_get_contents($resourcePath));
        }

        // Try downloads folder
        $downloadsPath = 'C:\Users\Nageeb\Downloads\welcome-email-premium-member.html';
        if (file_exists($downloadsPath)) {
            return str_replace("[USER'S NAME]", '[USER_NAME]', file_get_contents($downloadsPath));
        }

        // Fallback template
        return $this->getDefaultWelcomePremiumTemplate();
    }

    private function getDefaultWelcomeFreeTemplate()
    {
        return '<!DOCTYPE html><html><body><h1>Welcome [USER_NAME]!</h1><p>Thank you for joining Arabic All The Time!</p></body></html>';
    }

    private function getDefaultWelcomePremiumTemplate()
    {
        return '<!DOCTYPE html><html><body><h1>Welcome to Premium [USER_NAME]!</h1><p>Thank you for upgrading!</p></body></html>';
    }
}
