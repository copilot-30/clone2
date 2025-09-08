import React, { useState, useEffect } from 'react';

interface Patient {
  id: string;
  name: string;
  email: string;
  contact_number: string;
  address: string;
}

const AdminPatientManagement: React.FC = () => {
  const [patients, setPatients] = useState<Patient[]>([]); // Placeholder for patient data

  useEffect(() => {
    // Implement API call to fetch patients
    console.log('Fetching patients...');
    setPatients([
      { id: 'p1', name: 'Alice Smith', email: 'alice.smith@example.com', contact_number: '123-456-7890', address: '123 Main St' },
      { id: 'p2', name: 'Bob Johnson', email: 'bob.j@example.com', contact_number: '098-765-4321', address: '456 Oak Ave' },
    ]);
  }, []);

  return (
    <div className="p-4">
      <h1 className="text-2xl font-bold mb-4">Admin: Patient Management</h1>

      <div className="bg-white p-4 rounded shadow">
        <h2 className="text-xl font-semibold mb-4">Existing Patients</h2>
        <table className="min-w-full divide-y divide-gray-200">
          <thead className="bg-gray-50">
            <tr>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody className="bg-white divide-y divide-gray-200">
            {patients.map((patient) => (
              <tr key={patient.id}>
                <td className="px-6 py-4 whitespace-nowrap">{patient.name}</td>
                <td className="px-6 py-4 whitespace-nowrap">{patient.email}</td>
                <td className="px-6 py-4 whitespace-nowrap">{patient.contact_number}</td>
                <td className="px-6 py-4 whitespace-nowrap">{patient.address}</td>
                <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <a href="#" className="text-indigo-600 hover:text-indigo-900 mr-2">View Details</a>
                  <a href="#" className="text-red-600 hover:text-red-900">Contact</a>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default AdminPatientManagement;