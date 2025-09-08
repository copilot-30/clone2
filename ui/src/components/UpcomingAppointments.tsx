import React from 'react';

interface Appointment {
  id: string;
  dateTime: string;
  patient: string;
  mode: string;
  status: 'Confirmed' | 'Pending' | 'Cancelled';
}

const UpcomingAppointments: React.FC = () => {
  const appointments: Appointment[] = [
    {
      id: '1',
      dateTime: 'Mar 10, 2025 / 9:00 AM',
      patient: 'Juan Dela Cruz',
      mode: 'Online',
      status: 'Confirmed'
    },
    {
      id: '2',
      dateTime: 'Mar 10, 2025 / 10:00 AM',
      patient: 'Juan Dela Cruz',
      mode: 'Face to Face',
      status: 'Confirmed'
    },
    {
      id: '3',
      dateTime: 'Mar 10, 2025 / 11:00 AM',
      patient: 'Maria Clara',
      mode: 'Online',
      status: 'Cancelled'
    },
    {
      id: '4',
      dateTime: 'Mar 10, 2025 / 2:00 PM',
      patient: 'Juan Dela Cruz',
      mode: 'Face to Face',
      status: 'Pending'
    },
    {
      id: '5',
      dateTime: 'Mar 11, 2025 / 10:00 AM',
      patient: 'Susan Ramirez',
      mode: 'Online',
      status: 'Confirmed'
    }
  ];

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'Confirmed':
        return 'text-emerald-600 bg-emerald-50';
      case 'Pending':
        return 'text-orange-600 bg-orange-50';
      case 'Cancelled':
        return 'text-red-600 bg-red-50';
      default:
        return 'text-gray-600 bg-gray-50';
    }
  };

  return (
    <div className="bg-white rounded-lg shadow-sm border border-gray-200">
      <div className="bg-emerald-600 text-white px-6 py-4 rounded-t-lg flex justify-between items-center">
        <h2 className="text-lg font-semibold">Upcoming Appointments</h2>
        <button className="text-white hover:text-emerald-100 transition-colors">
          View
        </button>
      </div>
      
      <div className="overflow-x-auto">
        <table className="w-full">
          <thead className="bg-gray-50">
            <tr>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Date / Time
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Patient
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Mode
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Status
              </th>
            </tr>
          </thead>
          <tbody className="divide-y divide-gray-200">
            {appointments.map((appointment) => (
              <tr key={appointment.id} className="hover:bg-gray-50">
                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {appointment.dateTime}
                </td>
                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  {appointment.patient}
                </td>
                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                  {appointment.mode}
                </td>
                <td className="px-6 py-4 whitespace-nowrap">
                  <span className={`inline-flex px-2 py-1 text-xs font-semibold rounded-full ${getStatusColor(appointment.status)}`}>
                    {appointment.status}
                  </span>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default UpcomingAppointments;