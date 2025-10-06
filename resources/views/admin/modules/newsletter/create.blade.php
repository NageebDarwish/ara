@extends('admin.layout.layout')

@section('content')
    <div class="mt-5">
        <div class="card shadow">
            <div class="card-header">
                <h3 class="card-title">Create Newsletter</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.newsletter.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="timezone" id="user_timezone" value="UTC">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="subject">Subject *</label>
                                <input type="text" class="form-control @error('subject') is-invalid @enderror"
                                    id="subject" name="subject" value="{{ old('subject') }}" required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="recipient_type">Send To *</label>
                                <select class="form-control @error('recipient_type') is-invalid @enderror" id="recipient_type" name="recipient_type" required>
                                    <option value="all" {{ old('recipient_type', 'all') == 'all' ? 'selected' : '' }}>All Users</option>
                                    <option value="premium" {{ old('recipient_type') == 'premium' ? 'selected' : '' }}>Premium Users Only</option>
                                    <option value="free" {{ old('recipient_type') == 'free' ? 'selected' : '' }}>Free Users Only</option>
                                    <option value="selected" {{ old('recipient_type') == 'selected' ? 'selected' : '' }}>Selected Users</option>
                                </select>
                                @error('recipient_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12" id="selected_users_wrapper">
                            <div class="form-group">
                                <label for="selected_users">Select Specific Users (Type to search)</label>
                                <select class="form-control select2-ajax @error('selected_users') is-invalid @enderror"
                                    id="selected_users" name="selected_users[]" multiple="multiple">
                                </select>
                                <small class="form-text text-muted">Type a name or email to search users. Loads dynamically for better performance.</small>
                                @error('selected_users')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Action *</label>
                                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Save as Draft</option>
                                    <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Schedule Send</option>
                                    <option value="send_now" {{ old('status', 'send_now') == 'send_now' ? 'selected' : '' }}>Send Now</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="scheduled_at">Send Date & Time (Your Local Time)</label>
                                <input type="datetime-local" class="form-control @error('scheduled_at') is-invalid @enderror"
                                    id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at') }}">
                                <small class="form-text text-muted">Required for scheduled send</small>
                                @error('scheduled_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="body">Content *</label>
                                <textarea class="form-control @error('body') is-invalid @enderror summernote"
                                    id="body" name="body" rows="10" required>{{ old('body') }}</textarea>
                                @error('body')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-paper-plane me-1"></i> Create Newsletter
                                </button>
                                <a href="{{ route('admin.newsletter.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>

    <script>
        $(document).ready(function() {
            console.log('Page loaded'); // Debug

            // Auto-detect user's timezone
            const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            document.getElementById('user_timezone').value = userTimezone;
            console.log('Timezone set:', userTimezone); // Debug

            // Initialize Select2 with AJAX for multi-select (handles 20,000+ users)
            try {
                $('#selected_users').select2({
                    ajax: {
                        url: '{{ route('admin.newsletter.searchUsers') }}',
                        dataType: 'json',
                        delay: 250, // Debounce search
                        data: function (params) {
                            return {
                                q: params.term || '', // search term (empty shows first 20)
                                page: params.page || 1
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.results,
                                pagination: {
                                    more: data.pagination.more
                                }
                            };
                        },
                        cache: true
                    },
                    placeholder: 'Click to see users or type to search...',
                    allowClear: true,
                    width: '100%',
                    minimumInputLength: 0, // Show results immediately when clicked
                    language: {
                        searching: function () {
                            return 'Searching...';
                        },
                        noResults: function () {
                            return 'No users found';
                        },
                        loadingMore: function () {
                            return 'Loading more...';
                        }
                    }
                });
                console.log('Select2 with AJAX initialized'); // Debug
            } catch(e) {
                console.error('Select2 init error:', e); // Debug
            }

            // Show/hide selected users based on recipient type
            function toggleSelectedUsers() {
                const selectedType = $('#recipient_type').val();
                console.log('Toggle called, type:', selectedType); // Debug

                if (selectedType === 'selected') {
                    console.log('Showing users wrapper'); // Debug
                    document.getElementById('selected_users_wrapper').style.display = 'block';
                    $('#selected_users').prop('required', true);
                } else {
                    console.log('Hiding users wrapper'); // Debug
                    document.getElementById('selected_users_wrapper').style.display = 'none';
                    $('#selected_users').prop('required', false);
                }
            }

            // Bind change event
            $('#recipient_type').on('change', function() {
                console.log('Recipient type changed to:', $(this).val()); // Debug
                toggleSelectedUsers();
            });

            // Check initial state immediately
            toggleSelectedUsers();

            // Init summernote
            $('.summernote').summernote({
                placeholder: 'Type your newsletter content here...',
                tabsize: 2,
                height: 300,
            });
        });
    </script>
    @endpush
@endsection

