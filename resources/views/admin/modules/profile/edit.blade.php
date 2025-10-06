@extends('admin.layout.layout')

@section('content')
<div class="container-fluid p-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="page-title mb-0">Edit Profile</h2>
                <a href="{{ route('admin.profile.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left"></i> Back to Profile
                </a>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Update Your Information</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <!-- Profile Image Upload -->
                                <div class="mb-4 text-center">
                                    <div class="profile-image-preview mb-3">
                                        @if($user->profile_image)
                                            <img id="imagePreview" src="{{ asset($user->profile_image) }}" alt="Profile Image" class="preview-img">
                                        @else
                                            <img id="imagePreview" src="{{ asset('assets/images/faces/face28.png') }}" alt="Default Profile" class="preview-img">
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <label for="profile_image" class="btn btn-outline-primary">
                                            <i class="mdi mdi-camera"></i> Change Profile Picture
                                        </label>
                                        <input type="file" id="profile_image" name="profile_image" class="d-none" accept="image/*" onchange="previewImage(event)">
                                    </div>
                                    @error('profile_image')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Name Field -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        <i class="mdi mdi-account"></i> Full Name
                                    </label>
                                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email Field -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        <i class="mdi mdi-email"></i> Email Address
                                    </label>
                                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Role (Read-only) -->
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="mdi mdi-shield-account"></i> Role
                                    </label>
                                    <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" disabled>
                                    <small class="text-muted">Role cannot be changed from profile settings</small>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex gap-2 justify-content-end mt-4">
                                    <a href="{{ route('admin.profile.index') }}" class="btn btn-light">
                                        <i class="mdi mdi-close"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="mdi mdi-content-save"></i> Save Changes
                                    </button>
                                </div>
                            </form>
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

    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .card-header {
        border-bottom: 2px solid #f0f0f0;
        padding: 1.5rem;
    }

    .profile-image-preview {
        position: relative;
        display: inline-block;
    }

    .preview-img {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid #f8f9fa;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .form-label {
        font-weight: 500;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .form-label i {
        color: #6c63ff;
        margin-right: 5px;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #6c63ff;
        box-shadow: 0 0 0 0.2rem rgba(108, 99, 255, 0.15);
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

    .btn-outline-primary {
        border: 2px solid #6c63ff;
        color: #6c63ff;
    }

    .btn-outline-primary:hover {
        background: #6c63ff;
        color: white;
    }

    .gap-2 {
        gap: 0.5rem;
    }
</style>

<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection

