@extends('admin.layout.layout')

@section('content')
    <div class="mt-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fa fa-envelope"></i> Edit Email Template: {{ $template->name }}</h3>
                <a href="{{ route('admin.email-templates.index') }}" class="btn btn-secondary float-right">Back</a>
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

                <!-- Stats Row -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fa fa-info-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Template Name</span>
                                <span class="info-box-number">{{ $template->name }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fa fa-check"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Emails Sent</span>
                                <span class="info-box-number">{{ $sentCount }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="fa fa-times"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Failed</span>
                                <span class="info-box-number">{{ $failedCount }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fa fa-bolt"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Trigger Event</span>
                                <span class="info-box-number" style="font-size: 12px;">{{ $template->trigger_event ?? 'Manual' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.email-templates.update', $template->id) }}" method="POST" id="templateForm">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="subject">Email Subject</label>
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
                        <code>[EMAIL]</code> - User's email address<br>
                        <small class="text-muted">Use these variables in your email template. They will be replaced with actual data when sending.</small>
                    </div>

                    <!-- HTML Editor -->
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label for="html_content">Email HTML Template</label>
                            <div>
                                <button type="button" class="btn btn-sm btn-info" onclick="previewTemplate()">
                                    <i class="fa fa-eye"></i> Quick Preview
                                </button>
                            </div>
                        </div>

                        <textarea class="summernote" name="html_content" id="html_content">{{ old('html_content', $template->html_content) }}</textarea>

                        <small class="text-muted">
                            <strong>Tip:</strong> Use the visual editor or switch to code view. Test with Preview before saving.
                        </small>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fa fa-save"></i> Update Template
                        </button>
                        <a href="{{ route('admin.email-templates.preview', $template->id) }}"
                           class="btn btn-info btn-lg" target="_blank">
                            <i class="fa fa-external-link-alt"></i> Full Preview in New Tab
                        </a>
                        @if($sentCount > 0)
                            <a href="{{ route('admin.email-templates.logs', $template->id) }}"
                               class="btn btn-secondary btn-lg">
                                <i class="fa fa-list"></i> View Email Logs ({{ $sentCount }})
                            </a>
                        @endif
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
                placeholder: 'Edit your email template here...',
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
            const previewWindow = window.open('', 'Email Preview', 'width=800,height=600,scrollbars=yes');
            previewWindow.document.write(html);
            previewWindow.document.close();
        }
    </script>

    <style>
        .info-box {
            min-height: 80px;
            background: #fff;
            border-radius: 4px;
            margin-bottom: 15px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12);
        }
        .info-box-icon {
            border-radius: 4px 0 0 4px;
            display: block;
            float: left;
            height: 80px;
            width: 80px;
            text-align: center;
            font-size: 40px;
            line-height: 80px;
            color: #fff;
        }
        .info-box-content {
            padding: 10px;
            margin-left: 90px;
        }
        .info-box-text {
            display: block;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .info-box-number {
            display: block;
            font-weight: bold;
            font-size: 18px;
        }
        #editorContainer {
            background: white;
        }
    </style>
@endsection

