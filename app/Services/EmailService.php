<?php

namespace App\Services;

use App\Models\EmailTemplate;
use App\Models\EmailLog;
use App\Models\User;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailService
{
    /**
     * Send email using template
     */
    public function sendTemplateEmail($templateName, User $user, array $additionalData = [])
    {
        try {
            $template = EmailTemplate::where('name', $templateName)
                ->where('is_active', true)
                ->first();

            if (!$template) {
                Log::warning("Email template not found: {$templateName}");
                return false;
            }

            // Prepare data for template
            $data = array_merge([
                'user_name' => $user->name ?? 'Valued Member',
                'email' => $user->email,
            ], $additionalData);

            // Render template with data
            $htmlContent = $template->render($data);

            // Create email log
            $emailLog = EmailLog::create([
                'user_id' => $user->id,
                'email_template_id' => $template->id,
                'recipient_email' => $user->email,
                'subject' => $template->subject,
                'status' => 'pending',
            ]);

            // Send email
            Mail::to($user->email)->send(new WelcomeEmail($htmlContent, $template->subject));

            // Update log status
            $emailLog->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error("Failed to send email: " . $e->getMessage());

            if (isset($emailLog)) {
                $emailLog->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
            }

            return false;
        }
    }

    /**
     * Send welcome email to new free member
     */
    public function sendWelcomeFreeMember(User $user)
    {
        return $this->sendTemplateEmail('welcome_free', $user);
    }

    /**
     * Send welcome email to new premium member
     */
    public function sendWelcomePremiumMember(User $user)
    {
        return $this->sendTemplateEmail('welcome_premium', $user);
    }
}

