import React, { useState } from 'react';
import PrescriptionForm from './PrescriptionForm';
import LabRequestForm from './LabRequestForm';

interface ConsultationInterfaceProps {
  appointmentId: string;
  googleMeetLink?: string; // Optional Google Meet link
}

const ConsultationInterface: React.FC<ConsultationInterfaceProps> = ({ appointmentId, googleMeetLink }) => {
  const [consultationNotes, setConsultationNotes] = useState('');

  const handleSaveNotes = () => {
    // Implement API call to save consultation notes
    console.log('Saving consultation notes:', consultationNotes);
  };

  return (
    <div className="p-4">
      <h1 className="text-2xl font-bold mb-4">Consultation Interface for Appointment: {appointmentId}</h1>

      {googleMeetLink && (
        <div className="mb-4">
          <h2 className="text-xl font-semibold mb-2">Google Meet</h2>
          <a
            href={googleMeetLink}
            target="_blank"
            rel="noopener noreferrer"
            className="text-blue-600 hover:underline"
          >
            Join Google Meet Call
          </a>
          {/* Placeholder for embedded video/Meet interface if applicable */}
          <div className="bg-gray-200 h-48 flex items-center justify-center mt-2 rounded">
            <p>Google Meet video will be embedded here.</p>
          </div>
        </div>
      )}

      <div className="mb-4">
        <h2 className="text-xl font-semibold mb-2">Consultation Notes</h2>
        <textarea
          className="w-full p-2 border rounded"
          rows={6}
          value={consultationNotes}
          onChange={(e) => setConsultationNotes(e.target.value)}
          placeholder="Enter consultation notes here..."
        ></textarea>
        <button
          onClick={handleSaveNotes}
          className="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        >
          Save Notes
        </button>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div className="bg-white p-4 rounded shadow">
          <PrescriptionForm consultationId={appointmentId} />
        </div>
        <div className="bg-white p-4 rounded shadow">
          <LabRequestForm consultationId={appointmentId} />
        </div>
      </div>
    </div>
  );
};

export default ConsultationInterface;