<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        // Payment Settings
        'stripe_secret_key',
        'stripe_public_key',

        // General Settings
        'site_name',
        'site_description',
        'site_logo',
        'site_favicon',
        'admin_email',
        'support_email',

        // Contact Information
        'contact_phone',
        'contact_address',

        // Social Media Links
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'youtube_url',
        'linkedin_url',
        'tiktok_url',

        // YouTube API Configuration
        'youtube_api_key',
        'youtube_channel_id',

        // Email/SMTP Configuration
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'mail_from_address',
        'mail_from_name',

        // SEO Settings
        'meta_keywords',
        'meta_description',
        'google_analytics_id',
        'facebook_pixel_id',

        // System Settings
        'maintenance_mode',
        'maintenance_message',
        'session_timeout',
        'max_upload_size',

        // Notification Settings
        'email_notifications',
        'new_user_notification',
        'new_contact_notification',

        // Pusher Configuration
        'pusher_app_id',
        'pusher_app_key',
        'pusher_app_secret',
        'pusher_app_cluster',
    ];

    protected $casts = [
        'maintenance_mode' => 'boolean',
        'email_notifications' => 'boolean',
        'new_user_notification' => 'boolean',
        'new_contact_notification' => 'boolean',
        'smtp_port' => 'integer',
        'session_timeout' => 'integer',
        'max_upload_size' => 'integer',
    ];
}
