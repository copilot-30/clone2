import React from 'react';

const DoctorDashboard: React.FC = () => {
  return (
    <div className="p-4">
      <h1 className="text-2xl font-bold mb-4">Doctor Dashboard</h1>
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div className="bg-white p-4 rounded shadow">
          <h2 className="text-xl font-semibold mb-2">Upcoming Appointments</h2>
          <p>Display list of upcoming appointments here.</p>
        </div>
        <div className="bg-white p-4 rounded shadow">
          <h2 className="text-xl font-semibold mb-2">Quick Actions</h2>
          <ul>
            <li>Configure Availability</li>
            <li>View Consultations</li>
          </ul>
        </div>
        <div className="bg-white p-4 rounded shadow">
          <h2 className="text-xl font-semibold mb-2">Earnings Summary</h2>
          <p>Display earnings summary here.</p>
        </div>
      </div>
    </div>
  );
};

export default DoctorDashboard;