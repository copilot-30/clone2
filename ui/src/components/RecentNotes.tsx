import React from 'react';

interface Note {
  id: string;
  date: string;
  patient: string;
  notes: string;
}

const RecentNotes: React.FC = () => {
  const notes: Note[] = [
    {
      id: '1',
      date: 'Mar 10, 2025',
      patient: 'Juan Dela Cruz',
      notes: 'quis nostrud exercitation ullamco laboris nisl ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit'
    },
    {
      id: '2',
      date: 'Mar 10, 2025',
      patient: 'Juan Dela Cruz',
      notes: 'quis nostrud exercitation ullamco laboris nisl ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate'
    },
    {
      id: '3',
      date: 'Mar 10, 2025',
      patient: 'Maria Clara',
      notes: 'Duis aute irure dolor in reprehenderit'
    },
    {
      id: '4',
      date: 'Mar 10, 2025',
      patient: 'Juan Dela Cruz',
      notes: 'in voluptate velit esse cillum dolore eu fugiat nulla pariatur'
    },
    {
      id: '5',
      date: 'Mar 10, 2025',
      patient: 'Susan Ramirez',
      notes: 'in voluptate velit esse cillum dolore eu fugiat nulla pariatur'
    }
  ];

  return (
    <div className="bg-white rounded-lg shadow-sm border border-gray-200">
      <div className="bg-emerald-600 text-white px-6 py-4 rounded-t-lg flex justify-between items-center">
        <h2 className="text-lg font-semibold">Recent Notes</h2>
        <button className="text-white hover:text-emerald-100 transition-colors">
          Add Notes
        </button>
      </div>
      
      <div className="overflow-x-auto">
        <table className="w-full">
          <thead className="bg-gray-50">
            <tr>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Date
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Patient
              </th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Notes
              </th>
            </tr>
          </thead>
          <tbody className="divide-y divide-gray-200">
            {notes.map((note) => (
              <tr key={note.id} className="hover:bg-gray-50">
                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {note.date}
                </td>
                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  {note.patient}
                </td>
                <td className="px-6 py-4 text-sm text-gray-600 max-w-md">
                  <div className="truncate">
                    {note.notes}
                  </div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default RecentNotes;