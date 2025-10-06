<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @include('admin.layout.components.style')
    <title>Document</title>
</head>

<body>

    @include('admin.layout.components.navbar')
    <div class="container-fluid page-body-wrapper">
        @include('admin.layout.components.sidebar')
        <div class="main-panel">
            <div class="content-wrapper">
                @yield('content')
            </div>
        </div>
    </div>

    {{-- @include('admin.layout.components.footer') --}}
    @include('admin.layout.components.js')
    @stack('scripts')

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="fa fa-exclamation-triangle me-2"></i>Confirm Delete
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure you want to delete this user? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">
                        <i class="fa fa-trash me-1"></i>Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Toast Notifications -->
    <div id="toast-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;"></div>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('{{ session('success') }}', 'success');
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('{{ session('error') }}', 'error');
            });
        </script>
    @endif

    @if(session('warning'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('{{ session('warning') }}', 'warning');
            });
        </script>
    @endif

    @if(session('info'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast('{{ session('info') }}', 'info');
            });
        </script>
    @endif

    <script>
        // Toast notification function
        function showToast(message, type) {
            const container = document.getElementById('toast-container');

            const colors = {
                success: { bg: '#28a745', icon: 'fa-check-circle' },
                error: { bg: '#dc3545', icon: 'fa-exclamation-circle' },
                warning: { bg: '#ffc107', icon: 'fa-exclamation-triangle' },
                info: { bg: '#17a2b8', icon: 'fa-info-circle' }
            };

            const config = colors[type] || colors.info;

            const toast = document.createElement('div');
            toast.className = 'custom-toast';
            toast.style.cssText = `
                background-color: ${config.bg};
                color: white;
                padding: 15px 20px;
                border-radius: 8px;
                margin-bottom: 10px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                display: flex;
                align-items: center;
                justify-content: space-between;
                animation: slideIn 0.3s ease-out;
                opacity: 1;
                transition: opacity 0.3s ease-out;
            `;

            toast.innerHTML = `
                <div style="display: flex; align-items: center; flex: 1;">
                    <i class="fa ${config.icon}" style="margin-right: 10px; font-size: 18px;"></i>
                    <span>${message}</span>
                </div>
                <button onclick="this.parentElement.remove()" style="background: none; border: none; color: white; font-size: 20px; cursor: pointer; margin-left: 15px; padding: 0; opacity: 0.8;">×</button>
            `;

            container.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // Delete modal functionality
        document.addEventListener('DOMContentLoaded', function() {
            let deleteForm = null;

            // Handle delete button clicks
            document.addEventListener('click', function(e) {
                if (e.target.closest('.delete-btn')) {
                    e.preventDefault();
                    deleteForm = e.target.closest('form');
                    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
                    modal.show();
                }
            });

            // Handle confirm delete
            document.getElementById('confirmDelete').addEventListener('click', function() {
                if (deleteForm) {
                    deleteForm.submit();
                }
            });
        });
    </script>

    <style>
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>

</body>

</html>
