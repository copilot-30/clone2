import React, { useState } from 'react';

interface Doctor {
  id: string;
  name: string;
  email: string;
  specialty: {
    name: string;
  };
  medical_license_number: string;
}

const AdminDoctorManagement: React.FC = () => {
  const [doctors, setDoctors] = useState<Doctor[]>([]); // Placeholder for doctor data
  const [newDoctorData, setNewDoctorData] = useState({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    specialty_id: '',
    medical_license_number: '',
    contact_number: '',
    address: '',
    bio: '',
  });

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
    setNewDoctorData({ ...newDoctorData, [e.target.name]: e.target.value });
  };

  const handleCreateDoctor = (e: React.FormEvent) => {
    e.preventDefault();
    // Implement API call to create new doctor
    console.log('Creating new doctor:', newDoctorData);
    // After successful creation, clear form and refresh doctor list
  };

  // Fetch doctors on component mount (placeholder)
  React.useEffect(() => {
    // Implement API call to fetch doctors
    console.log('Fetching doctors...');
    setDoctors([
      { id: '1', name: 'Dr. John Doe', email: 'john.doe@example.com', specialty: { name: 'Cardiology' }, medical_license_number: 'MD12345' },
      { id: '2', name: 'Dr. Jane Smith', email: 'jane.smith@example.com', specialty: { name: 'Pediatrics' }, medical_license_number: 'MD67890' },
    ]);
  }, []);

  return (
    <div className="p-4">
      <h1 className="text-2xl font-bold mb-4">Admin: Doctor Management</h1>

      <div className="bg-white p-4 rounded shadow mb-6">
        <h2 className="text-xl font-semibold mb-4">Create New Doctor Account</h2>
        <form onSubmit={handleCreateDoctor} className="space-y-4">
          {/* Input fields for new doctor data */}
          <input type="text" name="name" placeholder="Name" value={newDoctorData.name} onChange={handleInputChange} className="block w-full border p-2 rounded" />
          <input type="email" name="email" placeholder="Email" value={newDoctorData.email} onChange={handleInputChange} className="block w-full border p-2 rounded" />
          <input type="password" name="password" placeholder="Password" value={newDoctorData.password} onChange={handleInputChange} className="block w-full border p-2 rounded" />
          <input type="password" name="password_confirmation" placeholder="Confirm Password" value={newDoctorData.password_confirmation} onChange={handleInputChange} className="block w-full border p-2 rounded" />
          <select name="specialty_id" value={newDoctorData.specialty_id} onChange={handleInputChange} className="block w-full border p-2 rounded">
            <option value="">Select Specialty</option>
            {/* This would be dynamically populated */}
            <option value="uuid1">Cardiology</option>
            <option value="uuid2">Pediatrics</option>
          </select>
          <input type="text" name="medical_license_number" placeholder="Medical License Number" value={newDoctorData.medical_license_number} onChange={handleInputChange} className="block w-full border p-2 rounded" />
          <input type="text" name="contact_number" placeholder="Contact Number" value={newDoctorData.contact_number} onChange={handleInputChange} className="block w-full border p-2 rounded" />
          <input type="text" name="address" placeholder="Address" value={newDoctorData.address} onChange={handleInputChange} className="block w-full border p-2 rounded" />
          <textarea name="bio" placeholder="Bio" value={newDoctorData.bio} onChange={handleInputChange} className="block w-full border p-2 rounded"></textarea>
          <button type="submit" className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Create Doctor
          </button>
        </form>
      </div>

      <div className="bg-white p-4 rounded shadow">
        <h2 className="text-xl font-semibold mb-4">Existing Doctors</h2>
        <table className="min-w-full divide-y divide-gray-200">
          <thead className="bg-gray-50">
            <tr>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Specialty</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">License</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody className="bg-white divide-y divide-gray-200">
            {doctors.map((doctor) => (
              <tr key={doctor.id}>
                <td className="px-6 py-4 whitespace-nowrap">{doctor.name}</td>
                <td className="px-6 py-4 whitespace-nowrap">{doctor.email}</td>
                <td className="px-6 py-4 whitespace-nowrap">{doctor.specialty.name}</td>
                <td className="px-6 py-4 whitespace-nowrap">{doctor.medical_license_number}</td>
                <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <a href="#" className="text-indigo-600 hover:text-indigo-900 mr-2">Edit</a>
                  <a href="#" className="text-red-600 hover:text-red-900">Delete</a>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default AdminDoctorManagement;