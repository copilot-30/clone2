import React from 'react';
import { ArrowRight, Heart, Shield, Users, Clock } from 'lucide-react';

interface LandingPageProps {
  onLogin: () => void;
  onRegister: () => void;
}

const LandingPage: React.FC<LandingPageProps> = ({ onLogin, onRegister }) => {
  return (
    <div className="min-h-screen bg-gradient-to-br from-blue-50 to-cyan-50">
      {/* Navigation */}
      <nav className="bg-white/90 backdrop-blur-sm border-b border-gray-200 sticky top-0 z-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between items-center h-16">
            <div className="flex items-center">
              <span className="text-2xl font-bold">
                <span className="text-emerald-600">Get</span>
                <span className="text-blue-600">Care</span>
              </span>
            </div>
            
            <div className="hidden md:flex items-center space-x-8">
              <a href="#about" className="text-gray-700 hover:text-emerald-600 font-medium transition-colors">
                ABOUT
              </a>
              <button onClick={onLogin} className="text-gray-700 hover:text-emerald-600 font-medium transition-colors">
                LOGIN
              </button>
              <button onClick={onRegister} className="bg-emerald-600 text-white px-6 py-2 rounded-full font-medium hover:bg-emerald-700 transition-colors">
                REGISTER
              </button>
            </div>
          </div>
        </div>
      </nav>

      {/* Hero Section */}
      <section className="relative overflow-hidden">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-20">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            {/* Left Content */}
            <div className="space-y-8">
              <div className="space-y-4">
                <h1 className="text-4xl lg:text-6xl font-bold leading-tight">
                  <span className="text-slate-800">YOUR </span>
                  <span className="text-emerald-600">ONLINE</span>
                  <br />
                  <span className="text-blue-600">HEALTH TEAM</span>
                </h1>
                
                <p className="text-lg lg:text-xl text-slate-600 leading-relaxed max-w-lg">
                  We aim to be the leading provider of proactive, 
                  convenient, and comprehensive specialist healthcare 
                  for young adults in the Philippines, empowering them 
                  to manage their health effectively through a 
                  seamless, tech-enabled experience.
                </p>
              </div>
              
              <div className="flex flex-col sm:flex-row gap-4">
                <button onClick={onRegister} className="bg-emerald-600 text-white px-8 py-4 rounded-full font-semibold text-lg hover:bg-emerald-700 transition-all duration-300 transform hover:scale-105 flex items-center justify-center group">
                  GET STARTED
                  <ArrowRight className="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" />
                </button>
                <button className="border-2 border-gray-400 text-gray-700 px-8 py-4 rounded-full font-semibold text-lg hover:border-emerald-600 hover:text-emerald-600 transition-all duration-300">
                  LEARN MORE
                </button>
              </div>
            </div>
            
            {/* Right Image */}
            <div className="relative">
              <div className="relative rounded-2xl overflow-hidden shadow-2xl">
                <img 
                  src="https://images.pexels.com/photos/5215024/pexels-photo-5215024.jpeg?auto=compress&cs=tinysrgb&w=800" 
                  alt="Healthcare professionals working together" 
                  className="w-full h-[500px] object-cover"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-emerald-600/20 to-transparent"></div>
              </div>
              
              {/* Floating Elements */}
              <div className="absolute -top-4 -right-4 bg-white rounded-full p-4 shadow-lg">
                <Heart className="w-8 h-8 text-red-500" />
              </div>
              <div className="absolute -bottom-4 -left-4 bg-white rounded-full p-4 shadow-lg">
                <Shield className="w-8 h-8 text-emerald-600" />
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Features Section */}
      <section className="py-20 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16">
            <h2 className="text-3xl lg:text-4xl font-bold text-slate-800 mb-4">
              Why Choose GetCare?
            </h2>
            <p className="text-xl text-slate-600 max-w-3xl mx-auto">
              Experience healthcare that's designed around you, with cutting-edge technology 
              and compassionate care at every step.
            </p>
          </div>
          
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div className="text-center p-8 rounded-2xl bg-gradient-to-br from-emerald-50 to-emerald-100 hover:shadow-lg transition-all duration-300">
              <div className="bg-emerald-600 rounded-full p-4 w-16 h-16 mx-auto mb-6">
                <Users className="w-8 h-8 text-white" />
              </div>
              <h3 className="text-xl font-bold text-slate-800 mb-4">Expert Team</h3>
              <p className="text-slate-600">
                Access to qualified healthcare professionals specializing in young adult care.
              </p>
            </div>
            
            <div className="text-center p-8 rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100 hover:shadow-lg transition-all duration-300">
              <div className="bg-blue-600 rounded-full p-4 w-16 h-16 mx-auto mb-6">
                <Clock className="w-8 h-8 text-white" />
              </div>
              <h3 className="text-xl font-bold text-slate-800 mb-4">24/7 Availability</h3>
              <p className="text-slate-600">
                Round-the-clock support and consultation whenever you need it most.
              </p>
            </div>
            
            <div className="text-center p-8 rounded-2xl bg-gradient-to-br from-cyan-50 to-cyan-100 hover:shadow-lg transition-all duration-300">
              <div className="bg-cyan-600 rounded-full p-4 w-16 h-16 mx-auto mb-6">
                <Shield className="w-8 h-8 text-white" />
              </div>
              <h3 className="text-xl font-bold text-slate-800 mb-4">Secure & Private</h3>
              <p className="text-slate-600">
                Your health data is protected with enterprise-grade security measures.
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-20 bg-gradient-to-r from-emerald-600 to-blue-600">
        <div className="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
          <h2 className="text-3xl lg:text-4xl font-bold text-white mb-6">
            Ready to Transform Your Healthcare Experience?
          </h2>
          <p className="text-xl text-emerald-100 mb-8">
            Join thousands of young adults who trust GetCare for their health needs.
          </p>
          <button onClick={onRegister} className="bg-white text-emerald-600 px-8 py-4 rounded-full font-semibold text-lg hover:bg-gray-50 transition-all duration-300 transform hover:scale-105">
            Get Started Today
          </button>
        </div>
      </section>

      {/* Footer */}
      <footer className="bg-slate-800 text-white py-12">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div className="col-span-1 md:col-span-2">
              <div className="flex items-center mb-4">
                <span className="text-2xl font-bold">
                  <span className="text-emerald-400">Get</span>
                  <span className="text-blue-400">Care</span>
                </span>
              </div>
              <p className="text-slate-300 mb-4">
                Empowering young adults in the Philippines with accessible, 
                comprehensive healthcare through innovative technology.
              </p>
            </div>
            
            <div>
              <h4 className="font-semibold mb-4">Quick Links</h4>
              <ul className="space-y-2 text-slate-300">
                <li><a href="#" className="hover:text-emerald-400 transition-colors">About Us</a></li>
                <li><a href="#" className="hover:text-emerald-400 transition-colors">Services</a></li>
                <li><a href="#" className="hover:text-emerald-400 transition-colors">Contact</a></li>
                <li><a href="#" className="hover:text-emerald-400 transition-colors">Support</a></li>
              </ul>
            </div>
            
            <div>
              <h4 className="font-semibold mb-4">Legal</h4>
              <ul className="space-y-2 text-slate-300">
                <li><a href="#" className="hover:text-emerald-400 transition-colors">Privacy Policy</a></li>
                <li><a href="#" className="hover:text-emerald-400 transition-colors">Terms of Service</a></li>
                <li><a href="#" className="hover:text-emerald-400 transition-colors">HIPAA Compliance</a></li>
              </ul>
            </div>
          </div>
          
          <div className="border-t border-slate-700 mt-8 pt-8 text-center text-slate-400">
            <p>&copy; 2025 GetCare. All rights reserved.</p>
          </div>
        </div>
      </footer>
    </div>
  );
};

export default LandingPage;