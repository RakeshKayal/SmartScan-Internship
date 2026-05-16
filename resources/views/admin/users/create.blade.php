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
        <div style="display: flex; gap: 8px;">
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Their login email" required style="flex: 1;">
            <button type="button" class="auth-btn" id="sendOtpBtn" onclick="sendOtp()">Send OTP</button>
        </div>
        <small id="otpStatus" style="color: green; display: none;">OTP sent successfully.</small>
    </div>

    <div class="form-group">
        <label>OTP</label>
        <div style="display: flex; gap: 8px;">
            <input type="text" name="otp" id="otpInput" placeholder="Enter 6-digit OTP" maxlength="6" value="{{ old('otp') }}" style="flex: 1;">
            <button type="button" class="auth-btn" id="verifyOtpBtn" onclick="verifyOtp()" disabled>Verify OTP</button>
        </div>
        @error('otp')
            <small style="color: red;">{{ $message }}</small>
        @enderror
        <small id="otpTimer" style="color: orange; display: none;"></small>
        <small id="otpVerifiedMsg" style="color: green; display: none;">✅ Email verified! You can now set the password.</small>
    </div>

    {{-- Password section locked until OTP verified --}}
    <div id="passwordSection" style="opacity: 0.4; pointer-events: none;">
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" id="passwordInput" placeholder="Set a password" disabled>
        </div>

        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" id="passwordConfirmInput" placeholder="Confirm password" disabled>
        </div>

        <button type="submit" class="auth-btn" id="submitBtn" disabled>Create Sales User</button>
    </div>

    {{-- Hidden flag sent with form so server can double-check --}}
    <input type="hidden" name="otp_verified" id="otpVerifiedFlag" value="0">
</form>

<script>
let timerInterval = null;
let otpVerified   = false;

function sendOtp() {
    const email = document.querySelector('input[name="email"]').value;
    if (!email) { alert('Please enter an email address first.'); return; }

    const btn    = document.getElementById('sendOtpBtn');
    const status = document.getElementById('otpStatus');
    const timer  = document.getElementById('otpTimer');
    const vBtn   = document.getElementById('verifyOtpBtn');

    btn.disabled    = true;
    btn.textContent = 'Sending...';

    // Reset verified state on resend
    lockPasswordSection();

    fetch("{{ route('users.sendOtp') }}", {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ email })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            status.style.display = 'block';
            timer.style.display  = 'block';
            vBtn.disabled        = false;

            let seconds = 40;
            clearInterval(timerInterval);
            timerInterval = setInterval(() => {
                timer.textContent = `OTP expires in ${seconds}s`;
                timer.style.color = 'orange';
                seconds--;
                if (seconds < 0) {
                    clearInterval(timerInterval);
                    timer.textContent = 'OTP expired. Request a new one.';
                    timer.style.color = 'red';
                    vBtn.disabled     = true;
                    btn.disabled      = false;
                    btn.textContent   = 'Resend OTP';
                    lockPasswordSection();
                }
            }, 1000);

            btn.textContent = 'Resend OTP';
            setTimeout(() => { btn.disabled = false; }, 40000);
        } else {
            alert(data.message || 'Failed to send OTP.');
            btn.disabled    = false;
            btn.textContent = 'Send OTP';
        }
    })
    .catch(() => {
        alert('Something went wrong.');
        btn.disabled    = false;
        btn.textContent = 'Send OTP';
    });
}

function verifyOtp() {
    const email = document.querySelector('input[name="email"]').value;
    const otp   = document.getElementById('otpInput').value;

    if (otp.length !== 6) { alert('Please enter the 6-digit OTP.'); return; }

    fetch("{{ route('users.verifyOtp') }}", {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ email, otp })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            clearInterval(timerInterval);
            document.getElementById('otpTimer').style.display       = 'none';
            document.getElementById('otpVerifiedMsg').style.display = 'block';
            document.getElementById('verifyOtpBtn').disabled        = true;
            document.getElementById('otpInput').disabled            = true;
            unlockPasswordSection();
        } else {
            alert(data.message || 'Invalid OTP.');
        }
    })
    .catch(() => alert('Something went wrong.'));
}

function unlockPasswordSection() {
    otpVerified = true;
    document.getElementById('otpVerifiedFlag').value         = '1';
    const section = document.getElementById('passwordSection');
    section.style.opacity                                    = '1';
    section.style.pointerEvents                              = 'auto';
    document.getElementById('passwordInput').disabled        = false;
    document.getElementById('passwordConfirmInput').disabled = false;
    document.getElementById('submitBtn').disabled            = false;
}

function lockPasswordSection() {
    otpVerified = false;
    document.getElementById('otpVerifiedFlag').value         = '0';
    document.getElementById('otpVerifiedMsg').style.display  = 'none';
    const section = document.getElementById('passwordSection');
    section.style.opacity                                    = '0.4';
    section.style.pointerEvents                              = 'none';
    document.getElementById('passwordInput').disabled        = true;
    document.getElementById('passwordConfirmInput').disabled = true;
    document.getElementById('submitBtn').disabled            = true;
}
</script>
@endsection