import React, { useState } from 'react';
import { Mail, Lock, Eye, EyeOff } from 'lucide-react';

interface LoginPageProps {
  onSwitchToRegister: () => void;
  onLogin: () => void;
  onBackToLanding: () => void;
}

const LoginPage: React.FC<LoginPageProps> = ({ onSwitchToRegister, onLogin, onBackToLanding }) => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [showPassword, setShowPassword] = useState(false);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    // Here you would typically handle the login logic
    onLogin();
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-blue-50 to-cyan-100 flex items-center justify-center p-4">
      {/* Back to Landing Button */}
      <button
        onClick={onBackToLanding}
        className="absolute top-6 left-6 text-slate-600 hover:text-emerald-600 font-medium transition-colors"
      >
        ← Back to Home
      </button>

      <div className="w-full max-w-4xl bg-white rounded-3xl shadow-2xl overflow-hidden">
        <div className="flex flex-col lg:flex-row min-h-[600px]">
          {/* Left Side - Welcome Section */}
          <div className="lg:w-1/2 bg-gradient-to-br from-emerald-500 to-emerald-600 p-12 flex items-center justify-center">
            <div className="text-center text-white">
              <h1 className="text-4xl lg:text-5xl font-bold mb-4">
                Hello!
              </h1>
              <h2 className="text-3xl lg:text-4xl font-bold">
                Welcome to
              </h2>
              <h2 className="text-3xl lg:text-4xl font-bold">
                GetCare.
              </h2>
            </div>
          </div>

          {/* Right Side - Login Form */}
          <div className="lg:w-1/2 p-12 flex items-center justify-center">
            <div className="w-full max-w-md">
              <h2 className="text-3xl font-bold text-slate-800 mb-8 text-center">Login</h2>
              
              <form onSubmit={handleSubmit} className="space-y-6">
                {/* Email Field */}
                <div className="relative">
                  <div className="flex items-center border-b-2 border-gray-300 focus-within:border-emerald-500 transition-colors">
                    <Mail className="w-5 h-5 text-gray-400 mr-3" />
                    <input
                      type="email"
                      placeholder="Enter your email"
                      value={email}
                      onChange={(e) => setEmail(e.target.value)}
                      className="flex-1 py-3 bg-transparent outline-none text-slate-700 placeholder-gray-400"
                      required
                    />
                  </div>
                </div>

                {/* Password Field */}
                <div className="relative">
                  <div className="flex items-center border-b-2 border-gray-300 focus-within:border-emerald-500 transition-colors">
                    <Lock className="w-5 h-5 text-gray-400 mr-3" />
                    <input
                      type={showPassword ? 'text' : 'password'}
                      placeholder="Enter your password"
                      value={password}
                      onChange={(e) => setPassword(e.target.value)}
                      className="flex-1 py-3 bg-transparent outline-none text-slate-700 placeholder-gray-400"
                      required
                    />
                    <button
                      type="button"
                      onClick={() => setShowPassword(!showPassword)}
                      className="text-gray-400 hover:text-gray-600 transition-colors"
                    >
                      {showPassword ? <EyeOff className="w-5 h-5" /> : <Eye className="w-5 h-5" />}
                    </button>
                  </div>
                </div>

                {/* Forgot Password */}
                <div className="text-right">
                  <button
                    type="button"
                    className="text-gray-500 hover:text-emerald-600 transition-colors text-sm"
                  >
                    Forgot password?
                  </button>
                </div>

                {/* Submit Button */}
                <button
                  type="submit"
                  className="w-full bg-emerald-500 text-white py-4 rounded-full font-semibold text-lg hover:bg-emerald-600 transition-all duration-300 transform hover:scale-105 shadow-lg"
                >
                  SUBMIT
                </button>

                {/* Switch to Register */}
                <div className="text-center">
                  <span className="text-gray-500">No account yet? </span>
                  <button
                    type="button"
                    onClick={onSwitchToRegister}
                    className="text-emerald-600 hover:text-emerald-700 font-medium transition-colors"
                  >
                    Register here.
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default LoginPage;