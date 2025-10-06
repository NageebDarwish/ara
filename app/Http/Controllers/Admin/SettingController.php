<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        return view('admin.modules.setting.index', compact('setting'));
    }

    public function edit()
    {
        $setting = Setting::first();

        // Create a default setting if none exists
        if (!$setting) {
            $setting = Setting::create([
                'site_name' => 'Arabic All The Time',
                'site_description' => 'Learn Arabic Language Online',
                'admin_email' => 'admin@arabicallthetime.com',
                'maintenance_mode' => false,
                'email_notifications' => true,
                'new_user_notification' => true,
                'new_contact_notification' => true,
                'session_timeout' => 120,
                'max_upload_size' => 2048,
            ]);
        }

        return view('admin.modules.setting.edit', compact('setting'));
    }

    public function update(Request $request, $id)
    {
        $setting = Setting::findOrFail($id);

        // Validate the request
        $validated = $request->validate([
            // General Settings
            'site_name' => 'nullable|string|max:255',
            'site_description' => 'nullable|string',
            'admin_email' => 'nullable|email',
            'support_email' => 'nullable|email',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'site_favicon' => 'nullable|image|mimes:ico,png|max:1024',

            // Contact Information
            'contact_phone' => 'nullable|string|max:50',
            'contact_address' => 'nullable|string',

            // Social Media
            'facebook_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'tiktok_url' => 'nullable|url',

            // YouTube API
            'youtube_api_key' => 'nullable|string',
            'youtube_channel_id' => 'nullable|string',

            // Payment
            'stripe_public_key' => 'nullable|string',
            'stripe_secret_key' => 'nullable|string',

            // Email/SMTP
            'smtp_host' => 'nullable|string',
            'smtp_port' => 'nullable|integer',
            'smtp_username' => 'nullable|string',
            'smtp_password' => 'nullable|string',
            'smtp_encryption' => 'nullable|in:tls,ssl',
            'mail_from_address' => 'nullable|email',
            'mail_from_name' => 'nullable|string',

            // SEO
            'meta_keywords' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'google_analytics_id' => 'nullable|string',
            'facebook_pixel_id' => 'nullable|string',

            // System
            'maintenance_mode' => 'nullable|boolean',
            'maintenance_message' => 'nullable|string',
            'session_timeout' => 'nullable|integer|min:1|max:1440',
            'max_upload_size' => 'nullable|integer|min:512|max:10240',

            // Notifications
            'email_notifications' => 'nullable|boolean',
            'new_user_notification' => 'nullable|boolean',
            'new_contact_notification' => 'nullable|boolean',

            // Pusher
            'pusher_app_id' => 'nullable|string',
            'pusher_app_key' => 'nullable|string',
            'pusher_app_secret' => 'nullable|string',
            'pusher_app_cluster' => 'nullable|string',
        ]);

        // Handle file uploads
        if ($request->hasFile('site_logo')) {
            // Delete old logo if exists
            if ($setting->site_logo && file_exists(public_path($setting->site_logo))) {
                unlink(public_path($setting->site_logo));
            }

            $logo = $request->file('site_logo');
            $logoName = 'logo_' . time() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('assets/images'), $logoName);
            $validated['site_logo'] = 'assets/images/' . $logoName;
        }

        if ($request->hasFile('site_favicon')) {
            // Delete old favicon if exists
            if ($setting->site_favicon && file_exists(public_path($setting->site_favicon))) {
                unlink(public_path($setting->site_favicon));
            }

            $favicon = $request->file('site_favicon');
            $faviconName = 'favicon_' . time() . '.' . $favicon->getClientOriginalExtension();
            $favicon->move(public_path('assets/images'), $faviconName);
            $validated['site_favicon'] = 'assets/images/' . $faviconName;
        }

        // Convert checkboxes to boolean
        $validated['maintenance_mode'] = $request->has('maintenance_mode') ? 1 : 0;
        $validated['email_notifications'] = $request->has('email_notifications') ? 1 : 0;
        $validated['new_user_notification'] = $request->has('new_user_notification') ? 1 : 0;
        $validated['new_contact_notification'] = $request->has('new_contact_notification') ? 1 : 0;

        $setting->update($validated);

        return redirect()->route('admin.setting.index')
            ->with('success', 'Settings updated successfully!');
    }
}
