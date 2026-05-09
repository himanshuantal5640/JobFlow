@extends('layouts.auth')

@section('content')
<div class="auth-card" style="max-width: 450px; margin: auto; text-align: center;">
    <div class="auth-header">
        <h2 style="font-family: 'Syne', sans-serif; font-weight: 800; font-size: 28px; color: var(--text);">Set New Password</h2>
        <p style="color: var(--text2); margin-top: 10px; line-height: 1.6;">
            Enter the 6-digit code sent to <strong>{{ $email }}</strong> and choose a new secure password.
        </p>
    </div>

    @if(session('error'))
        <div style="background: rgba(255, 77, 106, 0.1); border: 1px solid var(--red); color: var(--red); padding: 10px; border-radius: 10px; margin: 20px 0; font-size: 13px;">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" style="margin-top: 30px;">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">

        <!-- OTP INPUTS -->
        <div style="display: flex; gap: 10px; justify-content: center; margin-bottom: 30px;">
            <input type="text" name="otp[]" maxlength="1" class="otp-input" required autofocus onkeyup="moveFocus(this, 0)">
            <input type="text" name="otp[]" maxlength="1" class="otp-input" required onkeyup="moveFocus(this, 1)">
            <input type="text" name="otp[]" maxlength="1" class="otp-input" required onkeyup="moveFocus(this, 2)">
            <input type="text" name="otp[]" maxlength="1" class="otp-input" required onkeyup="moveFocus(this, 3)">
            <input type="text" name="otp[]" maxlength="1" class="otp-input" required onkeyup="moveFocus(this, 4)">
            <input type="text" name="otp[]" maxlength="1" class="otp-input" required onkeyup="moveFocus(this, 5)">
        </div>

        <div style="display: flex; flex-direction: column; gap: 18px; text-align: left;">
            <div class="field">
                <label style="font-size:12px; color:var(--text2); margin-bottom:6px; display:block;">New Password</label>
                <input type="password" name="password" placeholder="Min. 8 characters" required 
                       style="width: 100%; background: var(--surface); border: 1.5px solid var(--border); border-radius: 10px; padding: 11px 14px; color: var(--text); outline: none;">
            </div>

            <div class="field">
                <label style="font-size:12px; color:var(--text2); margin-bottom:6px; display:block;">Confirm Password</label>
                <input type="password" name="password_confirmation" placeholder="Repeat password" required 
                       style="width: 100%; background: var(--surface); border: 1.5px solid var(--border); border-radius: 10px; padding: 11px 14px; color: var(--text); outline: none;">
            </div>
        </div>

        <button type="submit" class="btn-primary" style="width: 100%; margin-top: 30px; padding: 14px; font-weight: 700; cursor: pointer; border-radius: var(--radius); border: none; background: linear-gradient(135deg, var(--primary), var(--primary2)); color: white; font-family: 'Syne', sans-serif;">
            Reset Password
        </button>
    </form>
</div>

<style>
    .otp-input {
        width: 45px; height: 55px;
        background: var(--surface2);
        border: 1.5px solid var(--border);
        border-radius: 10px;
        text-align: center;
        font-size: 24px;
        font-weight: 700;
        color: var(--text);
        font-family: 'Syne', sans-serif;
        outline: none;
        transition: 0.2s;
    }
    .otp-input:focus {
        border-color: var(--primary);
        background: rgba(124,111,255,0.08);
        box-shadow: 0 0 12px rgba(124,111,255,0.2);
    }
</style>

<script>
    function moveFocus(current, index) {
        if (current.value.length === 1 && index < 5) {
            document.querySelectorAll('.otp-input')[index + 1].focus();
        }
    }
</script>
@endsection
