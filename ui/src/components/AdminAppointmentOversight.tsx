import React, { useState, useEffect } from 'react';

interface Appointment {
  id: string;
  patient_name: string;
  doctor_name: string;
  appointment_datetime: string;
  type: string;
  status: string;
}

const AdminAppointmentOversight: React.FC = () => {
  const [appointments, setAppointments] = useState<Appointment[]>([]); // Placeholder for appointment data

  useEffect(() => {
    // Implement API call to fetch all appointments
    console.log('Fetching all appointments...');
    setAppointments([
      { id: 'a1', patient_name: 'Alice Smith', doctor_name: 'Dr. John Doe', appointment_datetime: '2025-09-10T10:00:00', type: 'online', status: 'scheduled' },
      { id: 'a2', patient_name: 'Bob Johnson', doctor_name: 'Dr. Jane Smith', appointment_datetime: '2025-09-10T14:00:00', type: 'face-to-face', status: 'completed' },
    ]);
  }, []);

  const handleCancel = (id: string) => {
    console.log('Cancelling appointment:', id);
    // Implement API call to cancel appointment
  };

  const handleReschedule = (id: string) => {
    console.log('Rescheduling appointment:', id);
    // Implement API call to reschedule appointment
  };

  const handleReassign = (id: string) => {
    console.log('Reassigning appointment:', id);
    // Implement API call to reassign appointment
  };

  return (
    <div className="p-4">
      <h1 className="text-2xl font-bold mb-4">Admin: Appointment Oversight</h1>

      <div className="bg-white p-4 rounded shadow">
        <h2 className="text-xl font-semibold mb-4">All Appointments</h2>
        <table className="min-w-full divide-y divide-gray-200">
          <thead className="bg-gray-50">
            <tr>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date/Time</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody className="bg-white divide-y divide-gray-200">
            {appointments.map((appt) => (
              <tr key={appt.id}>
                <td className="px-6 py-4 whitespace-nowrap">{appt.patient_name}</td>
                <td className="px-6 py-4 whitespace-nowrap">{appt.doctor_name}</td>
                <td className="px-6 py-4 whitespace-nowrap">{new Date(appt.appointment_datetime).toLocaleString()}</td>
                <td className="px-6 py-4 whitespace-nowrap">{appt.type}</td>
                <td className="px-6 py-4 whitespace-nowrap">{appt.status}</td>
                <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <button onClick={() => handleCancel(appt.id)} className="text-red-600 hover:text-red-900 mr-2">Cancel</button>
                  <button onClick={() => handleReschedule(appt.id)} className="text-yellow-600 hover:text-yellow-900 mr-2">Reschedule</button>
                  <button onClick={() => handleReassign(appt.id)} className="text-blue-600 hover:text-blue-900">Reassign</button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default AdminAppointmentOversight;