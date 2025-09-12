@extends('admin.layout.layout')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Edit Blog Post</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.blog.update', $blog->id) }}" method="POST" enctype="multipart/form-data"
                    id="blogForm">
                    @csrf
                    @method('PUT')
                        <div class="col-md-12">
                        <div class="form-group">
                            <label for="blog_category_id">Select Category</label>
                            <select class="form-control @error('blog_category_id') is-invalid @enderror"
                                name="blog_category_id" id="blog_category_id">
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @if ((old('blog_category_id') ?? ($blog->blog_category_id ?? '')) == $category->id) selected @endif>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('blog_category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                   <div class="col-md-12">
                        <div class="form-group">
                            <label for="author">Author Name</label>
                            <input type="text" class="form-control @error('author') is-invalid @enderror" id="author"
                                name="author" value="{{ old('author', $blog->author) }}">
                            @error('author')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="cover_image">Cover Image</label>
                                <input type="file" class="form-control-file @error('cover_image') is-invalid @enderror"
                                    id="cover_image" name="cover_image">
                                <div class="mt-2">
                                    <img id="imagePreview" src="{{ asset($blog->cover_image) }}" alt="Current Cover"
                                        class="img-thumbnail" style="max-height: 200px;">
                                </div>
                                <small class="text-muted">Leave blank to keep current image</small>
                                @error('cover_image')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="title">Title *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title', $blog->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Short Description *</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3" required>{{ old('description', $blog->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="meta_title">Meta Title</label>
                                <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                                    id="meta_title" name="meta_title" value="{{ old('meta_title', $blog->meta_title) }}">
                                @error('meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="meta_description">Meta Description</label>
                                <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description"
                                    name="meta_description" rows="3">{{ old('meta_description', $blog->meta_description) }}</textarea>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="slug">Slug</label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                    id="slug" name="slug" value="{{ old('slug', $blog->slug) }}">
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="content">Content *</label>
                                <textarea class="@error('content') is-invalid @enderror summernote" id="content" name="content" rows="10">{{ old('content', $blog->content) }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">Update Post</button>
                                <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

   
   <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>
    <script>
         $('.summernote').summernote({
            placeholder: 'Type your content here...',
            tabsize: 2,
            height: null,
            // toolbar: [
            //     ['style', ['style']],
            //     ['font', ['bold', 'underline', 'clear']],
            //     ['color', ['color']],
            //     ['para', ['ul', 'ol', 'paragraph']],
            //     ['table', ['table']],
            //     ['insert', ['link', 'picture', 'video']],
            //     ['view', ['fullscreen', 'codeview', 'help']]
            // ]
        });
    </script>
@endsection
