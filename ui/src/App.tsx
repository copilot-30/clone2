import React, { useState } from 'react';
import LandingPage from './components/LandingPage';
import LoginPage from './components/LoginPage';
import RegisterPage from './components/RegisterPage';
import Sidebar from './components/Sidebar';
import Header from './components/Header';
import Dashboard from './components/Dashboard';

function App() {
  const [currentView, setCurrentView] = useState<'landing' | 'login' | 'register' | 'dashboard'>('dashboard');
  const [activeItem, setActiveItem] = useState('dashboard');

  const handleItemClick = (item: string) => {
    setActiveItem(item);
  };

  const handleLogin = () => {
    setCurrentView('login');
  };

  const handleRegister = () => {
    setCurrentView('register');
  };

  const handleAuthSuccess = () => {
    setCurrentView('dashboard');
  };

  const handleBackToLanding = () => {
    setCurrentView('landing');
  };

  if (currentView === 'landing') {
    return (
      <div>
        <LandingPage onLogin={handleLogin} onRegister={handleRegister} />
        <div className="fixed bottom-4 right-4">
          <button
            onClick={() => setCurrentView('dashboard')}
            className="bg-emerald-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-emerald-700 transition-colors"
          >
            View Dashboard
          </button>
        </div>
      </div>
    );
  }

  if (currentView === 'login') {
    return (
      <LoginPage
        onSwitchToRegister={handleRegister}
        onLogin={handleAuthSuccess}
        onBackToLanding={handleBackToLanding}
      />
    );
  }

  if (currentView === 'register') {
    return (
      <RegisterPage
        onSwitchToLogin={handleLogin}
        onRegister={handleAuthSuccess}
        onBackToLanding={handleBackToLanding}
      />
    );
  }

  const renderContent = () => {
    switch (activeItem) {
      case 'dashboard':
        return <Dashboard />;
      case 'patients':
        return (
          <div className="p-6 bg-gray-50 min-h-screen">
            <div className="bg-white rounded-lg shadow-sm p-8">
              <h2 className="text-2xl font-bold text-gray-800 mb-4">Patients</h2>
              <p className="text-gray-600">Patients management interface coming soon...</p>
            </div>
          </div>
        );
      case 'notes':
        return (
          <div className="p-6 bg-gray-50 min-h-screen">
            <div className="bg-white rounded-lg shadow-sm p-8">
              <h2 className="text-2xl font-bold text-gray-800 mb-4">Notes</h2>
              <p className="text-gray-600">Notes management interface coming soon...</p>
            </div>
          </div>
        );
      case 'chat':
        return (
          <div className="p-6 bg-gray-50 min-h-screen">
            <div className="bg-white rounded-lg shadow-sm p-8">
              <h2 className="text-2xl font-bold text-gray-800 mb-4">Chat</h2>
              <p className="text-gray-600">Chat interface coming soon...</p>
            </div>
          </div>
        );
      case 'files':
        return (
          <div className="p-6 bg-gray-50 min-h-screen">
            <div className="bg-white rounded-lg shadow-sm p-8">
              <h2 className="text-2xl font-bold text-gray-800 mb-4">Files</h2>
              <p className="text-gray-600">File management interface coming soon...</p>
            </div>
          </div>
        );
      case 'analytics':
        return (
          <div className="p-6 bg-gray-50 min-h-screen">
            <div className="bg-white rounded-lg shadow-sm p-8">
              <h2 className="text-2xl font-bold text-gray-800 mb-4">Analytics</h2>
              <p className="text-gray-600">Analytics dashboard coming soon...</p>
            </div>
          </div>
        );
      case 'engagement':
        return (
          <div className="p-6 bg-gray-50 min-h-screen">
            <div className="bg-white rounded-lg shadow-sm p-8">
              <h2 className="text-2xl font-bold text-gray-800 mb-4">Engagement</h2>
              <p className="text-gray-600">Engagement tools coming soon...</p>
            </div>
          </div>
        );
      default:
        return <Dashboard />;
    }
  };

  return (
    <div className="flex h-screen bg-gray-50">
      <div className="fixed top-4 left-4 z-50">
        <button
          onClick={handleBackToLanding}
          className="bg-white text-emerald-600 px-4 py-2 rounded-lg shadow-lg hover:bg-gray-50 transition-colors border border-emerald-600"
        >
          Back to Landing
        </button>
      </div>
      <Sidebar activeItem={activeItem} onItemClick={handleItemClick} />
      <div className="flex-1 flex flex-col overflow-hidden">
        <Header />
        <main className="flex-1 overflow-y-auto">
          {renderContent()}
        </main>
      </div>
    </div>
  );
}

export default App;