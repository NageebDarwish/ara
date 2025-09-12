@extends('admin.layout.layout')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card card-glass mb-4">
                    <div class="card-header p-4 position-relative">
                        @if ($setting)
                            <div class=" end-0 mb-2 me-4">
                                <a href="{{ route('admin.setting.edit', $setting->id) }}" class="btn btn-dark">
                                    <i class="fas fa-pen me-1"></i>Edit Settings
                                </a>
                            </div>
                        @endif
                        <div class="bg-gradient-primary shadow-primary border-radius-lg p-3 row">
                            <h3 class="text-white mb-0">
                                <i class="fas fa-sliders-h me-2"></i>System Configuration
                            </h3>
                        </div>

                    </div>

                    <div class="card-body p-4  ">
                        @if ($setting)
                            <div class="row g-4">
                                <!-- Payment Gateway Section -->
                                <div class="col-md-12 mb-2">
                                    <div class="card card-plain border">
                                        <div class="card-header bg-transparent">
                                            <h5 class="text-gradient text-primary mb-0">
                                                <i class="fas fa-credit-card me-2"></i>Payment Gateway
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="list-group list-group-flush">
                                                <div
                                                    class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                                    <div>
                                                        <h6 class="mb-0">Stripe Public Key</h6>
                                                        <p class="text-xs text-secondary mb-0">Visible in client-side code
                                                        </p>
                                                    </div>
                                                    <span class="badge bg-light text-dark font-monospace"
                                                        style="word-break: break-word; white-space: normal;">
                                                        {{ $setting->stripe_public_key ?? 'Not configured' }}
                                                    </span>
                                                </div>
                                                <div
                                                    class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                                    <div>
                                                        <h6 class="mb-0">Stripe Secret Key</h6>
                                                        <p class="text-xs text-danger mb-0">Keep this secure</p>
                                                    </div>
                                                    <span class="badge bg-light text-dark font-monospace"
                                                        style="word-break: break-word; white-space: normal;">
                                                        {{ $setting->stripe_secret_key ?? 'Not configured' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- System Information -->
                                <div class="col-md-12">
                                    <div class="card card-plain border">
                                        <div class="card-header bg-transparent">
                                            <h5 class="text-gradient text-primary mb-0">
                                                <i class="fas fa-server me-2"></i>System Information
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="list-group list-group-flush">
                                                <div
                                                    class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                                    <div>
                                                        <h6 class="mb-0">Configuration Version</h6>
                                                        <p class="text-xs text-secondary mb-0">Current settings version</p>
                                                    </div>
                                                    <span class="badge bg-dark rounded-pill">
                                                        v1.0
                                                    </span>
                                                </div>
                                                <div
                                                    class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                                    <div>
                                                        <h6 class="mb-0">Last Updated</h6>
                                                        <p class="text-xs text-secondary mb-0">When settings were modified
                                                        </p>
                                                    </div>
                                                    <span class="text-dark">
                                                        {{ $setting->updated_at->format('M j, Y \a\t g:i A') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>No settings configured!</strong> Please initialize the system settings.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>

                            <div class="text-center mt-4">
                                <a href="{{ route('admin.setting.create') }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-plus-circle me-2"></i>Initialize Settings
                                </a>
                            </div>
                        @endif
                    </div>

                    @if (session('success'))
                        <div class="card-footer bg-transparent border-top-0">
                            <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .card-glass {
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            background-color: rgba(255, 255, 255, 0.85);
            border-radius: 12px;
            border: 1px solid rgba(209, 213, 219, 0.3);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
        }



        .text-gradient {
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            position: relative;
            z-index: 1;
        }

        .text-primary {
            background-image: linear-gradient(310deg, rgb(180, 180, 180) 0%, rgb(2, 2, 2) 100%);
        }

        .bg-gradient-primary {
            background-image: linear-gradient(310deg, rgb(165, 165, 165) 0%, rgb(0, 0, 0) 100%);
        }

        .font-monospace {
            font-family: 'SFMono-Regular', Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: 0.85em;
            word-break: break-all;
        }

        .border-radius-lg {
            border-radius: 0.75rem;
        }

        .shadow-primary {
            box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.14), 0 7px 10px -5px rgba(156, 39, 176, 0.4);
        }
    </style>
@endsection
