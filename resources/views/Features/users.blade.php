@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h1>Users</h1>
                @if(auth()->user()->role == 2)
                <div class="d-flex justify-content-end mb-3"> <!-- Use flex utilities to align content to the end -->           
                    <!-- Button to go to features.admins route -->
                    <a href="{{ route('features.admins') }}" class="btn btn-success">Admins</a>
                </div>

                @endif
            </div>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Profile</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>
                            @php
                                $profile = App\Models\Profile::where('user_id', $user->id)->first();
                            @endphp
                            @if($profile && $profile->profile_picture)
                                <img src="{{ asset('storage/' . $profile->profile_picture) }}" alt="Profile Picture" width="40" height="40" style="border-radius: 50%;">
                            @else
                                <!-- You can use a default picture here if no profile picture is available -->
                                <img src="{{ asset('storage/users/default-avatar.jpg') }}" alt="Default Picture" width="40" height="40" style="border-radius: 50%;">
                            @endif
                        </td>
                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role == 0)
                            Customer
                            @elseif($user->role == 1)
                            Admin
                            @elseif($user->role == 2)
                            Super Admin
                            @else
                            Unknown
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
