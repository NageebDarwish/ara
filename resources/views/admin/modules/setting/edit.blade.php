@extends('admin.layout.layout')

@section('content')
<div class="container-fluid p-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="page-title mb-1">
                        <i class="mdi mdi-pencil"></i> Edit Settings
                    </h2>
                    <p class="text-muted mb-0">Configure your application settings</p>
                </div>
                <a href="{{ route('admin.setting.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left"></i> Back
                </a>
            </div>

                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <h5><i class="mdi mdi-alert"></i> Validation Errors</h5>
                    <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

            <form action="{{ route('admin.setting.update', $setting->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                <!-- General Settings -->
                <div class="card settings-section mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="mdi mdi-cog-outline"></i> General Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-web"></i> Site Name
                                </label>
                                <input type="text" name="site_name" class="form-control"
                                       value="{{ old('site_name', $setting->site_name) }}"
                                       placeholder="Arabic All The Time">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-email"></i> Admin Email
                                </label>
                                <input type="email" name="admin_email" class="form-control"
                                       value="{{ old('admin_email', $setting->admin_email) }}"
                                       placeholder="admin@example.com">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-email-outline"></i> Support Email
                                </label>
                                <input type="email" name="support_email" class="form-control"
                                       value="{{ old('support_email', $setting->support_email) }}"
                                       placeholder="support@example.com">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-text"></i> Site Description
                                </label>
                                <textarea name="site_description" class="form-control" rows="3"
                                          placeholder="A brief description of your website">{{ old('site_description', $setting->site_description) }}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-image"></i> Site Logo
                                </label>
                                <input type="file" name="site_logo" class="form-control" accept="image/*" onchange="previewImage(this, 'logo-preview')">
                                @if($setting->site_logo)
                                    <div class="mt-2">
                                        <img id="logo-preview" src="{{ asset($setting->site_logo) }}" alt="Logo" class="preview-image">
                                    </div>
                                @else
                                    <div class="mt-2">
                                        <img id="logo-preview" src="" alt="" class="preview-image" style="display: none;">
                                    </div>
                                @endif
                                <small class="text-muted">Recommended: PNG, JPG, SVG (Max: 2MB)</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-star"></i> Site Favicon
                                </label>
                                <input type="file" name="site_favicon" class="form-control" accept="image/x-icon,image/png" onchange="previewImage(this, 'favicon-preview')">
                                @if($setting->site_favicon)
                                    <div class="mt-2">
                                        <img id="favicon-preview" src="{{ asset($setting->site_favicon) }}" alt="Favicon" class="favicon-preview">
                                    </div>
                                @else
                                    <div class="mt-2">
                                        <img id="favicon-preview" src="" alt="" class="favicon-preview" style="display: none;">
                                    </div>
                                @endif
                                <small class="text-muted">Recommended: ICO, PNG (32x32 or 16x16)</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="card settings-section mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="mdi mdi-phone"></i> Contact Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-phone"></i> Phone Number
                                </label>
                                <input type="text" name="contact_phone" class="form-control"
                                       value="{{ old('contact_phone', $setting->contact_phone) }}"
                                       placeholder="+1 234 567 8900">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-map-marker"></i> Address
                                </label>
                                <textarea name="contact_address" class="form-control" rows="2"
                                          placeholder="Your office address">{{ old('contact_address', $setting->contact_address) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Media Links -->
                <div class="card settings-section mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="mdi mdi-share-variant"></i> Social Media Links</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-facebook"></i> Facebook URL
                                </label>
                                <input type="url" name="facebook_url" class="form-control"
                                       value="{{ old('facebook_url', $setting->facebook_url) }}"
                                       placeholder="https://facebook.com/yourpage">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-twitter"></i> Twitter URL
                                </label>
                                <input type="url" name="twitter_url" class="form-control"
                                       value="{{ old('twitter_url', $setting->twitter_url) }}"
                                       placeholder="https://twitter.com/yourhandle">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-instagram"></i> Instagram URL
                                </label>
                                <input type="url" name="instagram_url" class="form-control"
                                       value="{{ old('instagram_url', $setting->instagram_url) }}"
                                       placeholder="https://instagram.com/yourprofile">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-youtube"></i> YouTube URL
                                </label>
                                <input type="url" name="youtube_url" class="form-control"
                                       value="{{ old('youtube_url', $setting->youtube_url) }}"
                                       placeholder="https://youtube.com/c/yourchannel">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-linkedin"></i> LinkedIn URL
                                </label>
                                <input type="url" name="linkedin_url" class="form-control"
                                       value="{{ old('linkedin_url', $setting->linkedin_url) }}"
                                       placeholder="https://linkedin.com/company/yourcompany">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-music-note"></i> TikTok URL
                                </label>
                                <input type="url" name="tiktok_url" class="form-control"
                                       value="{{ old('tiktok_url', $setting->tiktok_url) }}"
                                       placeholder="https://tiktok.com/@youraccount">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Settings -->
                <div class="card settings-section mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="mdi mdi-credit-card"></i> Stripe Payment Configuration</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="mdi mdi-information"></i> Get your Stripe API keys from <a href="https://dashboard.stripe.com/apikeys" target="_blank">Stripe Dashboard</a>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-key"></i> Stripe Public Key
                                </label>
                                <input type="text" name="stripe_public_key" class="form-control"
                                       value="{{ old('stripe_public_key', $setting->stripe_public_key) }}"
                                       placeholder="pk_test_...">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-key-variant"></i> Stripe Secret Key
                                </label>
                                <input type="text" name="stripe_secret_key" class="form-control"
                                       value="{{ old('stripe_secret_key', $setting->stripe_secret_key) }}"
                                       placeholder="sk_test_...">
                                <small class="text-danger">Keep this secure and never share it publicly</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- YouTube API Configuration -->
                <div class="card settings-section mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="mdi mdi-youtube"></i> YouTube API Configuration</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="mdi mdi-information"></i> Configure YouTube Data API v3 to fetch videos automatically
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-key"></i> YouTube API Key
                                </label>
                                <input type="text" name="youtube_api_key" class="form-control"
                                       value="{{ old('youtube_api_key', $setting->youtube_api_key) }}"
                                       placeholder="AIzaSy...">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-identifier"></i> YouTube Channel ID
                                </label>
                                <input type="text" name="youtube_channel_id" class="form-control"
                                       value="{{ old('youtube_channel_id', $setting->youtube_channel_id) }}"
                                       placeholder="UC...">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Email/SMTP Configuration -->
                <div class="card settings-section mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="mdi mdi-email"></i> Email/SMTP Configuration</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-server"></i> SMTP Host
                                </label>
                                <input type="text" name="smtp_host" class="form-control"
                                       value="{{ old('smtp_host', $setting->smtp_host) }}"
                                       placeholder="smtp.gmail.com">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-ethernet"></i> SMTP Port
                                </label>
                                <input type="number" name="smtp_port" class="form-control"
                                       value="{{ old('smtp_port', $setting->smtp_port) }}"
                                       placeholder="587">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-account"></i> SMTP Username
                                </label>
                                <input type="text" name="smtp_username" class="form-control"
                                       value="{{ old('smtp_username', $setting->smtp_username) }}"
                                       placeholder="your-email@gmail.com">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-lock"></i> SMTP Password
                                </label>
                                <input type="password" name="smtp_password" class="form-control"
                                       value="{{ old('smtp_password', $setting->smtp_password) }}"
                                       placeholder="Your app password">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-security"></i> SMTP Encryption
                                </label>
                                <select name="smtp_encryption" class="form-control">
                                    <option value="">None</option>
                                    <option value="tls" {{ old('smtp_encryption', $setting->smtp_encryption) == 'tls' ? 'selected' : '' }}>TLS</option>
                                    <option value="ssl" {{ old('smtp_encryption', $setting->smtp_encryption) == 'ssl' ? 'selected' : '' }}>SSL</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-email-send"></i> Mail From Address
                                </label>
                                <input type="email" name="mail_from_address" class="form-control"
                                       value="{{ old('mail_from_address', $setting->mail_from_address) }}"
                                       placeholder="noreply@yoursite.com">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-tag"></i> Mail From Name
                                </label>
                                <input type="text" name="mail_from_name" class="form-control"
                                       value="{{ old('mail_from_name', $setting->mail_from_name) }}"
                                       placeholder="Arabic All The Time">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO & Analytics -->
                <div class="card settings-section mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="mdi mdi-google"></i> SEO & Analytics</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-tag-multiple"></i> Meta Keywords
                                </label>
                                <input type="text" name="meta_keywords" class="form-control"
                                       value="{{ old('meta_keywords', $setting->meta_keywords) }}"
                                       placeholder="arabic, learning, language, online courses">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-text"></i> Meta Description
                                </label>
                                <textarea name="meta_description" class="form-control" rows="2"
                                          placeholder="A brief description for search engines">{{ old('meta_description', $setting->meta_description) }}</textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-google-analytics"></i> Google Analytics ID
                                </label>
                                <input type="text" name="google_analytics_id" class="form-control"
                                       value="{{ old('google_analytics_id', $setting->google_analytics_id) }}"
                                       placeholder="G-XXXXXXXXXX or UA-XXXXXXXXX-X">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-facebook"></i> Facebook Pixel ID
                                </label>
                                <input type="text" name="facebook_pixel_id" class="form-control"
                                       value="{{ old('facebook_pixel_id', $setting->facebook_pixel_id) }}"
                                       placeholder="123456789012345">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Settings -->
                <div class="card settings-section mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="mdi mdi-server"></i> System Configuration</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-clock"></i> Session Timeout (minutes)
                                </label>
                                <input type="number" name="session_timeout" class="form-control"
                                       value="{{ old('session_timeout', $setting->session_timeout) }}"
                                       placeholder="120" min="1" max="1440">
                                <small class="text-muted">How long users stay logged in</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="mdi mdi-file-upload"></i> Max Upload Size (KB)
                                </label>
                                <input type="number" name="max_upload_size" class="form-control"
                                       value="{{ old('max_upload_size', $setting->max_upload_size) }}"
                                       placeholder="2048" min="512" max="10240">
                                <small class="text-muted">Maximum file size for uploads</small>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="maintenance_mode" id="maintenance_mode"
                                           {{ old('maintenance_mode', $setting->maintenance_mode) ? 'checked' : '' }}
                                           onchange="toggleMaintenanceMessage()">
                                    <label class="form-check-label" for="maintenance_mode">
                                        <i class="mdi mdi-wrench"></i> Enable Maintenance Mode
                                    </label>
                                </div>
                                <small class="text-muted">When enabled, only admins can access the site</small>
                            </div>
                            <div class="col-md-12 mb-3" id="maintenance_message_field" style="{{ old('maintenance_mode', $setting->maintenance_mode) ? '' : 'display: none;' }}">
                                <label class="form-label">
                                    <i class="mdi mdi-message-text"></i> Maintenance Message
                                </label>
                                <textarea name="maintenance_message" class="form-control" rows="2"
                                          placeholder="We're currently performing maintenance. Please check back soon.">{{ old('maintenance_message', $setting->maintenance_message) }}</textarea>
                            </div>
                            <div class="col-md-12">
                                <h6 class="mb-3">Notification Settings</h6>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="email_notifications" id="email_notifications"
                                           {{ old('email_notifications', $setting->email_notifications) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="email_notifications">
                                        <i class="mdi mdi-bell"></i> Email Notifications
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="new_user_notification" id="new_user_notification"
                                           {{ old('new_user_notification', $setting->new_user_notification) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="new_user_notification">
                                        <i class="mdi mdi-account-plus"></i> New User Alerts
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="new_contact_notification" id="new_contact_notification"
                                           {{ old('new_contact_notification', $setting->new_contact_notification) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="new_contact_notification">
                                        <i class="mdi mdi-message"></i> Contact Form Alerts
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pusher Configuration (Optional) -->
                <div class="card settings-section mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="mdi mdi-broadcast"></i> Pusher Configuration (Optional)</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="mdi mdi-information"></i> Configure Pusher for real-time notifications and features
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">App ID</label>
                                <input type="text" name="pusher_app_id" class="form-control"
                                       value="{{ old('pusher_app_id', $setting->pusher_app_id) }}"
                                       placeholder="123456">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">App Key</label>
                                <input type="text" name="pusher_app_key" class="form-control"
                                       value="{{ old('pusher_app_key', $setting->pusher_app_key) }}"
                                       placeholder="xxxxxxxxxxxxxxxxxxxx">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">App Secret</label>
                                <input type="password" name="pusher_app_secret" class="form-control"
                                       value="{{ old('pusher_app_secret', $setting->pusher_app_secret) }}"
                                       placeholder="xxxxxxxxxxxxxxxxxxxx">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">App Cluster</label>
                                <input type="text" name="pusher_app_cluster" class="form-control"
                                       value="{{ old('pusher_app_cluster', $setting->pusher_app_cluster) }}"
                                       placeholder="us2">
                            </div>
                        </div>
                    </div>
                    </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-2 justify-content-end mb-4">
                    <a href="{{ route('admin.setting.index') }}" class="btn btn-light btn-lg">
                        <i class="mdi mdi-close"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="mdi mdi-content-save"></i> Save Settings
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>

<style>
    .page-title {
        font-weight: 600;
        color: #2c3e50;
    }

    .settings-section {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .settings-section .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.25rem;
        border-bottom: none;
    }

    .settings-section .card-header h5 {
        margin: 0;
        font-weight: 600;
    }

    .settings-section .card-header i {
        margin-right: 0.5rem;
    }

    .form-label {
        font-weight: 500;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .form-label i {
        color: #6c63ff;
        margin-right: 0.25rem;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #6c63ff;
        box-shadow: 0 0 0 0.2rem rgba(108, 99, 255, 0.15);
    }

    .form-check-input {
        width: 3rem;
        height: 1.5rem;
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: #6c63ff;
        border-color: #6c63ff;
    }

    .form-check-label {
        margin-left: 0.5rem;
        cursor: pointer;
    }

    .preview-image {
        max-width: 200px;
        max-height: 80px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .favicon-preview {
        max-width: 32px;
        max-height: 32px;
    }

    .btn {
        border-radius: 8px;
        padding: 0.6rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-lg {
        padding: 0.75rem 2rem;
        font-size: 1.1rem;
    }

    .gap-2 {
        gap: 0.5rem;
    }

    .alert {
        border-radius: 10px;
        border: none;
    }

    textarea.form-control {
        resize: vertical;
    }
</style>

<script>
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function toggleMaintenanceMessage() {
        const checkbox = document.getElementById('maintenance_mode');
        const messageField = document.getElementById('maintenance_message_field');
        messageField.style.display = checkbox.checked ? 'block' : 'none';
    }
</script>
@endsection
