@extends('layouts.auth')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6 relative">
    <!-- Glow Blobs -->
    <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-primary/10 blur-[120px] rounded-full -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-accent/5 blur-[120px] rounded-full translate-x-1/2 translate-y-1/2"></div>

    <div class="w-full max-w-[1100px] grid grid-cols-1 lg:grid-cols-2 bg-surface border border-white/5 rounded-[32px] overflow-hidden shadow-2xl relative z-10">
        <!-- Left Side: Visual/Branding -->
        <div class="hidden lg:flex flex-col justify-between p-12 bg-gradient-to-br from-primary/10 to-transparent border-r border-white/5 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-full h-full opacity-10 pointer-events-none" style="background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.1) 1px, transparent 0); background-size: 24px 24px;"></div>
            
            <a href="/" class="flex items-center gap-3 no-underline group">
                <div class="w-10 h-10 bg-gradient-to-br from-primary to-accent rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-primary/20 group-hover:scale-110 transition-transform">J</div>
                <span class="text-2xl font-black tracking-tight text-white font-syne">Job<span class="text-primary">Flow</span></span>
            </a>

            <div>
                <h1 class="text-5xl font-black leading-[1.1] tracking-tight mb-6 font-syne">Elevate your <br/><span class="text-primary">Career Journey</span></h1>
                <p class="text-gray-400 text-lg leading-relaxed max-w-sm">Join the next generation of professionals finding their dream roles through AI-powered matching.</p>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex -space-x-3">
                    @for($i=1; $i<=4; $i++)
                        <div class="w-10 h-10 rounded-full border-2 border-surface bg-gray-800 flex items-center justify-center text-[10px] font-bold">U{{$i}}</div>
                    @endfor
                </div>
                <p class="text-xs text-gray-500 font-medium">Join 2,000+ others today</p>
            </div>
        </div>

        <!-- Right Side: Forms -->
        <div class="p-8 lg:p-16 flex flex-col justify-center">
            <!-- Tab Switcher -->
            <div class="flex bg-bg/50 p-1 rounded-2xl mb-10 w-fit">
                <button onclick="switchTab('login')" id="loginTab" class="px-8 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 bg-primary text-white shadow-lg shadow-primary/20">Login</button>
                <button onclick="switchTab('register')" id="registerTab" class="px-8 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 text-gray-500 hover:text-white">Register</button>
            </div>

            <!-- LOGIN FORM -->
            <div id="loginForm" class="block animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="mb-8">
                    <h2 class="text-3xl font-black text-white font-syne mb-2">Welcome Back ✦</h2>
                    <p class="text-gray-500 text-sm">Enter your credentials to access your account</p>
                </div>

                @if(session('success'))
                    <div class="bg-accent/10 border border-accent/20 text-accent px-4 py-3 rounded-xl mb-6 text-xs">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl mb-6 text-xs">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-400 uppercase tracking-wider ml-1">Email Address</label>
                        <div class="relative group">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-primary transition-colors">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            </span>
                            <input type="email" name="email" placeholder="name@company.com" required 
                                   class="w-full bg-bg border border-white/5 rounded-2xl py-4 pl-12 pr-4 text-white focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-center px-1">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider">Password</label>
                            <a href="{{ route('password.request') }}" class="text-xs text-primary font-bold hover:underline">Forgot?</a>
                        </div>
                        <div class="relative group">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-primary transition-colors">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            </span>
                            <input type="password" name="password" placeholder="••••••••" required 
                                   class="w-full bg-bg border border-white/5 rounded-2xl py-4 pl-12 pr-4 text-white focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-primary to-[#9B94FF] text-white font-bold py-4 rounded-2xl shadow-xl shadow-primary/20 hover:shadow-primary/40 hover:-translate-y-0.5 transition-all active:scale-95">
                        Sign In to JobFlow
                    </button>
                </form>
            </div>

            <!-- REGISTER FORM -->
            <div id="registerForm" class="hidden animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="mb-8">
                    <h2 class="text-3xl font-black text-white font-syne mb-2">Create Account ✦</h2>
                    <p class="text-gray-500 text-sm">Start your journey with us today</p>
                </div>

                <form method="POST" action="{{ route('register.post') }}" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <button type="button" onclick="setRole('seeker')" id="roleSeeker" class="p-4 rounded-2xl border-2 border-primary bg-primary/10 text-white transition-all text-center group">
                            <div class="text-2xl mb-1 group-hover:scale-110 transition-transform">🚀</div>
                            <div class="text-xs font-bold uppercase tracking-widest">Seeker</div>
                        </button>
                        <button type="button" onclick="setRole('recruiter')" id="roleRecruiter" class="p-4 rounded-2xl border-2 border-white/5 bg-bg text-gray-500 hover:text-white transition-all text-center group">
                            <div class="text-2xl mb-1 group-hover:scale-110 transition-transform">🏢</div>
                            <div class="text-xs font-bold uppercase tracking-widest">Recruiter</div>
                        </button>
                        <input type="hidden" name="role" id="roleInput" value="seeker">
                    </div>

                    <div class="space-y-4">
                        <div class="relative group">
                             <input type="text" name="name" placeholder="Full Name" required 
                                   class="w-full bg-bg border border-white/5 rounded-2xl py-4 px-6 text-white focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                        </div>
                        <div class="relative group">
                             <input type="email" name="email" placeholder="Email Address" required 
                                   class="w-full bg-bg border border-white/5 rounded-2xl py-4 px-6 text-white focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                        </div>
                        <div class="relative group">
                             <input type="password" name="password" placeholder="Create Password" required 
                                   class="w-full bg-bg border border-white/5 rounded-2xl py-4 px-6 text-white focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-primary to-[#9B94FF] text-white font-bold py-4 rounded-2xl shadow-xl shadow-primary/20 hover:shadow-primary/40 hover:-translate-y-0.5 transition-all active:scale-95">
                        Create Account
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function switchTab(tab) {
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        const loginTab = document.getElementById('loginTab');
        const registerTab = document.getElementById('registerTab');

        if (tab === 'login') {
            loginForm.classList.remove('hidden');
            registerForm.classList.add('hidden');
            loginTab.classList.add('bg-primary', 'text-white', 'shadow-lg', 'shadow-primary/20');
            loginTab.classList.remove('text-gray-500');
            registerTab.classList.remove('bg-primary', 'text-white', 'shadow-lg', 'shadow-primary/20');
            registerTab.classList.add('text-gray-500');
        } else {
            loginForm.classList.add('hidden');
            registerForm.classList.remove('hidden');
            registerTab.classList.add('bg-primary', 'text-white', 'shadow-lg', 'shadow-primary/20');
            registerTab.classList.remove('text-gray-500');
            loginTab.classList.remove('bg-primary', 'text-white', 'shadow-lg', 'shadow-primary/20');
            loginTab.classList.add('text-gray-500');
        }
    }

    function setRole(role) {
        const seekerBtn = document.getElementById('roleSeeker');
        const recruiterBtn = document.getElementById('roleRecruiter');
        const roleInput = document.getElementById('roleInput');

        roleInput.value = role;

        if (role === 'seeker') {
            seekerBtn.classList.add('border-primary', 'bg-primary/10', 'text-white');
            seekerBtn.classList.remove('border-white/5', 'bg-bg', 'text-gray-500');
            recruiterBtn.classList.remove('border-primary', 'bg-primary/10', 'text-white');
            recruiterBtn.classList.add('border-white/5', 'bg-bg', 'text-gray-500');
        } else {
            recruiterBtn.classList.add('border-primary', 'bg-primary/10', 'text-white');
            recruiterBtn.classList.remove('border-white/5', 'bg-bg', 'text-gray-500');
            seekerBtn.classList.remove('border-primary', 'bg-primary/10', 'text-white');
            seekerBtn.classList.add('border-white/5', 'bg-bg', 'text-gray-500');
        }
    }
</script>
@endsection
