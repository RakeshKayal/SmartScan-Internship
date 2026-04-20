@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>Create Sales User</h1>
    <p>The new user will receive Sales role access.</p>
</div>

@if($errors->any())
    <div class="auth-alert error-alert">
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form action="{{ route('users.store') }}" method="POST" class="auth-form">
    @csrf
    <div class="form-group">
        <label>Full Name</label>
        <input type="text" name="name" value="{{ old('name') }}" placeholder="Sales person's name" required>
    </div>
    <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="Their login email" required>
    </div>
    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" placeholder="Set a password" required>
    </div>
    <div class="form-group">
        <label>Confirm Password</label>
        <input type="password" name="password_confirmation" placeholder="Confirm password" required>
    </div>
    <button type="submit" class="auth-btn">Create Sales User</button>
</form>
@endsection