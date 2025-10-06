@extends('admin.layout.layout')

@section('content')
<div class="container-fluid p-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="page-title mb-0">Change Password</h2>
                <a href="{{ route('admin.profile.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left"></i> Back to Profile
                </a>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Update Your Password</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.profile.password.update') }}">
                                @csrf
                                @method('PUT')

                                <!-- Current Password -->
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">
                                        <i class="mdi mdi-lock"></i> Current Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password" id="current_password" name="current_password"
                                               class="form-control @error('current_password') is-invalid @enderror"
                                               placeholder="Enter your current password" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                            <i class="mdi mdi-eye" id="current_password_icon"></i>
                                        </button>
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- New Password -->
                                <div class="mb-3">
                                    <label for="password" class="form-label">
                                        <i class="mdi mdi-lock-plus"></i> New Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password" id="password" name="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               placeholder="Enter new password (min. 8 characters)" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                            <i class="mdi mdi-eye" id="password_icon"></i>
                                        </button>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted">Password must be at least 8 characters long</small>
                                </div>

                                <!-- Confirm Password -->
                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label">
                                        <i class="mdi mdi-lock-check"></i> Confirm New Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                               class="form-control"
                                               placeholder="Re-enter new password" required>
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                            <i class="mdi mdi-eye" id="password_confirmation_icon"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Password Strength Indicator -->
                                <div class="password-strength mb-4">
                                    <div class="strength-bar">
                                        <div class="strength-progress" id="strengthBar"></div>
                                    </div>
                                    <small id="strengthText" class="text-muted"></small>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('admin.profile.index') }}" class="btn btn-light">
                                        <i class="mdi mdi-close"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="mdi mdi-content-save"></i> Update Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Password Tips -->
                    <div class="card mt-4">
                        <div class="card-body">
                            <h6 class="mb-3"><i class="mdi mdi-information"></i> Password Tips</h6>
                            <ul class="password-tips">
                                <li>Use at least 8 characters</li>
                                <li>Include uppercase and lowercase letters</li>
                                <li>Add numbers and special characters</li>
                                <li>Avoid common words and personal information</li>
                                <li>Don't reuse passwords from other sites</li>
                            </ul>
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
        border-radius: 8px 0 0 8px;
        border: 1px solid #dee2e6;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #6c63ff;
        box-shadow: 0 0 0 0.2rem rgba(108, 99, 255, 0.15);
    }

    .input-group .btn {
        border-radius: 0 8px 8px 0;
        border-left: 0;
    }

    .password-strength {
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .strength-bar {
        height: 6px;
        background: #e9ecef;
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }

    .strength-progress {
        height: 100%;
        width: 0;
        transition: all 0.3s ease;
        border-radius: 3px;
    }

    .password-tips {
        margin: 0;
        padding-left: 1.5rem;
        color: #6c757d;
    }

    .password-tips li {
        margin-bottom: 0.5rem;
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

    .gap-2 {
        gap: 0.5rem;
    }
</style>

<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '_icon');

        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('mdi-eye');
            icon.classList.add('mdi-eye-off');
        } else {
            field.type = 'password';
            icon.classList.remove('mdi-eye-off');
            icon.classList.add('mdi-eye');
        }
    }

    // Password strength checker
    document.getElementById('password').addEventListener('input', function(e) {
        const password = e.target.value;
        let strength = 0;

        if (password.length >= 8) strength += 25;
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 25;
        if (password.match(/[0-9]/)) strength += 25;
        if (password.match(/[^a-zA-Z0-9]/)) strength += 25;

        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');

        strengthBar.style.width = strength + '%';

        if (strength <= 25) {
            strengthBar.style.backgroundColor = '#dc3545';
            strengthText.textContent = 'Weak password';
            strengthText.style.color = '#dc3545';
        } else if (strength <= 50) {
            strengthBar.style.backgroundColor = '#ffc107';
            strengthText.textContent = 'Fair password';
            strengthText.style.color = '#ffc107';
        } else if (strength <= 75) {
            strengthBar.style.backgroundColor = '#17a2b8';
            strengthText.textContent = 'Good password';
            strengthText.style.color = '#17a2b8';
        } else {
            strengthBar.style.backgroundColor = '#28a745';
            strengthText.textContent = 'Strong password';
            strengthText.style.color = '#28a745';
        }
    });
</script>
@endsection

