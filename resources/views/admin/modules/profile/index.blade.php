@extends('admin.layout.layout')

@section('content')
<div class="container-fluid p-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="page-title mb-0">My Profile</h2>
                <div>
                    <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary">
                        <i class="mdi mdi-pencil"></i> Edit Profile
                    </a>
                    <a href="{{ route('admin.profile.password') }}" class="btn btn-warning">
                        <i class="mdi mdi-lock"></i> Change Password
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <!-- Profile Card -->
                <div class="col-lg-4 col-md-12 mb-4">
                    <div class="card profile-card">
                        <div class="card-body text-center">
                            <div class="profile-image-wrapper mb-3">
                                @if($user->profile_image)
                                    <img src="{{ asset($user->profile_image) }}" alt="Profile Image" class="profile-image">
                                @else
                                    <img src="{{ asset('assets/images/faces/face28.png') }}" alt="Default Profile" class="profile-image">
                                @endif
                            </div>
                            <h4 class="mb-2">{{ $user->name }}</h4>
                            <p class="text-muted mb-3">{{ ucfirst($user->role) }}</p>
                            <div class="profile-stats">
                                <div class="stat-item">
                                    <span class="stat-value">{{ $user->created_at->format('M d, Y') }}</span>
                                    <span class="stat-label">Member Since</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Details -->
                <div class="col-lg-8 col-md-12 mb-4">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Profile Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="profile-info">
                                <div class="info-row">
                                    <div class="info-label">
                                        <i class="mdi mdi-account"></i> Full Name
                                    </div>
                                    <div class="info-value">{{ $user->name }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">
                                        <i class="mdi mdi-email"></i> Email Address
                                    </div>
                                    <div class="info-value">{{ $user->email }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">
                                        <i class="mdi mdi-shield-account"></i> Role
                                    </div>
                                    <div class="info-value">
                                        <span class="badge badge-{{ $user->role === 'admin' ? 'danger' : 'info' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">
                                        <i class="mdi mdi-calendar"></i> Joined Date
                                    </div>
                                    <div class="info-value">{{ $user->created_at->format('F d, Y') }}</div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label">
                                        <i class="mdi mdi-clock"></i> Last Updated
                                    </div>
                                    <div class="info-value">{{ $user->updated_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .page-title {
        font-weight: 600;
        color: #2c3e50;
    }

    .profile-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .profile-image-wrapper {
        position: relative;
        display: inline-block;
    }

    .profile-image {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid #f8f9fa;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .profile-stats {
        padding: 20px 0;
        border-top: 1px solid #f0f0f0;
        margin-top: 20px;
    }

    .stat-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .stat-value {
        font-size: 1.2rem;
        font-weight: 600;
        color: #2c3e50;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #7f8c8d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .card-header {
        border-bottom: 2px solid #f0f0f0;
        padding: 1.5rem;
    }

    .profile-info {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .info-row:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }

    .info-label {
        font-weight: 500;
        color: #7f8c8d;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-label i {
        font-size: 1.2rem;
        color: #6c63ff;
    }

    .info-value {
        font-weight: 600;
        color: #2c3e50;
    }

    .badge {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        border-radius: 20px;
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

    .alert {
        border-radius: 10px;
        border: none;
    }
</style>
@endsection

