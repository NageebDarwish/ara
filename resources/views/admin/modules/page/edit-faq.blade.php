@extends('admin.layout.layout')

@section('content')
    <div class="mt-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fa fa-question-circle"></i> Edit FAQ Page</h3>
                <a href="{{ route('admin.page.index') }}" class="btn btn-secondary float-right">Back</a>
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

                <form action="{{ route('admin.page.update', $page->id) }}" method="POST" id="faqForm">
                    @csrf
                    @method('PUT')

                    <!-- Page Basic Info -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" class="form-control" id="name"
                                    value="{{ old('name', $page->name) }}" required readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="slug">Slug</label>
                                <input type="text" name="slug" class="form-control" id="slug"
                                    value="{{ old('slug', $page->slug) }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="title">Page Title</label>
                        <input type="text" name="title" class="form-control" id="title"
                            value="{{ old('title', $page->title) }}">
                    </div>

                    <div class="form-group">
                        <label for="description">Page Description</label>
                        <textarea name="description" class="form-control" id="description" rows="2">{{ old('description', $page->description) }}</textarea>
                    </div>

                    <hr class="my-4">

                    <!-- FAQ Sections -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4><i class="fa fa-list"></i> FAQ Sections</h4>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-secondary mr-2" onclick="collapseAllSections()">
                                <i class="fa fa-minus"></i> Collapse All
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary mr-2" onclick="expandAllSections()">
                                <i class="fa fa-plus"></i> Expand All
                            </button>
                            <button type="button" class="btn btn-success" onclick="addSection()">
                                <i class="fa fa-plus"></i> Add Section
                            </button>
                        </div>
                    </div>

                    <div id="faqSections">
                        @if($page->faqSections && $page->faqSections->count() > 0)
                            @foreach($page->faqSections as $sectionIndex => $section)
                                <div class="section-container card mb-3" data-section-index="{{ $sectionIndex }}">
                                    <div class="card-header bg-primary text-white" style="cursor: pointer;" onclick="toggleSection(this)">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">
                                                <i class="fa fa-chevron-down section-toggle-icon"></i>
                                                <i class="fa fa-folder"></i> Section {{ $sectionIndex + 1 }}: {{ $section->title }}
                                            </h5>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="removeSection(this); event.stopPropagation();">
                                                <i class="fa fa-trash"></i> Remove Section
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body section-body">
                                        <div class="form-group">
                                            <label>Section Title</label>
                                            <input type="text" name="faq_sections[{{ $sectionIndex }}][title]"
                                                   class="form-control" value="{{ $section->title }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Section Description (Optional)</label>
                                            <textarea name="faq_sections[{{ $sectionIndex }}][description]"
                                                      class="form-control" rows="2">{{ $section->description }}</textarea>
                                        </div>

                                        <!-- FAQ Items -->
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6><i class="fa fa-comments"></i> Questions & Answers</h6>
                                            <button type="button" class="btn btn-sm btn-info" onclick="addItem(this)">
                                                <i class="fa fa-plus"></i> Add Q&A
                                            </button>
                                        </div>

                                        <div class="items-container">
                                            @if($section->items && $section->items->count() > 0)
                                                @foreach($section->items as $itemIndex => $item)
                                                    <div class="item-container card mb-2 border-info">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <strong class="text-info">Q&A {{ $itemIndex + 1 }}</strong>
                                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem(this)">
                                                                    <i class="fa fa-times"></i> Remove
                                                                </button>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Question</label>
                                                                <input type="text" name="faq_sections[{{ $sectionIndex }}][items][{{ $itemIndex }}][question]"
                                                                       class="form-control" value="{{ $item->question }}" required>
                                                            </div>
                                            <div class="form-group mb-0">
                                                <label>Answer</label>
                                                <textarea name="faq_sections[{{ $sectionIndex }}][items][{{ $itemIndex }}][answer]"
                                                          class="form-control" rows="5" required>{{ $item->answer }}</textarea>
                                                <small class="text-muted">
                                                    <strong>Tip:</strong> You can use line breaks for paragraphs. For lists, use "-" at the start of each line.
                                                </small>
                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fa fa-save"></i> Update FAQ Page
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let sectionCounter = {{ $page->faqSections->count() ?? 0 }};

        function addSection() {
            const container = document.getElementById('faqSections');
            const sectionHtml = `
                <div class="section-container card mb-3" data-section-index="${sectionCounter}">
                    <div class="card-header bg-primary text-white" style="cursor: pointer;" onclick="toggleSection(this)">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fa fa-chevron-down section-toggle-icon"></i>
                                <i class="fa fa-folder"></i> Section ${sectionCounter + 1}
                            </h5>
                            <button type="button" class="btn btn-sm btn-danger" onclick="removeSection(this); event.stopPropagation();">
                                <i class="fa fa-trash"></i> Remove Section
                            </button>
                        </div>
                    </div>
                    <div class="card-body section-body">
                        <div class="form-group">
                            <label>Section Title</label>
                            <input type="text" name="faq_sections[${sectionCounter}][title]"
                                   class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Section Description (Optional)</label>
                            <textarea name="faq_sections[${sectionCounter}][description]"
                                      class="form-control" rows="2"></textarea>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6><i class="fa fa-comments"></i> Questions & Answers</h6>
                            <button type="button" class="btn btn-sm btn-info" onclick="addItem(this)">
                                <i class="fa fa-plus"></i> Add Q&A
                            </button>
                        </div>

                        <div class="items-container">
                            <!-- Items will be added here -->
                        </div>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', sectionHtml);
            sectionCounter++;
        }

        function removeSection(button) {
            if (confirm('Are you sure you want to remove this section and all its Q&As?')) {
                button.closest('.section-container').remove();
            }
        }

        function addItem(button) {
            const section = button.closest('.section-container');
            const sectionIndex = section.dataset.sectionIndex;
            const itemsContainer = section.querySelector('.items-container');
            const itemCount = itemsContainer.querySelectorAll('.item-container').length;

            const itemHtml = `
                <div class="item-container card mb-2 border-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong class="text-info">Q&A ${itemCount + 1}</strong>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem(this)">
                                <i class="fa fa-times"></i> Remove
                            </button>
                        </div>
                        <div class="form-group">
                            <label>Question</label>
                            <input type="text" name="faq_sections[${sectionIndex}][items][${itemCount}][question]"
                                   class="form-control" required>
                        </div>
                        <div class="form-group mb-0">
                            <label>Answer</label>
                            <textarea name="faq_sections[${sectionIndex}][items][${itemCount}][answer]"
                                      class="form-control" rows="5" required></textarea>
                            <small class="text-muted">
                                <strong>Tip:</strong> You can use line breaks for paragraphs. For lists, use "-" at the start of each line.
                            </small>
                        </div>
                    </div>
                </div>
            `;

            itemsContainer.insertAdjacentHTML('beforeend', itemHtml);
        }

        function removeItem(button) {
            if (confirm('Are you sure you want to remove this Q&A?')) {
                button.closest('.item-container').remove();
            }
        }

        function toggleSection(header) {
            const body = header.nextElementSibling;
            const icon = header.querySelector('.section-toggle-icon');

            if (body.style.display === 'none') {
                body.style.display = 'block';
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-chevron-down');
            } else {
                body.style.display = 'none';
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-right');
            }
        }

        function collapseAllSections() {
            document.querySelectorAll('.section-body').forEach(body => {
                body.style.display = 'none';
            });
            document.querySelectorAll('.section-toggle-icon').forEach(icon => {
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-right');
            });
        }

        function expandAllSections() {
            document.querySelectorAll('.section-body').forEach(body => {
                body.style.display = 'block';
            });
            document.querySelectorAll('.section-toggle-icon').forEach(icon => {
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-chevron-down');
            });
        }

        // If no sections exist, add one by default
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelectorAll('.section-container').length === 0) {
                addSection();
            }
        });
    </script>

    <style>
        .section-container {
            border-left: 4px solid #007bff;
        }
        .item-container {
            background-color: #f8f9fa;
        }
        .items-container {
            min-height: 50px;
        }
        .section-toggle-icon {
            transition: transform 0.3s ease;
            margin-right: 8px;
        }
        .card-header:hover {
            background-color: #0056b3 !important;
        }
    </style>
@endsection

