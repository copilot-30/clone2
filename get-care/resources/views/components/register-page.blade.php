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

      {{-- Right Side - Register Form --}}
      <div class="lg:w-1/2 p-12 flex items-center justify-center">
        <div class="w-full max-w-md">
          <h2 class="text-3xl font-bold text-slate-800 mb-8 text-center">Register</h2>

          <form action="/register" method="POST" class="space-y-6">
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

            {{-- First Name Field --}}
            <div class="relative">
              <div class="flex items-center border-b-2 border-gray-300 focus-within:border-emerald-500 transition-colors">
                {{-- User Icon --}}
                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <input
                  type="text"
                  placeholder="Enter your first name"
                  name="first_name"
                  value="{{ old('first_name') }}"
                  class="flex-1 py-3 bg-transparent outline-none text-slate-700 placeholder-gray-400"
                  required
                />
              </div>
            </div>

            {{-- Last Name Field --}}
            <div class="relative">
              <div class="flex items-center border-b-2 border-gray-300 focus-within:border-emerald-500 transition-colors">
                {{-- User Icon --}}
                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <input
                  type="text"
                  placeholder="Enter your last name"
                  name="last_name"
                  value="{{ old('last_name') }}"
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
                  placeholder="Create your password"
                  name="password"
                  class="flex-1 py-3 bg-transparent outline-none text-slate-700 placeholder-gray-400"
                  required
                />
                {{-- No show/hide password button in Blade unless custom JS is added --}}
              </div>
            </div>

            {{-- Confirm Password Field --}}
            <div class="relative">
              <div class="flex items-center border-b-2 border-gray-300 focus-within:border-emerald-500 transition-colors">
                {{-- Lock Icon --}}
                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-4a2 2 0 00-2-2H6a2 2 0 00-2 2v4a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                <input
                  type="password" {{-- Simplified: Blade doesn't have React's state for show/hide password --}}
                  placeholder="Confirm your password"
                  name="password_confirmation"
                  class="flex-1 py-3 bg-transparent outline-none text-slate-700 placeholder-gray-400"
                  required
                />
                {{-- No show/hide password button in Blade unless custom JS is added --}}
              </div>
            </div>

            {{-- No privacy consent checkbox needed based on AuthController refactor --}}

            {{-- Submit Button --}}
            <button
              type="submit"
              class="w-full bg-emerald-500 text-white py-4 rounded-full font-semibold text-lg hover:bg-emerald-600 transition-all duration-300 transform hover:scale-105 shadow-lg"
            >
              SUBMIT
            </button>

            {{-- Switch to Login --}}
            <div class="text-center">
              <span class="text-gray-500">Already have an account? </span>
              <a href="/login" class="text-emerald-600 hover:text-emerald-700 font-medium transition-colors">
                Login here.
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Privacy Policy Modal --}}
<div id="privacy-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <!-- Modal header -->
        <div class="flex justify-between items-center pb-3">
            <h3 class="text-2xl font-bold text-gray-900">Terms and Conditions</h3>
            <button class="text-gray-400 hover:text-gray-600" onclick="hidePrivacyModal()">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <!-- Modal body - Load privacy_policy.blade.php content here -->
        <div class="mt-2 text-gray-600" style="max-height: 70vh; overflow-y: auto;">
            @include('privacy_policy')
        </div>
        <!-- Modal footer -->
        <div class="flex justify-end pt-4">
            <button class="px-4 py-2 bg-emerald-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-emerald-600" onclick="hidePrivacyModal()">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    function showPrivacyModal() {
        document.getElementById('privacy-modal').classList.remove('hidden');
    }

    function hidePrivacyModal() {
        document.getElementById('privacy-modal').classList.add('hidden');
    }
</script>
@endsection