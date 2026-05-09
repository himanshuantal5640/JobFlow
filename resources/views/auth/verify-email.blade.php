@extends('layouts.auth')

@section('content')
<div class="max-w-md mx-auto bg-[#0E0E1C] border border-white/5 rounded-3xl p-8 shadow-2xl relative overflow-hidden group">
    <!-- Glow Effect -->
    <div class="absolute -top-24 -left-24 w-48 h-48 bg-[#7C6FFF]/20 blur-[80px] rounded-full group-hover:bg-[#7C6FFF]/30 transition-all duration-500"></div>
    
    <div class="relative z-10 text-center">
        <h2 class="text-3xl font-extrabold text-white tracking-tight mb-2">Verify Account ✦</h2>
        <p class="text-gray-400 text-sm leading-relaxed mb-8">
            We've sent a 6-digit code to <span class="text-[#7C6FFF] font-medium">{{ auth()->user()->email }}</span>. Enter it below to unlock your dashboard.
        </p>

        @if (session('error'))
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl mb-6 text-xs animate-pulse">
                {{ session('error') }}
            </div>
        @endif

        @if (session('status') == 'verification-link-sent')
            <div class="bg-[#00D4AA]/10 border border-[#00D4AA]/20 text-[#00D4AA] px-4 py-3 rounded-xl mb-6 text-xs">
                A fresh code has been delivered to your inbox.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.verify.otp') }}" id="otpForm">
            @csrf
            <div class="flex gap-3 justify-center mb-10">
                @for ($i = 0; $i < 6; $i++)
                    <input type="text" name="otp[]" maxlength="1" 
                           class="otp-box w-12 h-16 bg-[#06060F] border border-white/10 rounded-2xl text-center text-2xl font-bold text-white focus:outline-none focus:border-[#7C6FFF] focus:ring-4 focus:ring-[#7C6FFF]/10 transition-all duration-200"
                           required {{ $i == 0 ? 'autofocus' : '' }} onkeyup="moveFocus(this, {{ $i }})">
                @endfor
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-[#7C6FFF] to-[#9B94FF] text-white font-bold py-4 rounded-2xl shadow-lg shadow-[#7C6FFF]/20 hover:shadow-[#7C6FFF]/40 hover:-translate-y-0.5 transition-all duration-200 active:scale-95 mb-6">
                Complete Verification
            </button>
        </form>

        <div class="flex flex-col items-center gap-4">
            <!-- Countdown Timer -->
            <div class="text-xs text-gray-500 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Code expires in: <span id="timer" class="text-white font-mono font-bold">10:00</span>
            </div>

            <form method="POST" action="{{ route('verification.send') }}" id="resendForm" class="hidden">
                @csrf
                <button type="submit" class="text-sm text-[#7C6FFF] font-semibold hover:text-[#9B94FF] transition-colors">
                    Didn't get the code? Resend
                </button>
            </form>
            
            <p id="resendWait" class="text-xs text-gray-600">Please wait before requesting a new code.</p>
        </div>

        <div class="mt-8 pt-8 border-t border-white/5">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-xs text-gray-500 hover:text-white transition-colors underline">
                    Switch Account / Logout
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // OTP Input Focus Movement
    function moveFocus(current, index) {
        if (current.value.length === 1 && index < 5) {
            document.querySelectorAll('.otp-box')[index + 1].focus();
        }
    }

    // Timer Logic
    let timeLeft = 600; // 10 minutes
    const timerDisplay = document.getElementById('timer');
    const resendForm = document.getElementById('resendForm');
    const resendWait = document.getElementById('resendWait');

    const countdown = setInterval(() => {
        let minutes = Math.floor(timeLeft / 60);
        let seconds = timeLeft % 60;
        
        timerDisplay.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
        
        if (timeLeft <= 0) {
            clearInterval(countdown);
            timerDisplay.textContent = "Expired";
            resendForm.classList.remove('hidden');
            resendWait.classList.add('hidden');
        } else {
            timeLeft--;
        }
    }, 1000);
</script>
@endsection
