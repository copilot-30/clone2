import React from 'react';

const DoctorEarnings: React.FC = () => {
  return (
    <div className="p-4">
      <h1 className="text-2xl font-bold mb-4">Doctor Earnings & Payouts</h1>
      <div className="bg-white p-4 rounded shadow mb-4">
        <h2 className="text-xl font-semibold mb-2">Current Balance</h2>
        <p className="text-3xl font-bold text-green-600">$X,XXX.XX</p>
        <button className="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
          Request Payout
        </button>
      </div>
      <div className="bg-white p-4 rounded shadow">
        <h2 className="text-xl font-semibold mb-2">Transaction History</h2>
        <p>Display a list of earnings and payout transactions here.</p>
      </div>
    </div>
  );
};

export default DoctorEarnings;