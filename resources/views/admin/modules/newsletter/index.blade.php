@extends('admin.layout.layout')

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header ">
                <h3 class="card-title">Send Newsletter</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.newsletter.send') }}" method="POST">
                    @csrf

                    <div class="form-group mb-4">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject"
                            name="subject" placeholder="Enter email subject" required>
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="body" class="form-label">Content</label>
                        <textarea class="form-control @error('body') is-invalid @enderror" id="body" name="body" rows="10"
                            placeholder="Write your newsletter content here..." required></textarea>
                        @error('body')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4 py-2">
                            <i class="fas fa-paper-plane me-2"></i> Send Newsletter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
