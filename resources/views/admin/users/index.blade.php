@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Manage Users</h1>
    <a href="{{ route('users.create') }}" class="auth-btn">+ Add Sales User</a>
</div>

@if(session('success'))
    <div class="auth-alert success-alert">{{ session('success') }}</div>
@endif

<table class="data-table">
    <thead>
        <tr><th>Name</th><th>Email</th><th>Role</th><th>Action</th></tr>
    </thead>
    <tbody>
        @forelse($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ ucfirst($user->role) }}</td>
            <td>
                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                      onsubmit="return confirm('Delete this user?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="4">No users found.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection