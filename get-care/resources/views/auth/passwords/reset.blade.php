@extends('layout')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-cyan-100 flex items-center justify-center p-4">
  {{-- Back to Landing Button --}}
  <button
    onclick="window.location.href='/'" {{-- Assuming '/' is the landing page route --}}
    class="absolute top-6 left-6 text-slate-600 hover:text-emerald-600 font-medium transition-colors"
  >
    ← Back to Home
  </button>

  <div class="w-full max-w-4xl bg-white rounded-3xl shadow-2xl overflow-hidden">
    <div class="flex flex-col lg:flex-row min-h-[600px]">
      {{-- Left Side - Welcome Section --}}
      <div class="lg:w-1/2 bg-gradient-to-br from-emerald-500 to-emerald-600 p-12 flex items-center justify-center">
        <div class="text-center text-white">
          <h1 class="text-4xl lg:text-5xl font-bold mb-4">
            Welcome back
          </h1>
          <h2 class="text-xl lg:text-2xl font-medium">
            <!-- Enter your new password below. -->
          </h2>
        </div>
      </div>

      {{-- Right Side - Reset Password Form --}}
      <div class="lg:w-1/2 p-12 flex items-center justify-center">
        <div class="w-full max-w-md">
          <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Reset Password</h2>

          @if (session('status'))
              <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                  {{ session('status') }}
              </div>
          @endif

          <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            {{-- Email Field --}}
            <div class="relative">
              <div class="flex items-center border-b-2 border-gray-300 focus-within:border-emerald-500 transition-colors">
                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26c.55.36 1.25.36 1.8 0L21 8m-2 10a2 2 0 01-2 2H7a2 2 0 01-2-2V8a2 2 0 012-2h10a2 2 0 012 2v10z"></path></svg>
                <input
                  id="email"
                  type="email"
                  placeholder="Enter your email"
                  name="email"
                  value="{{ $email ?? old('email') }}"
                  class="flex-1 py-3 bg-transparent outline-none text-slate-700 placeholder-gray-400 @error('email') border-red-500 @enderror"
                  required
                  autocomplete="email"
                  autofocus
                />
              </div>
              @error('email')
                  <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
              @enderror
            </div>

            {{-- Password Field --}}
            <div class="relative">
              <div class="flex items-center border-b-2 border-gray-300 focus-within:border-emerald-500 transition-colors">
                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                <input
                  id="password"
                  type="password"
                  placeholder="New Password"
                  name="password"
                  class="flex-1 py-3 bg-transparent outline-none text-slate-700 placeholder-gray-400 @error('password') border-red-500 @enderror"
                  required
                  autocomplete="new-password"
                />
              </div>
              @error('password')
                  <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
              @enderror
            </div>

            {{-- Confirm Password Field --}}
            <div class="relative">
              <div class="flex items-center border-b-2 border-gray-300 focus-within:border-emerald-500 transition-colors">
                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                <input
                  id="password-confirm"
                  type="password"
                  placeholder="Confirm New Password"
                  name="password_confirmation"
                  class="flex-1 py-3 bg-transparent outline-none text-slate-700 placeholder-gray-400"
                  required
                  autocomplete="new-password"
                />
              </div>
            </div>

            {{-- Submit Button --}}
            <button
              type="submit"
              class="w-full bg-emerald-500 text-white py-4 rounded-full font-semibold text-lg hover:bg-emerald-600 transition-all duration-300 transform hover:scale-105 shadow-lg"
            >
              Reset Password
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection