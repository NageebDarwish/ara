@extends('admin.layout.layout')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Contact Messages</h2>
            <span class="badge bg-dark rounded-pill text-white">{{ count($data) }} messages</span>
        </div>

        <div class="row g-4">
            @forelse($data as $message)
            <div class="col-12 mt-2">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">{{ $message->name }}</h5>
                            <small>{{ $message->email }}</small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <a href="mailto:{{ $message->email }}?subject=Re: {{ urlencode($message->subject) }}" 
                               class="btn btn-sm btn-success text-white">
                                <i class="fas fa-reply me-1"></i> Reply
                            </a>
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
                    <div class="message-details bg-light" id="details-{{ $message->id }}" style="display: none;">
                        <div class="card-body border-top">
                            <p class="text-dark mb-4">{{ $message->message }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    Received: {{ $message->created_at->format('M j, Y g:i A') }}
                                </small>
                                <div class="d-flex gap-2">
                                    <a href="mailto:{{ $message->email }}?subject=Re: {{ urlencode($message->subject) }}" 
                                       class="btn btn-sm btn-success text-white">
                                        <i class="fas fa-reply me-1"></i> Reply
                                    </a>
                                    <form action="{{ route('admin.contactus.destroy', $message->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-dark" onclick="return confirm('Delete this message?')">
                                            Delete
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

<script>
$(document).ready(function() {
    // Toggle message details
    $('.toggle-details').click(function() {
        const messageId = $(this).data('message-id');
        const detailsDiv = $('#details-' + messageId);
        
        // Toggle visibility with animation
        detailsDiv.slideToggle();
        
        // Update button text
        const currentText = $(this).text().trim();
        $(this).text(currentText === 'Details' ? 'Hide' : 'Details');
        
        // Mark as read if unread
        if (!detailsDiv.data('loaded') && !$(this).hasClass('read')) {
            $.ajax({
                url: '/admin/contact/' + messageId + '/mark-as-read',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    const badge = $('#details-' + messageId).closest('.card').find('.badge');
                    badge.removeClass('bg-white text-dark').addClass('bg-light text-dark').text('Read');
                    detailsDiv.data('loaded', true);
                }
            });
        }
    });
});
</script>

<style>
    .card {
        transition: transform 0.2s ease;
    }
    .card:hover {
        transform: translateY(-2px);
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
        background-color: rgba(255,255,255,0.2);
    }
    .gap-2 {
        gap: 0.5rem;
    }
</style>
@endsection