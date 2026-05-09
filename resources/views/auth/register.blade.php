@extends('layouts.auth')

@section('content')

<div class="min-h-screen flex items-center justify-center px-6 py-20">

    <div class="w-full max-w-2xl bg-surface border border-white/10 rounded-3xl p-10">

        <div class="text-center mb-10">

            <h1 class="text-5xl font-syne font-extrabold mb-4">
                Create Account ✨
            </h1>

            <p class="text-gray-400">
                Start your job journey with JobFlow
            </p>

        </div>

        <form method="POST" action="{{ route('register.post') }}" class="space-y-5">

            @csrf

            <div class="grid md:grid-cols-2 gap-5">

                <input type="text"
                       name="name"
                       placeholder="Full Name"
                       class="bg-[#13131F] border border-white/10 rounded-2xl px-5 py-4">

                <input type="email"
                       name="email"
                       placeholder="Email"
                       class="bg-[#13131F] border border-white/10 rounded-2xl px-5 py-4">

            </div>

            <input type="password"
                   name="password"
                   placeholder="Password"
                   class="w-full bg-[#13131F] border border-white/10 rounded-2xl px-5 py-4">

            <select name="role"
                    class="w-full bg-[#13131F] border border-white/10 rounded-2xl px-5 py-4">

                <option value="seeker">Job Seeker</option>
                <option value="recruiter">Recruiter</option>

            </select>

            <button class="w-full py-4 rounded-2xl bg-gradient-to-r from-primary to-accent font-semibold">
                Create Account
            </button>

        </form>

    </div>

</div>

@endsection