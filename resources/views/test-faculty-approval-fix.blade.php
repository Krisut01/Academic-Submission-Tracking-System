<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Faculty Approval Fix</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-section { margin: 20px 0; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
        .success { background: #d4edda; border-color: #c3e6cb; }
        .error { background: #f8d7da; border-color: #f5c6cb; }
        .info { background: #d1ecf1; border-color: #bee5eb; }
        .approval-item { padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>🔧 Test Faculty Approval Fix</h1>
    
    <div class="test-section info">
        <h3>📋 Issue Fixed</h3>
        <p><strong>Problem:</strong> "Attempt to read property 'name' on string" error in student dashboard</p>
        <p><strong>Root Cause:</strong> Missing null checks when accessing faculty relationships</p>
        <p><strong>Solution:</strong> Added proper null checking with null coalescing operator (??)</p>
    </div>

    <div class="test-section">
        <h3>🧪 Test Document Approval Status</h3>
        <p>Testing the fixed approval status display:</p>
        <div id="testResults">Loading...</div>
    </div>

    <div class="test-section">
        <h3>📊 Individual Faculty Approvals Test</h3>
        <div id="individualApprovals">Loading...</div>
    </div>

    <script>
        // Test the fixed approval status
        function testApprovalStatus() {
            const resultsDiv = document.getElementById('testResults');
            
            // Test with a sample document ID (you can change this)
            const documentId = 4; // Based on the error message showing Document ID: 4
            
            fetch(`/student/thesis/${documentId}/individual-approval-status`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        resultsDiv.innerHTML = `
                            <div class="success">
                                <h4>✅ Individual Approval Status Loaded Successfully</h4>
                                <p><strong>Overall Status:</strong> ${data.approvalStatus.overall_status}</p>
                                <p><strong>Completion:</strong> ${data.approvalStatus.completion_percentage}%</p>
                                <p><strong>Total Approvals:</strong> ${data.approvalStatus.total_approvals}</p>
                                <p><strong>Approved Count:</strong> ${data.approvalStatus.approved_count}</p>
                            </div>
                        `;
                        
                        // Display individual approvals
                        displayIndividualApprovals(data.approvalStatus.individual_approvals);
                    } else {
                        resultsDiv.innerHTML = `
                            <div class="error">
                                <h4>❌ Error Loading Approval Status</h4>
                                <p>${data.message}</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    resultsDiv.innerHTML = `
                        <div class="error">
                            <h4>❌ Network Error</h4>
                            <p>Error: ${error.message}</p>
                        </div>
                    `;
                });
        }

        function displayIndividualApprovals(approvals) {
            const approvalsDiv = document.getElementById('individualApprovals');
            
            if (!approvals || approvals.length === 0) {
                approvalsDiv.innerHTML = '<p>No individual approvals found.</p>';
                return;
            }
            
            let html = '<h4>👥 Individual Faculty Approvals:</h4>';
            approvals.forEach(approval => {
                const statusColor = approval.status === 'approved' ? 'green' : 
                                  approval.status === 'returned_for_revision' ? 'red' : 'yellow';
                
                html += `
                    <div class="approval-item">
                        <strong>${approval.faculty_name}</strong> (${approval.faculty_role}) - 
                        <span style="color: ${statusColor}">${approval.status.toUpperCase()}</span>
                        ${approval.comments ? `<br><em>Comments: ${approval.comments}</em>` : ''}
                        ${approval.approved_at ? `<br><small>Approved: ${new Date(approval.approved_at).toLocaleString()}</small>` : ''}
                    </div>
                `;
            });
            
            approvalsDiv.innerHTML = html;
        }

        // Initialize test
        document.addEventListener('DOMContentLoaded', function() {
            testApprovalStatus();
        });
    </script>
</body>
</html>

