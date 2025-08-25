@extends('layouts.app')

@section('content')
<div class="container mt-5">

    <h1 class="mb-4">Profile Settings</h1>


    <h2>Welcome, {{ auth()->user()->name }}</h2>

<br>
    <!-- Update Password -->
    <div class="card mb-4">
        <div class="card-header">Update Password</div>
        <div class="card-body">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="current_password" class="form-label">Current Password</label>
                    <input id="current_password" name="current_password" type="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input id="password" name="password" type="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-warning">Update Password</button>
            </form>
        </div>
    </div>

    <!-- Delete Account -->
    <div class="card mb-4">
        <div class="card-header text-danger">Delete Account</div>
        <div class="card-body">
            <p class="text-muted">Once your account is deleted, all of its resources and data will be permanently deleted.</p>

            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
                    Delete Account
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
