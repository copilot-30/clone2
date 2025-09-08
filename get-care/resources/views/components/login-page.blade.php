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
            Hello!
          </h1>
          <h2 class="text-3xl lg:text-4xl font-bold">
            Welcome to
          </h2>
          <h2 class="text-3xl lg:text-4xl font-bold">
            GetCare.
          </h2>
        </div>
      </div>

      {{-- Right Side - Login Form --}}
      <div class="lg:w-1/2 p-12 flex items-center justify-center">
        <div class="w-full max-w-md">
          <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Login</h2>

          <form action="/login" method="POST" class="space-y-6">
            @csrf
            {{-- Email Field --}}
            <div class="relative">
              <div class="flex items-center border-b-2 border-gray-300 focus-within:border-emerald-500 transition-colors">
                {{-- Mail Icon --}}
                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26c.55.36 1.25.36 1.8 0L21 8m-2 10a2 2 0 01-2 2H7a2 2 0 01-2-2V8a2 2 0 012-2h10a2 2 0 012 2v10z"></path></svg>
                <input
                  type="email"
                  placeholder="Enter your email"
                  name="email"
                  value="{{ old('email') }}"
                  class="flex-1 py-3 bg-transparent outline-none text-slate-700 placeholder-gray-400"
                  required
                />
              </div>
            </div>

            

            {{-- Password Field --}}
            <div class="relative">
              <div class="flex items-center border-b-2 border-gray-300 focus-within:border-emerald-500 transition-colors">
                {{-- Lock Icon --}}
                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-4a2 2 0 00-2-2H6a2 2 0 00-2 2v4a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                <input
                  type="password" {{-- Simplified: Blade doesn't have React's state for show/hide password --}}
                  placeholder="Enter your password"
                  name="password"
                  class="flex-1 py-3 bg-transparent outline-none text-slate-700 placeholder-gray-400"
                  required
                />
                {{-- No show/hide password button in Blade unless custom JS is added --}}
              </div>
            </div>

            {{-- Forgot Password --}}
            <div class="text-right">
              <a href="/account-recovery" class="text-gray-500 hover:text-emerald-600 transition-colors text-sm">
                Forgot password?
              </a>
            </div>

            {{-- Submit Button --}}
            <button
              type="submit"
              class="w-full bg-emerald-500 text-white py-4 rounded-full font-semibold text-lg hover:bg-emerald-600 transition-all duration-300 transform hover:scale-105 shadow-lg"
            >
              SUBMIT
            </button>

            {{-- Switch to Register --}}
            <div class="text-center">
              <span class="text-gray-500">No account yet? </span>
              <a href="/register" class="text-emerald-600 hover:text-emerald-700 font-medium transition-colors">
                Register here.
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection