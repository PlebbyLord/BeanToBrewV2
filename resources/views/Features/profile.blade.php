@extends('layouts.app')
@section('content')

<div class="container mt-4">
    <div class="card mx-auto" style="max-width: 850px;">
        <div class="card-header text-center" style="font-size: 30px;">Profile</div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @elseif(session('info'))
                <div class="alert alert-info">
                    {{ session('info') }}
                </div>
            @endif
            <form action="{{ route('update.profile') }}" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                @csrf

                <div class="row">
                    {{-- Left Column: Picture and Choose Picture --}}
                    <div class="col-md-4">
                        <img id="previewImage" src="{{ optional(auth()->user()->profile)->profile_picture ? asset('storage/' . optional(auth()->user()->profile)->profile_picture) : asset('storage/users/default-avatar.jpg') }}" alt="Profile Picture" class="img-fluid" style="max-width: 250px; max-height: 250px; border: 1px solid black;">
                        <input type="file" name="image1" class="form-control" accept=".jpg, .jpeg, .png" onchange="previewImage(this)">
                        <small id="fileHelp1" class="form-text text-muted">Accepted formats: .jpg, .jpeg, .png. Maximum size: 10MB</small>
                        <div id="fileError1" class="text-danger"></div>
                    </div>

                    {{-- Right Column: Name, Address, Number --}}
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" class="form-control" value="{{ auth()->user()->first_name }}">
                        </div>
                    
                        <div class="mb-3">
                            <label for="last_name">Last Name</label>
                            <input type="text" name="last_name" class="form-control" value="{{ auth()->user()->last_name }}">
                        </div>
                    
                        <div class="mb-3">
                            <label for="mobile_number">Mobile Number</label>
                            <input type="number" name="mobile_number" class="form-control" value="{{ auth()->user()->mobile_number }}">
                        </div>
                    
                        <div class="mb-3">
                            <label for="address">Address</label>
                            <input type="text" name="address" class="form-control" value="{{ auth()->user()->address }}">
                        </div>                     
                    </div>                                       
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary float-end">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
    
@endsection
