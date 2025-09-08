import React from 'react';
import { 
  LayoutDashboard, 
  Users, 
  FileText, 
  MessageCircle, 
  Files, 
  BarChart3, 
  Heart 
} from 'lucide-react';

interface SidebarProps {
  activeItem: string;
  onItemClick: (item: string) => void;
}

const Sidebar: React.FC<SidebarProps> = ({ activeItem, onItemClick }) => {
  const menuItems = [
    { id: 'dashboard', label: 'Dashboard', icon: LayoutDashboard },
    { id: 'patients', label: 'Patients', icon: Users },
    { id: 'notes', label: 'Notes', icon: FileText },
    { id: 'chat', label: 'Chat', icon: MessageCircle },
    { id: 'files', label: 'Files', icon: Files },
    { id: 'analytics', label: 'Analytics', icon: BarChart3 },
    { id: 'engagement', label: 'Engagement', icon: Heart },
  ];

  return (
    <div className="w-64 bg-emerald-600 min-h-screen text-white">
      <div className="p-6">
        <h1 className="text-2xl font-bold">GetCare</h1>
      </div>
      
      <nav className="mt-8">
        {menuItems.map((item) => {
          const Icon = item.icon;
          return (
            <button
              key={item.id}
              onClick={() => onItemClick(item.id)}
              className={`w-full flex items-center px-6 py-3 text-left hover:bg-emerald-700 transition-colors ${
                activeItem === item.id ? 'bg-emerald-700 border-r-4 border-white' : ''
              }`}
            >
              <Icon className="w-5 h-5 mr-3" />
              <span>{item.label}</span>
            </button>
          );
        })}
      </nav>
    </div>
  );
};

export default Sidebar;