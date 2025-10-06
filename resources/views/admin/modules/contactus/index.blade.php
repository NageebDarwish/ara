@extends('admin.layout.layout')

@section('content')
    <div class="py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <h2 class="mb-0">Contact Messages</h2>
            <div class="d-flex gap-4 align-items-center flex-wrap">
                <!-- Filter Buttons -->
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.contactus.index', ['filter' => 'all']) }}"
                        class="btn btn-outline-dark {{ $filter === 'all' ? 'active' : '' }}">
                        All
                    </a>
                    <a href="{{ route('admin.contactus.index', ['filter' => 'unread']) }}"
                        class="btn btn-outline-danger {{ $filter === 'unread' ? 'active' : '' }}">
                        Unread
                    </a>
                    <a href="{{ route('admin.contactus.index', ['filter' => 'read']) }}"
                        class="btn btn-outline-success {{ $filter === 'read' ? 'active' : '' }}">
                        Read
                    </a>
                </div>
                <span class="badge bg-dark rounded-pill text-white" style="margin-left: 20px">{{ count($data) }}
                    messages</span>
            </div>
        </div>

        <div class="row g-4">
            @forelse($data as $message)
                <div class="col-12 mt-2">
                    <div class="card border-0 shadow-sm {{ $message->replied_at ? 'border-success' : '' }}">
                        <div
                            class="card-header bg-dark text-white d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h5 class="mb-0">
                                    {{ $message->name }}
                                    @if ($message->replied_at)
                                        <span class="badge replied-badge ms-2">
                                            <i class="fas fa-check-circle me-1"></i>Replied
                                        </span>
                                    @endif
                                    @if (!$message->read_at)
                                        <span class="badge bg-danger ms-2">
                                            <i class="fas fa-envelope me-1"></i>Unread
                                        </span>
                                    @endif
                                </h5>
                                <small>{{ $message->email }}</small>
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                @if ($message->replied_at)
                                    <small class="replied-time text-light">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $message->replied_at->format('M j, Y g:i A') }}
                                    </small>
                                @endif
                                <button
                                    class="btn btn-sm {{ $message->replied_at ? 'replied-btn' : 'btn-success text-white' }} reply-btn"
                                    data-bs-toggle="modal" data-bs-target="#replyModal"
                                    data-message-id="{{ $message->id }}" data-message-name="{{ $message->name }}"
                                    data-message-email="{{ $message->email }}"
                                    data-message-subject="{{ $message->subject }}">
                                    <i class="fas fa-reply me-1"></i> {{ $message->replied_at ? 'Reply Again' : 'Reply' }}
                                </button>
                                <button class="btn btn-sm btn-light toggle-details" data-message-id="{{ $message->id }}">
                                    Details
                                </button>
                            </div>
                        </div>

                        <div class="card-body">
                            <h6 class="text-dark mb-3">{{ $message->subject }}</h6>
                            <p class="text-muted preview-text mb-0">{{ Str::limit($message->message, 120) }}</p>
                        </div>

                        <!-- Hidden details section -->
                        <div class="message-details {{ $message->replied_at ? 'replied-details' : 'bg-light' }}"
                            id="details-{{ $message->id }}" style="display: none;">
                            <div class="card-body border-top">
                                <p class="text-dark mb-4">{{ $message->message }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted">
                                            Received: {{ $message->created_at->format('M j, Y g:i A') }}
                                        </small>
                                        @if ($message->replied_at)
                                            <br>
                                            <small class="replied-indicator">
                                                <i class="fas fa-check-circle me-1"></i>
                                                Replied: {{ $message->replied_at->format('M j, Y g:i A') }}
                                            </small>
                                        @endif
                                    </div>
                                    <div class="d-flex gap-2">
                                        @if (!$message->read_at)
                                            <button class="btn btn-sm btn-outline-primary mark-read-btn"
                                                data-message-id="{{ $message->id }}">
                                                <i class="fas fa-envelope-open me-1"></i>Mark as Read
                                            </button>
                                        @endif
                                        <button
                                            class="btn btn-sm {{ $message->replied_at ? 'btn-outline-success' : 'btn-success' }} text-{{ $message->replied_at ? 'success' : 'white' }} reply-btn"
                                            data-bs-toggle="modal" data-bs-target="#replyModal"
                                            data-message-id="{{ $message->id }}" data-message-name="{{ $message->name }}"
                                            data-message-email="{{ $message->email }}"
                                            data-message-subject="{{ $message->subject }}">
                                            <i class="fas fa-reply me-1"></i>
                                            {{ $message->replied_at ? 'Reply Again' : 'Reply' }}
                                        </button>
                                        <form action="{{ route('admin.contactus.destroy', $message->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger delete-btn" title="Delete Message">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <h4 class="text-muted mb-3">No messages yet</h4>
                            <p class="text-muted mb-0">When messages arrive, they'll appear here</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Reply Modal -->
    <div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="replyModalLabel">
                        <i class="fas fa-reply me-2"></i>Reply to Message
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="replyForm" method="POST" action="{{ route('admin.contactus.reply') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="messageId" name="message_id">

                        <!-- Message Info -->
                        <div class="alert alert-light border-start border-4 border-dark mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>To:</strong> <span id="recipientName"></span><br>
                                    <strong>Email:</strong> <span id="recipientEmail"></span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Original Subject:</strong> <span id="originalSubject"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Reply Form -->
                        <div class="mb-4">
                            <label for="replySubject" class="form-label fw-semibold">
                                Subject
                            </label>
                            <input type="text" class="form-control form-control-lg border-2" id="replySubject"
                                name="subject" required>
                        </div>

                        <div class="mb-4">
                            <label for="replyMessage" class="form-label fw-semibold">
                                Message
                            </label>
                            <textarea class="form-control border-2" id="replyMessage" name="message" rows="8"
                                placeholder="Type your reply here..." required style="resize: vertical;"></textarea>
                        </div>

                        <!-- Email Options -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check form-switch d-flex gap-2">
                                    <input type="checkbox" id="sendCopy" name="send_copy" value="1" checked>
                                    <label class="form-check-label m-0" for="sendCopy">
                                        Send copy to admin email
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch d-flex gap-2">
                                    <input type="checkbox" id="markAsReplied" name="mark_as_replied" value="1"
                                        checked>
                                    <label class="form-check-label m-0" for="markAsReplied">
                                        Mark message as replied
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-success shadow-sm">
                            <i class="fas fa-paper-plane me-1"></i>Send Reply
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Toggle message details
            $('.toggle-details').click(function() {
                const messageId = $(this).data('message-id');
                const detailsDiv = $('#details-' + messageId);
                const messageCard = $(this).closest('.card');

                // Toggle visibility with animation
                detailsDiv.slideToggle();

                // Update button text
                const currentText = $(this).text().trim();
                $(this).text(currentText === 'Details' ? 'Hide' : 'Details');

                // Mark as read only if unread and not yet loaded
                const isUnread = messageCard.find('.badge.bg-danger').length > 0;
                if (isUnread && !detailsDiv.data('loaded')) {
                    $.ajax({
                        url: '{{ route('admin.contactus.markAsRead', '') }}/' + messageId,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            messageCard.find('.badge.bg-danger').remove();
                            messageCard.find('.mark-read-btn').remove();
                            updateUnreadCount();
                            detailsDiv.data('loaded', true);
                        }
                    });
                } else {
                    detailsDiv.data('loaded', true);
                }
            });

            // Handle reply button click
            $('.reply-btn').click(function() {
                const messageId = $(this).data('message-id');
                const messageName = $(this).data('message-name');
                const messageEmail = $(this).data('message-email');
                const messageSubject = $(this).data('message-subject');

                // Populate modal with message data
                $('#messageId').val(messageId);
                $('#recipientName').text(messageName);
                $('#recipientEmail').text(messageEmail);
                $('#originalSubject').text(messageSubject);
                $('#replySubject').val('Re: ' + messageSubject);

                // Clear previous message
                $('#replyMessage').val('');

                // Focus on message textarea when modal is shown
                $('#replyModal').on('shown.bs.modal', function() {
                    $('#replyMessage').focus();
                });
            });

            // Handle form submission
            $('#replyForm').submit(function(e) {
                e.preventDefault();

                const form = $(this);
                const submitBtn = form.find('button[type="submit"]');
                const originalText = submitBtn.html();

                // Show loading state
                submitBtn.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin me-1"></i>Sending...');

                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        // Show success message
                        if (response.success) {
                            // Close modal
                            $('#replyModal').modal('hide');

                            // Show success notification
                            showNotification('Reply sent successfully!', 'success');

                            // Reset form
                            form[0].reset();

                            // Refresh the page after a short delay
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            showNotification(response.message || 'Failed to send reply',
                                'error');
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Failed to send reply';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        showNotification(errorMessage, 'error');
                    },
                    complete: function() {
                        // Reset button state
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });

            // Notification function
            function showNotification(message, type) {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

                const notification = $(`
            <div class="alert ${alertClass} alert-dismissible fade show position-fixed"
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                <i class="fas ${iconClass} me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);

                $('body').append(notification);

                // Auto remove after 5 seconds
                setTimeout(function() {
                    notification.alert('close');
                }, 5000);
            }
        });
        // Show initial notifications
        @if (session('success'))
            showNotification('{{ session('success') }}', 'success');
        @endif

        @if (session('error'))
            showNotification('{{ session('error') }}', 'error');
        @endif

        // Initialize unread count on page load
        updateUnreadCount();

        // Mark as Read button functionality
        $('.mark-read-btn').on('click', function() {
            const button = $(this);
            const messageId = button.data('message-id');

            $.ajax({
                url: '{{ route('admin.contactus.markAsRead', '') }}/' + messageId,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Remove the button
                        button.remove();

                        // Remove the unread badge from the message card
                        const messageCard = button.closest('.card');
                        messageCard.find('.badge.bg-danger').remove();

                        // Update the unread count in the filter buttons if it exists
                        updateUnreadCount();

                        showNotification('Message marked as read!', 'success');
                    } else {
                        showNotification('Failed to mark message as read', 'error');
                    }
                },
                error: function() {
                    showNotification('Error occurred while marking as read', 'error');
                }
            });
        });

        // Function to update unread count in filter buttons
        function updateUnreadCount() {
            // Count remaining unread messages
            const unreadCount = $('.badge.bg-danger').length;

            // Update the Unread filter button text if it exists
            const unreadButton = $('.btn-outline-primary[href*="filter=unread"]');
            if (unreadButton.length && unreadCount > 0) {
                const originalText = unreadButton.text().replace(/\s*\(\d+\)\s*$/, '');
                unreadButton.text(originalText + ' (' + unreadCount + ')');
            }
        }
    </script>

    <style>
        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        /* Enhanced styling for replied messages */
        .card.border-success {
            border: 2px solid #198754 !important;
            box-shadow: 0 4px 12px rgba(25, 135, 84, 0.15);
        }

        .card.border-success .card-header {
            background: linear-gradient(135deg, #198754 0%, #146c43 100%) !important;
        }

        .card.border-success .card-body {
            background-color: #f8fff9;
        }

        .replied-badge {
            background: linear-gradient(135deg, #20c997 0%, #198754 100%) !important;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            font-weight: 600;
            padding: 0.35rem 0.7rem;
        }

        .replied-time {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 0.25rem 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }

        .replied-btn {
            background: rgba(255, 255, 255, 0.9) !important;
            color: #198754 !important;
            border: 1px solid rgba(25, 135, 84, 0.3) !important;
            font-weight: 500;
        }

        .replied-btn:hover {
            background: #ffffff !important;
            color: #146c43 !important;
            border-color: #198754 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(25, 135, 84, 0.2);
        }

        .message-details.replied-details {
            background: linear-gradient(135deg, #f8fff9 0%, #e8f5e8 100%) !important;
            border-left: 4px solid #198754;
        }

        .replied-indicator {
            color: #198754;
            font-weight: 600;
        }

        .replied-indicator i {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .preview-text {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .message-details {
            border-bottom-left-radius: 0.375rem;
            border-bottom-right-radius: 0.375rem;
        }

        .toggle-details {
            transition: all 0.2s ease;
        }

        .toggle-details:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .gap-2 {
            gap: 0.5rem;
        }
    </style>
@endsection
