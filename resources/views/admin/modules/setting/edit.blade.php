@extends('admin.layout.layout')

@section('content')
    <div class="container mt-2">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Settings</h3>
                <a href="{{ route('admin.setting.index') }}" class="btn btn-secondary float-right">Back</a>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.setting.update', $setting->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="stripe_public_key">Stripe Public Key</label>
                        <input type="text" name="stripe_public_key" class="form-control" id="stripe_public_key"
                            value="{{ old('stripe_public_key', $setting->stripe_public_key) }}"required>
                    </div>
                      <div class="form-group">
                        <label for="stripe_secret_key">Stripe Secret Key</label>
                        <input type="text" name="stripe_secret_key" class="form-control" id="stripe_secret_key"
                            value="{{ old('stripe_secret_key', $setting->stripe_secret_key) }}"required>
                    </div>
                  
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
