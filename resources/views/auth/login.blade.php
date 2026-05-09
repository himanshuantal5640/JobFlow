@extends('layouts.auth')

@section('content')

<div class="min-h-screen grid lg:grid-cols-2">

    <!-- LEFT SIDE -->
    <div class="hidden lg:flex flex-col justify-center px-20 bg-gradient-to-br from-primary/10 to-accent/5 border-r border-white/10">

        <div class="max-w-lg">

            <div class="text-primary uppercase tracking-[0.2em] text-xs mb-5">
                Your Career Command Center
            </div>

            <h1 class="text-6xl font-syne font-extrabold leading-tight mb-6">
                Track. Apply.<br>
                <span class="bg-gradient-to-r from-primary to-accent bg-clip-text text-transparent">
                    Get Hired.
                </span>
            </h1>

            <p class="text-gray-400 leading-8 mb-10">
                Join thousands of professionals using JobFlow.
            </p>

            <div class="bg-surface border border-white/10 rounded-3xl p-6">
                <div class="flex justify-between mb-5">
                    <span class="text-gray-400">Application Tracker</span>

                    <div class="flex gap-2">
                        <div class="w-2 h-2 rounded-full bg-red-400"></div>
                        <div class="w-2 h-2 rounded-full bg-yellow-400"></div>
                        <div class="w-2 h-2 rounded-full bg-green-400"></div>
                    </div>
                </div>

                <div class="grid grid-cols-5 gap-3">

                    <div class="space-y-2">
                        <div class="text-xs text-primary">Applied</div>
                        <div class="h-10 rounded bg-primary/20"></div>
                    </div>

                    <div class="space-y-2">
                        <div class="text-xs text-cyan-400">Review</div>
                        <div class="h-10 rounded bg-cyan-400/20"></div>
                    </div>

                    <div class="space-y-2">
                        <div class="text-xs text-yellow-400">Interview</div>
                        <div class="h-10 rounded bg-yellow-400/20"></div>
                    </div>

                    <div class="space-y-2">
                        <div class="text-xs text-accent">Offer</div>
                        <div class="h-10 rounded bg-accent/20"></div>
                    </div>

                    <div class="space-y-2">
                        <div class="text-xs text-red-400">Reject</div>
                        <div class="h-10 rounded bg-red-400/20"></div>
                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- RIGHT SIDE -->
    <div class="flex items-center justify-center px-6 py-20">

        <div class="w-full max-w-md">

            <div class="mb-10">

                <a href="/" class="flex items-center gap-3 mb-10">

                    <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-primary to-accent flex items-center justify-center font-bold">
                        J
                    </div>

                    <span class="text-3xl font-syne font-bold">
                        JobFlow
                    </span>

                </a>

                <h2 class="text-4xl font-syne font-bold mb-3">
                    Welcome Back 👋
                </h2>

                <p class="text-gray-400">
                    Sign in to continue
                </p>

            </div>

            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">

                @csrf

                <div>
                    <label class="block mb-2 text-sm text-gray-400">
                        Email
                    </label>

                    <input type="email"
                           name="email"
                           class="w-full bg-surface border border-white/10 rounded-2xl px-5 py-4 outline-none focus:border-primary">
                </div>

                <div>
                    <label class="block mb-2 text-sm text-gray-400">
                        Password
                    </label>

                    <input type="password"
                           name="password"
                           class="w-full bg-surface border border-white/10 rounded-2xl px-5 py-4 outline-none focus:border-primary">
                </div>

                <button class="w-full py-4 rounded-2xl bg-gradient-to-r from-primary to-accent font-semibold">
                    Sign In
                </button>

            </form>

            <div class="mt-6 text-center text-gray-400">
                Don’t have an account?

                <a href="{{ route('register') }}" class="text-primary">
                    Create account
                </a>
            </div>

        </div>

    </div>

</div>

@endsection