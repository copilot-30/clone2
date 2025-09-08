import React from 'react';
import UpcomingAppointments from './UpcomingAppointments';
import AssignedPatients from './AssignedPatients';
import RecentNotes from './RecentNotes';

const Dashboard: React.FC = () => {
  return (
    <div className="p-6 bg-gray-50 min-h-screen">
      {/* Top Section - Appointments and Patients */}
      <div className="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">
        <UpcomingAppointments />
        <AssignedPatients />
      </div>
      
      {/* Bottom Section - Recent Notes */}
      <RecentNotes />
    </div>
  );
};

export default Dashboard;