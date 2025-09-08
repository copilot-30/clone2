import React, { useState } from 'react';

interface PrescriptionFormProps {
  consultationId: string;
}

const PrescriptionForm: React.FC<PrescriptionFormProps> = ({ consultationId }) => {
  const [medicationName, setMedicationName] = useState('');
  const [dosage, setDosage] = useState('');
  const [frequency, setFrequency] = useState('');
  const [instructions, setInstructions] = useState('');

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    // Implement API call to submit prescription
    console.log('Submitting prescription for consultation:', consultationId, {
      medicationName,
      dosage,
      frequency,
      instructions,
    });
    // Clear form after submission
    setMedicationName('');
    setDosage('');
    setFrequency('');
    setInstructions('');
  };

  return (
    <div>
      <h2 className="text-xl font-semibold mb-2">Prescribe Medication</h2>
      <form onSubmit={handleSubmit} className="space-y-4">
        <div>
          <label htmlFor="medicationName" className="block text-sm font-medium text-gray-700">
            Medication Name
          </label>
          <input
            type="text"
            id="medicationName"
            className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
            value={medicationName}
            onChange={(e) => setMedicationName(e.target.value)}
            required
          />
        </div>
        <div>
          <label htmlFor="dosage" className="block text-sm font-medium text-gray-700">
            Dosage
          </label>
          <input
            type="text"
            id="dosage"
            className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
            value={dosage}
            onChange={(e) => setDosage(e.target.value)}
            required
          />
        </div>
        <div>
          <label htmlFor="frequency" className="block text-sm font-medium text-gray-700">
            Frequency
          </label>
          <input
            type="text"
            id="frequency"
            className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
            value={frequency}
            onChange={(e) => setFrequency(e.target.value)}
            required
          />
        </div>
        <div>
          <label htmlFor="instructions" className="block text-sm font-medium text-gray-700">
            Instructions
          </label>
          <textarea
            id="instructions"
            className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
            rows={3}
            value={instructions}
            onChange={(e) => setInstructions(e.target.value)}
          ></textarea>
        </div>
        <button
          type="submit"
          className="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
        >
          Add Prescription
        </button>
      </form>
    </div>
  );
};

export default PrescriptionForm;