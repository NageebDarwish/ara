@extends('admin.layout.layout')

@section('content')
    <div class="mt-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fa fa-newspaper"></i> Edit Newsletter Template: {{ $template->name }}</h3>
                <a href="{{ route('admin.newsletter-templates.index') }}" class="btn btn-secondary float-right">Back</a>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.newsletter-templates.update', $template->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="subject">Newsletter Subject</label>
                                <input type="text" name="subject" id="subject" class="form-control"
                                       value="{{ old('subject', $template->subject) }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status</label>
                                <div class="custom-control custom-switch" style="padding-top: 8px;">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                           {{ $template->is_active ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">
                                        <span class="badge badge-success" id="status-badge">
                                            {{ $template->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description (Optional)</label>
                        <textarea name="description" id="description" class="form-control" rows="2">{{ old('description', $template->description) }}</textarea>
                    </div>

                    <!-- Available Variables -->
                    <div class="alert alert-info">
                        <strong><i class="fa fa-info-circle"></i> Available Variables:</strong><br>
                        <code>[USER_NAME]</code> - User's name |
                        <code>[VIDEO_TITLE]</code> - Featured video title |
                        <code>[VIDEO_URL]</code> - Video URL |
                        <code>[VIDEO_DESCRIPTION]</code> - Video description<br>
                        <small class="text-muted">Use these variables in your newsletter template.</small>
                    </div>

                    <!-- HTML Editor -->
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label for="html_content">Newsletter HTML Template</label>
                            <div>
                                <button type="button" class="btn btn-sm btn-info" onclick="previewTemplate()">
                                    <i class="fa fa-eye"></i> Quick Preview
                                </button>
                            </div>
                        </div>

                        <textarea class="summernote" name="html_content" id="html_content" rows="15">{{ old('html_content', $template->html_content) }}</textarea>

                        <small class="text-muted">
                            <strong>Tip:</strong> Full HTML newsletter template. Use variables for dynamic content.
                        </small>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fa fa-save"></i> Update Template
                        </button>
                        <a href="{{ route('admin.newsletter-templates.preview', $template->id) }}"
                           class="btn btn-info btn-lg" target="_blank">
                            <i class="fa fa-external-link-alt"></i> Full Preview in New Tab
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Summernote Editor -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>

    <script>
        (function() {
            // Init summernote
            $('.summernote').summernote({
                placeholder: 'Edit your newsletter template here...',
                tabsize: 2,
                height: 500,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });

            // Update status badge on toggle
            document.getElementById('is_active').addEventListener('change', function() {
                const badge = document.getElementById('status-badge');
                if (this.checked) {
                    badge.textContent = 'Active';
                    badge.className = 'badge badge-success';
                } else {
                    badge.textContent = 'Inactive';
                    badge.className = 'badge badge-secondary';
                }
            });
        })();

        function previewTemplate() {
            const html = $('.summernote').summernote('code');
            const previewWindow = window.open('', 'Newsletter Preview', 'width=800,height=600,scrollbars=yes');
            previewWindow.document.write(html);
            previewWindow.document.close();
        }
    </script>
@endsection

