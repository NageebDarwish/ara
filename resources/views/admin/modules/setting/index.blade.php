@extends('admin.layout.layout')

@section('content')
<div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="page-title mb-1">
                        <i class="mdi mdi-cog"></i> System Settings
                    </h2>
                    <p class="text-muted mb-0">Manage your application configuration and preferences</p>
                </div>
                        @if ($setting)
                    <a href="{{ route('admin.setting.edit', $setting->id) }}" class="btn btn-primary">
                        <i class="mdi mdi-pencil"></i> Edit Settings
                                </a>
                        @endif
                        </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
            @endif

                        @if ($setting)
                <!-- Tabbed Settings Content -->
                <div class="card settings-card">
                    <div class="card-body p-0">
                        <!-- Nav Tabs -->
                        <ul class="nav nav-tabs settings-tabs" id="settingsTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button">
                                    <i class="mdi mdi-cog-outline"></i> General
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button">
                                    <i class="mdi mdi-phone"></i> Contact
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="social-tab" data-bs-toggle="tab" data-bs-target="#social" type="button">
                                    <i class="mdi mdi-share-variant"></i> Social Media
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button">
                                    <i class="mdi mdi-credit-card"></i> Payment
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="email-tab" data-bs-toggle="tab" data-bs-target="#email" type="button">
                                    <i class="mdi mdi-email"></i> Email
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="youtube-tab" data-bs-toggle="tab" data-bs-target="#youtube" type="button">
                                    <i class="mdi mdi-youtube"></i> YouTube API
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button">
                                    <i class="mdi mdi-google"></i> SEO & Analytics
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="system-tab" data-bs-toggle="tab" data-bs-target="#system" type="button">
                                    <i class="mdi mdi-server"></i> System
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content p-4" id="settingsTabContent">
                            <!-- General Tab -->
                            <div class="tab-pane fade show active" id="general" role="tabpanel">
                                <h5 class="section-title">General Information</h5>
                                <div class="settings-grid">
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-web"></i> Site Name
                                        </div>
                                        <div class="setting-value">{{ $setting->site_name ?: 'Not set' }}</div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-text"></i> Site Description
                                        </div>
                                        <div class="setting-value">{{ $setting->site_description ?: 'Not set' }}</div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-email"></i> Admin Email
                                        </div>
                                        <div class="setting-value">{{ $setting->admin_email ?: 'Not set' }}</div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-email-outline"></i> Support Email
                                                    </div>
                                        <div class="setting-value">{{ $setting->support_email ?: 'Not set' }}</div>
                                                </div>
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-image"></i> Site Logo
                                                    </div>
                                        <div class="setting-value">
                                            @if($setting->site_logo)
                                                <img src="{{ asset($setting->site_logo) }}" alt="Logo" class="setting-image">
                                            @else
                                                <span class="text-muted">Not uploaded</span>
                                            @endif
                                                </div>
                                            </div>
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-star"></i> Site Favicon
                                        </div>
                                        <div class="setting-value">
                                            @if($setting->site_favicon)
                                                <img src="{{ asset($setting->site_favicon) }}" alt="Favicon" class="setting-favicon">
                                            @else
                                                <span class="text-muted">Not uploaded</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Tab -->
                            <div class="tab-pane fade" id="contact" role="tabpanel">
                                <h5 class="section-title">Contact Information</h5>
                                <div class="settings-grid">
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-phone"></i> Phone Number
                                        </div>
                                        <div class="setting-value">{{ $setting->contact_phone ?: 'Not set' }}</div>
                                    </div>
                                    <div class="setting-item full-width">
                                        <div class="setting-label">
                                            <i class="mdi mdi-map-marker"></i> Address
                                        </div>
                                        <div class="setting-value">{{ $setting->contact_address ?: 'Not set' }}</div>
                                        </div>
                                    </div>
                                </div>

                            <!-- Social Media Tab -->
                            <div class="tab-pane fade" id="social" role="tabpanel">
                                <h5 class="section-title">Social Media Links</h5>
                                <div class="settings-grid">
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-facebook"></i> Facebook
                                        </div>
                                        <div class="setting-value">
                                            @if($setting->facebook_url)
                                                <a href="{{ $setting->facebook_url }}" target="_blank" class="social-link">
                                                    {{ Str::limit($setting->facebook_url, 50) }}
                                                </a>
                                            @else
                                                <span class="text-muted">Not set</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-twitter"></i> Twitter
                                        </div>
                                        <div class="setting-value">
                                            @if($setting->twitter_url)
                                                <a href="{{ $setting->twitter_url }}" target="_blank" class="social-link">
                                                    {{ Str::limit($setting->twitter_url, 50) }}
                                                </a>
                                            @else
                                                <span class="text-muted">Not set</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-instagram"></i> Instagram
                                        </div>
                                        <div class="setting-value">
                                            @if($setting->instagram_url)
                                                <a href="{{ $setting->instagram_url }}" target="_blank" class="social-link">
                                                    {{ Str::limit($setting->instagram_url, 50) }}
                                                </a>
                                            @else
                                                <span class="text-muted">Not set</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-youtube"></i> YouTube
                                        </div>
                                        <div class="setting-value">
                                            @if($setting->youtube_url)
                                                <a href="{{ $setting->youtube_url }}" target="_blank" class="social-link">
                                                    {{ Str::limit($setting->youtube_url, 50) }}
                                                </a>
                                            @else
                                                <span class="text-muted">Not set</span>
                                            @endif
                                                    </div>
                                                </div>
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-linkedin"></i> LinkedIn
                                                    </div>
                                        <div class="setting-value">
                                            @if($setting->linkedin_url)
                                                <a href="{{ $setting->linkedin_url }}" target="_blank" class="social-link">
                                                    {{ Str::limit($setting->linkedin_url, 50) }}
                                                </a>
                                            @else
                                                <span class="text-muted">Not set</span>
                                            @endif
                                                </div>
                                            </div>
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-music-note"></i> TikTok
                                        </div>
                                        <div class="setting-value">
                                            @if($setting->tiktok_url)
                                                <a href="{{ $setting->tiktok_url }}" target="_blank" class="social-link">
                                                    {{ Str::limit($setting->tiktok_url, 50) }}
                                                </a>
                                            @else
                                                <span class="text-muted">Not set</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Tab -->
                            <div class="tab-pane fade" id="payment" role="tabpanel">
                                <h5 class="section-title">Stripe Payment Configuration</h5>
                                <div class="alert alert-info">
                                    <i class="mdi mdi-information"></i> These keys are used for processing payments through Stripe.
                                </div>
                                <div class="settings-grid">
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-key"></i> Public Key
                                        </div>
                                        <div class="setting-value">
                                            <code class="key-display">{{ $setting->stripe_public_key ? Str::limit($setting->stripe_public_key, 40) : 'Not configured' }}</code>
                                        </div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-key-variant"></i> Secret Key
                                        </div>
                                        <div class="setting-value">
                                            <code class="key-display">{{ $setting->stripe_secret_key ? '••••••••••••' . substr($setting->stripe_secret_key, -8) : 'Not configured' }}</code>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Email Tab -->
                            <div class="tab-pane fade" id="email" role="tabpanel">
                                <h5 class="section-title">SMTP Email Configuration</h5>
                                <div class="settings-grid">
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-server"></i> SMTP Host
                                        </div>
                                        <div class="setting-value">{{ $setting->smtp_host ?: 'Not set' }}</div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-ethernet"></i> SMTP Port
                                        </div>
                                        <div class="setting-value">{{ $setting->smtp_port ?: 'Not set' }}</div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-account"></i> Username
                                        </div>
                                        <div class="setting-value">{{ $setting->smtp_username ?: 'Not set' }}</div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-lock"></i> Encryption
                                        </div>
                                        <div class="setting-value">{{ $setting->smtp_encryption ? strtoupper($setting->smtp_encryption) : 'Not set' }}</div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-email-send"></i> From Address
                                        </div>
                                        <div class="setting-value">{{ $setting->mail_from_address ?: 'Not set' }}</div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-tag"></i> From Name
                                        </div>
                                        <div class="setting-value">{{ $setting->mail_from_name ?: 'Not set' }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- YouTube API Tab -->
                            <div class="tab-pane fade" id="youtube" role="tabpanel">
                                <h5 class="section-title">YouTube API Configuration</h5>
                                <div class="alert alert-info">
                                    <i class="mdi mdi-information"></i> Configure YouTube API to fetch videos and series automatically.
                                </div>
                                <div class="settings-grid">
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-key"></i> API Key
                                        </div>
                                        <div class="setting-value">
                                            <code class="key-display">{{ $setting->youtube_api_key ? Str::limit($setting->youtube_api_key, 40) : 'Not configured' }}</code>
                                        </div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-identifier"></i> Channel ID
                                        </div>
                                        <div class="setting-value">{{ $setting->youtube_channel_id ?: 'Not set' }}</div>
                                    </div>
                                </div>
                    </div>

                            <!-- SEO & Analytics Tab -->
                            <div class="tab-pane fade" id="seo" role="tabpanel">
                                <h5 class="section-title">SEO & Analytics</h5>
                                <div class="settings-grid">
                                    <div class="setting-item full-width">
                                        <div class="setting-label">
                                            <i class="mdi mdi-tag-multiple"></i> Meta Keywords
                                        </div>
                                        <div class="setting-value">{{ $setting->meta_keywords ?: 'Not set' }}</div>
                                    </div>
                                    <div class="setting-item full-width">
                                        <div class="setting-label">
                                            <i class="mdi mdi-text"></i> Meta Description
                                        </div>
                                        <div class="setting-value">{{ $setting->meta_description ?: 'Not set' }}</div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-google-analytics"></i> Google Analytics ID
                                        </div>
                                        <div class="setting-value">{{ $setting->google_analytics_id ?: 'Not set' }}</div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-facebook"></i> Facebook Pixel ID
                                        </div>
                                        <div class="setting-value">{{ $setting->facebook_pixel_id ?: 'Not set' }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- System Tab -->
                            <div class="tab-pane fade" id="system" role="tabpanel">
                                <h5 class="section-title">System Configuration</h5>
                                <div class="settings-grid">
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-wrench"></i> Maintenance Mode
                                        </div>
                                        <div class="setting-value">
                                            @if($setting->maintenance_mode)
                                                <span class="badge bg-danger">Enabled</span>
                                            @else
                                                <span class="badge bg-success">Disabled</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-clock"></i> Session Timeout
                                        </div>
                                        <div class="setting-value">{{ $setting->session_timeout }} minutes</div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-file-upload"></i> Max Upload Size
                                        </div>
                                        <div class="setting-value">{{ $setting->max_upload_size }} KB</div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-bell"></i> Email Notifications
                                        </div>
                                        <div class="setting-value">
                                            @if($setting->email_notifications)
                                                <span class="badge bg-success">Enabled</span>
                                            @else
                                                <span class="badge bg-secondary">Disabled</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-account-plus"></i> New User Notification
                                        </div>
                                        <div class="setting-value">
                                            @if($setting->new_user_notification)
                                                <span class="badge bg-success">Enabled</span>
                                            @else
                                                <span class="badge bg-secondary">Disabled</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="setting-item">
                                        <div class="setting-label">
                                            <i class="mdi mdi-message"></i> New Contact Notification
                                        </div>
                                        <div class="setting-value">
                                            @if($setting->new_contact_notification)
                                                <span class="badge bg-success">Enabled</span>
                                            @else
                                                <span class="badge bg-secondary">Disabled</span>
                                            @endif
                                        </div>
                                    </div>
                                    @if($setting->maintenance_mode && $setting->maintenance_message)
                                        <div class="setting-item full-width">
                                            <div class="setting-label">
                                                <i class="mdi mdi-message-text"></i> Maintenance Message
                                            </div>
                                            <div class="setting-value">{{ $setting->maintenance_message }}</div>
                        </div>
                    @endif
                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center p-5">
                        <i class="mdi mdi-alert-circle-outline display-1 text-warning"></i>
                        <h4 class="mt-3">No Settings Configured</h4>
                        <p class="text-muted">Initialize the system settings to get started.</p>
                        <a href="{{ route('admin.setting.edit') }}" class="btn btn-primary mt-3">
                            <i class="mdi mdi-plus-circle"></i> Initialize Settings
                        </a>
                    </div>
                </div>
            @endif
            </div>
        </div>
    </div>

    <style>
    .page-title {
        font-weight: 600;
        color: #2c3e50;
    }

    .settings-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .settings-tabs {
        border-bottom: 2px solid #f0f0f0;
        background: #f8f9fa;
        padding: 0.5rem 1rem;
    }

    .settings-tabs .nav-link {
        color: #6c757d;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px 8px 0 0;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .settings-tabs .nav-link:hover {
        background: rgba(108, 99, 255, 0.1);
        color: #6c63ff;
    }

    .settings-tabs .nav-link.active {
        background: white;
        color: #6c63ff;
        border-bottom: 3px solid #6c63ff;
    }

    .settings-tabs .nav-link i {
        margin-right: 0.5rem;
    }

    .section-title {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .setting-item {
        background: #f8f9fa;
        padding: 1.25rem;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .setting-item:hover {
        background: #e9ecef;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .setting-item.full-width {
        grid-column: 1 / -1;
    }

    .setting-label {
        font-weight: 500;
        color: #6c757d;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .setting-label i {
        color: #6c63ff;
        font-size: 1.1rem;
    }

    .setting-value {
        color: #2c3e50;
        font-weight: 600;
        font-size: 1rem;
        word-break: break-word;
    }

    .setting-image {
        max-width: 150px;
        max-height: 60px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .setting-favicon {
        max-width: 32px;
        max-height: 32px;
    }

    .key-display {
        background: #2c3e50;
        color: #fff;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.85rem;
        display: inline-block;
    }

    .social-link {
        color: #6c63ff;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .social-link:hover {
        color: #5a52d5;
        text-decoration: underline;
    }

    .badge {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        border-radius: 20px;
        font-weight: 500;
    }

    .alert {
        border-radius: 10px;
        border: none;
    }

    .btn {
        border-radius: 8px;
        padding: 0.6rem 1.2rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .settings-grid {
            grid-template-columns: 1fr;
        }

        .settings-tabs .nav-link {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        }
    </style>
@endsection
