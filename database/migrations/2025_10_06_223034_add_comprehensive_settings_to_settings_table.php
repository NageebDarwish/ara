<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // General Settings
            $table->string('site_name')->nullable()->after('id');
            $table->text('site_description')->nullable();
            $table->string('site_logo')->nullable();
            $table->string('site_favicon')->nullable();
            $table->string('admin_email')->nullable();
            $table->string('support_email')->nullable();

            // Contact Information
            $table->string('contact_phone')->nullable();
            $table->text('contact_address')->nullable();

            // Social Media Links
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('tiktok_url')->nullable();

            // YouTube API Configuration
            $table->text('youtube_api_key')->nullable();
            $table->string('youtube_channel_id')->nullable();

            // Email/SMTP Configuration
            $table->string('smtp_host')->nullable();
            $table->integer('smtp_port')->nullable();
            $table->string('smtp_username')->nullable();
            $table->string('smtp_password')->nullable();
            $table->string('smtp_encryption')->nullable();
            $table->string('mail_from_address')->nullable();
            $table->string('mail_from_name')->nullable();

            // SEO Settings
            $table->string('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('google_analytics_id')->nullable();
            $table->text('facebook_pixel_id')->nullable();

            // System Settings
            $table->boolean('maintenance_mode')->default(false);
            $table->text('maintenance_message')->nullable();
            $table->integer('session_timeout')->default(120); // minutes
            $table->integer('max_upload_size')->default(2048); // KB

            // Notification Settings
            $table->boolean('email_notifications')->default(true);
            $table->boolean('new_user_notification')->default(true);
            $table->boolean('new_contact_notification')->default(true);

            // Pusher Configuration (for real-time features)
            $table->string('pusher_app_id')->nullable();
            $table->string('pusher_app_key')->nullable();
            $table->string('pusher_app_secret')->nullable();
            $table->string('pusher_app_cluster')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'site_name', 'site_description', 'site_logo', 'site_favicon',
                'admin_email', 'support_email', 'contact_phone', 'contact_address',
                'facebook_url', 'twitter_url', 'instagram_url', 'youtube_url',
                'linkedin_url', 'tiktok_url', 'youtube_api_key', 'youtube_channel_id',
                'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password',
                'smtp_encryption', 'mail_from_address', 'mail_from_name',
                'meta_keywords', 'meta_description', 'google_analytics_id',
                'facebook_pixel_id', 'maintenance_mode', 'maintenance_message',
                'session_timeout', 'max_upload_size', 'email_notifications',
                'new_user_notification', 'new_contact_notification',
                'pusher_app_id', 'pusher_app_key', 'pusher_app_secret', 'pusher_app_cluster'
            ]);
        });
    }
};
