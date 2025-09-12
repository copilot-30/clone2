@extends('layout')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-cyan-50">
  {{-- Navigation --}}
  <nav class="bg-white/90 backdrop-blur-sm border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <div class="flex items-center">
          <span class="text-2xl font-bold">
            <span class="text-emerald-600">Get</span>
            <span class="text-blue-600">Care</span>
          </span>
        </div>

        <div class="hidden md:flex items-center space-x-8">
          <a href="#about" class="text-gray-700 hover:text-emerald-600 font-medium transition-colors">
            ABOUT
          </a>
          <button onclick="window.location.href='/login'" class="text-gray-700 hover:text-emerald-600 font-medium transition-colors">
            LOGIN
          </button>
          <button onclick="window.location.href='/register'" class="bg-emerald-600 text-white px-6 py-2 rounded-full font-medium hover:bg-emerald-700 transition-colors">
            REGISTER
          </button>
        </div>
      </div>
    </div>
  </nav>

  {{-- Hero Section --}}
  <section class="relative overflow-hidden min-h-screen flex items-center justify-center">
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.pexels.com/photos/5206923/pexels-photo-5206923.jpeg?auto=compress&cs=tinysrgb&w=1000'); transform: scaleX(-1);"></div> {{-- Flipped background image --}}
    <div class="absolute inset-0 bg-black opacity-50"></div> {{-- Overlay for readability --}}
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-20 text-white">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        {{-- Left Content (not flipped) --}}
        <div class="space-y-8">
          <div class="space-y-4">
            <h1 class="text-4xl lg:text-6xl font-bold leading-tight">
              <span class="text-white">YOUR </span>
              <span class="text-emerald-300">ONLINE</span>
              <br />
              <span class="text-blue-300">HEALTH TEAM</span>
            </h1>

            <p class="text-lg lg:text-xl text-gray-200 leading-relaxed max-w-lg">
              We aim to be the leading provider of proactive,
              convenient, and comprehensive specialist healthcare
              for young adults in the Philippines, empowering them
              to manage their health effectively through a
              seamless, tech-enabled experience.
            </p>
          </div>

          <div class="flex flex-col sm:flex-row gap-4">
            <button onclick="window.location.href='/register'" class="bg-emerald-600 text-white px-8 py-4 rounded-full font-semibold text-lg hover:bg-emerald-700 transition-all duration-300 transform hover:scale-105 flex items-center justify-center group">
              GET STARTED
              {{-- ArrowRight Icon --}}
              <svg class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
            <button class="border-2 border-gray-200 text-gray-200 px-8 py-4 rounded-full font-semibold text-lg hover:border-emerald-300 hover:text-emerald-300 transition-all duration-300">
              LEARN MORE
            </button>
          </div>
        </div>
        {{-- Right Placeholder (now empty) --}}
        <div></div>
      </div>
    </div>
  </section>
  {{-- Features Section --}}
  <section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16">
        <h2 class="text-3xl lg:text-4xl font-bold text-slate-800 mb-4">
          Why Choose GetCare?
        </h2>
        <p class="text-xl text-slate-600 max-w-3xl mx-auto">
          Experience healthcare that's designed around you, with cutting-edge technology
          and compassionate care at every step.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-emerald-50 to-emerald-100 hover:shadow-lg transition-all duration-300">
          <div class="bg-emerald-600 rounded-full p-4 w-16 h-16 mx-auto mb-6">
            {{-- Users Icon --}}
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h2a2 2 0 002-2V7a2 2 0 00-2-2H9a2 2 0 00-2 2v10a2 2 0 002 2h2m4 0l3-3m-3 3l-3-3m-6 0v-4m0 0a2 2 0 01-2-2V7a2 2 0 012-2h2m4 0l3-3m-3 3l-3-3"></path></svg>
          </div>
          <h3 class="text-xl font-bold text-slate-800 mb-4">Expert Team</h3>
          <p class="text-slate-600">
            Access to qualified healthcare professionals specializing in young adult care.
          </p>
        </div>

        <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100 hover:shadow-lg transition-all duration-300">
          <div class="bg-blue-600 rounded-full p-4 w-16 h-16 mx-auto mb-6">
            {{-- Clock Icon --}}
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
          </div>
          <h3 class="text-xl font-bold text-slate-800 mb-4">24/7 Availability</h3>
          <p class="text-slate-600">
            Round-the-clock support and consultation whenever you need it most.
          </p>
        </div>

        <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-cyan-50 to-cyan-100 hover:shadow-lg transition-all duration-300">
          <div class="bg-cyan-600 rounded-full p-4 w-16 h-16 mx-auto mb-6">
            {{-- Shield Icon --}}
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.001 12.001 0 002 12c0 2.227.53 4.378 1.45 6.273L12 22l8.55-3.727A12.001 12.001 0 0022 12c0-2.363-.667-4.573-1.882-6.476z"></path></svg>
          </div>
          <h3 class="text-xl font-bold text-slate-800 mb-4">Secure & Private</h3>
          <p class="text-slate-600">
            Your health data is protected with enterprise-grade security measures.
          </p>
        </div>
      </div>
    </div>
  </section>

  {{-- CTA Section --}}
  <section class="py-20 bg-gradient-to-r from-emerald-600 to-blue-600">
    <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
      <h2 class="text-3xl lg:text-4xl font-bold text-white mb-6">
        Ready to Transform Your Healthcare Experience?
      </h2>
      <p class="text-xl text-emerald-100 mb-8">
        Join thousands of young adults who trust GetCare for their health needs.
      </p>
      <button onclick="window.location.href='/register'" class="bg-white text-emerald-600 px-8 py-4 rounded-full font-semibold text-lg hover:bg-gray-50 transition-all duration-300 transform hover:scale-105">
        Get Started Today
      </button>
    </div>
  </section>

  {{-- Footer --}}
  <footer class="bg-slate-800 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <div class="col-span-1 md:col-span-2">
          <div class="flex items-center mb-4">
            <span class="text-2xl font-bold">
              <span class="text-emerald-400">Get</span>
              <span class="text-blue-400">Care</span>
            </span>
          </div>
          <p class="text-slate-300 mb-4">
            Empowering young adults in the Philippines with accessible,
            comprehensive healthcare through innovative technology.
          </p>
        </div>

        <div>
          <h4 class="font-semibold mb-4">Quick Links</h4>
          <ul class="space-y-2 text-slate-300">
            <li><a href="#" class="hover:text-emerald-400 transition-colors">About Us</a></li>
            <li><a href="#" class="hover:text-emerald-400 transition-colors">Services</a></li>
            <li><a href="#" class="hover:text-emerald-400 transition-colors">Contact</a></li>
            <li><a href="#" class="hover:text-emerald-400 transition-colors">Support</a></li>
          </ul>
        </div>

        <div>
          <h4 class="font-semibold mb-4">Legal</h4>
          <ul class="space-y-2 text-slate-300">
            <li><a href="#" class="hover:text-emerald-400 transition-colors">Privacy Policy</a></li>
            <li><a href="#" class="hover:text-emerald-400 transition-colors">Terms of Service</a></li>
            <li><a href="#" class="hover:text-emerald-400 transition-colors">HIPAA Compliance</a></li>
          </ul>
        </div>
      </div>

      <div class="border-t border-slate-700 mt-8 pt-8 text-center text-slate-400">
        <p>&copy; 2025 GetCare. All rights reserved.</p>
      </div>
    </div>
  </footer>
</div>
@endsection