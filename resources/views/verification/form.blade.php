@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verification Form') }}</div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('verification.verify') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="verification_code" class="form-label">{{ __('Verification Code') }}</label>
                            <input id="verification_code" type="text" pattern="[0-9]*" inputmode="numeric" class="form-control @error('verification_code') is-invalid @enderror" name="verification_code" required autocomplete="off" maxlength="6">

                            @error('verification_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">{{ __('Verify') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript to restrict input to numeric characters and limit length to 6
    document.getElementById('verification_code').addEventListener('input', function (e) {
        this.value = this.value.replace(/[^0-9]/g, ''); // Remove non-numeric characters
        if (this.value.length > 6) {
            this.value = this.value.slice(0, 6); // Limit to 6 characters
        }
    });
</script>

@endsection
