import React, { useState } from 'react';

interface LabRequestFormProps {
  consultationId: string;
}

const LabRequestForm: React.FC<LabRequestFormProps> = ({ consultationId }) => {
  const [testName, setTestName] = useState('');
  const [instructions, setInstructions] = useState('');

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    // Implement API call to submit lab request
    console.log('Submitting lab request for consultation:', consultationId, {
      testName,
      instructions,
    });
    // Clear form after submission
    setTestName('');
    setInstructions('');
  };

  return (
    <div>
      <h2 className="text-xl font-semibold mb-2">Request Lab Test</h2>
      <form onSubmit={handleSubmit} className="space-y-4">
        <div>
          <label htmlFor="testName" className="block text-sm font-medium text-gray-700">
            Test Name
          </label>
          <input
            type="text"
            id="testName"
            className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
            value={testName}
            onChange={(e) => setTestName(e.target.value)}
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
          className="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded"
        >
          Add Lab Request
        </button>
      </form>
    </div>
  );
};

export default LabRequestForm;