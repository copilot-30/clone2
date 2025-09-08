import React from 'react';

interface Patient {
  id: string;
  name: string;
  condition: string;
}

const AssignedPatients: React.FC = () => {
  const patients: Patient[] = [
    {
      id: '1',
      name: 'Juan Dela Cruz',
      condition: 'Hypertension'
    },
    {
      id: '2',
      name: 'Juan Dela Cruz',
      condition: 'Diabetes'
    },
    {
      id: '3',
      name: 'Maria Clara',
      condition: 'Amnesia'
    },
    {
      id: '4',
      name: 'Juan Dela Cruz',
      condition: 'Hypertension'
    },
    {
      id: '5',
      name: 'Susan Ramirez',
      condition: 'Diabetes'
    }
  ];

  return (
    <div className="bg-white rounded-lg shadow-sm border border-gray-200">
      <div className="bg-emerald-600 text-white px-6 py-4 rounded-t-lg">
        <h2 className="text-lg font-semibold">Assigned Patients</h2>
      </div>
      
      <div className="overflow-x-auto">
        <table className="w-full">
          <thead className="bg-gray-50">
            <tr>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Patient
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Condition
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Action
              </th>
            </tr>
          </thead>
          <tbody className="divide-y divide-gray-200">
            {patients.map((patient) => (
              <tr key={patient.id} className="hover:bg-gray-50">
                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  {patient.name}
                </td>
                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                  {patient.condition}
                </td>
                <td className="px-6 py-4 whitespace-nowrap">
                  <button className="text-emerald-600 hover:text-emerald-800 text-sm font-medium transition-colors">
                    View
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default AssignedPatients;