import React, { useState, useEffect } from 'react';

interface AuditLog {
  id: string;
  user_id: string;
  user_email: string; // Assuming we'd eager load user email
  event_type: string;
  description: string;
  ip_address: string;
  user_agent: string;
  created_at: string;
}

const AdminAuditLogViewer: React.FC = () => {
  const [auditLogs, setAuditLogs] = useState<AuditLog[]>([]); // Placeholder for audit log data

  useEffect(() => {
    // Implement API call to fetch audit logs
    console.log('Fetching audit logs...');
    setAuditLogs([
      {
        id: 'log1',
        user_id: 'user1',
        user_email: 'admin@example.com',
        event_type: 'login_success',
        description: '{"user_id":"user1","email":"admin@example.com","user_type":"admin"}',
        ip_address: '192.168.1.1',
        user_agent: 'Mozilla/5.0',
        created_at: '2025-09-06T08:00:00Z',
      },
      {
        id: 'log2',
        user_id: 'user2',
        user_email: 'doctor@example.com',
        event_type: 'doctor_created',
        description: '{"doctor_id":"doc1","user_id":"user2","email":"doctor@example.com","specialty":"Cardiology"}',
        ip_address: '192.168.1.2',
        user_agent: 'Postman',
        created_at: '2025-09-06T08:05:00Z',
      },
    ]);
  }, []);

  return (
    <div className="p-4">
      <h1 className="text-2xl font-bold mb-4">Admin: Audit Log Viewer</h1>

      <div className="bg-white p-4 rounded shadow">
        <h2 className="text-xl font-semibold mb-4">System Audit Logs</h2>
        <table className="min-w-full divide-y divide-gray-200">
          <thead className="bg-gray-50">
            <tr>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Email</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Type</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
              <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Agent</th>
            </tr>
          </thead>
          <tbody className="bg-white divide-y divide-gray-200">
            {auditLogs.map((log) => (
              <tr key={log.id}>
                <td className="px-6 py-4 whitespace-nowrap">{new Date(log.created_at).toLocaleString()}</td>
                <td className="px-6 py-4 whitespace-nowrap">{log.user_email}</td>
                <td className="px-6 py-4 whitespace-nowrap">{log.event_type}</td>
                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{log.description}</td>
                <td className="px-6 py-4 whitespace-nowrap">{log.ip_address}</td>
                <td className="px-6 py-4 whitespace-nowrap">{log.user_agent}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
};

export default AdminAuditLogViewer;