<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test AJAX</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        button { padding: 10px 20px; margin: 10px; background: #007cba; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #005a87; }
        .result { margin: 20px 0; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        .success { background: #d4edda; border-color: #c3e6cb; }
        .error { background: #f8d7da; border-color: #f5c6cb; }
    </style>
</head>
<body>
    <h1>Test Faculty Approval AJAX</h1>
    
    <button onclick="testApproval()">Test Faculty Approval</button>
    <button onclick="testSimpleApproval()">Test Simple Approval</button>
    
    <div id="result" class="result" style="display: none;"></div>

    <script>
        function showResult(message, isSuccess = true) {
            const result = document.getElementById('result');
            result.textContent = message;
            result.className = 'result ' + (isSuccess ? 'success' : 'error');
            result.style.display = 'block';
        }

        function testApproval() {
            showResult('Testing faculty approval...', true);
            
            fetch('/faculty/thesis/reviews/1', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    status: 'approved',
                    review_comments: 'Test approval from AJAX test page.'
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    showResult('Faculty approval test successful!', true);
                } else {
                    showResult('Faculty approval test failed: ' + (data.message || 'Unknown error'), false);
                }
            })
            .catch(error => {
                console.error('Error details:', error);
                showResult('Faculty approval test error: ' + error.message, false);
            });
        }

        function testSimpleApproval() {
            showResult('Testing simple approval...', true);
            
            fetch('/test-faculty-approval', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({})
            })
            .then(response => {
                console.log('Simple response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Simple response data:', data);
                if (data.success) {
                    showResult('Simple approval test successful!', true);
                } else {
                    showResult('Simple approval test failed: ' + (data.message || 'Unknown error'), false);
                }
            })
            .catch(error => {
                console.error('Simple error details:', error);
                showResult('Simple approval test error: ' + error.message, false);
            });
        }
    </script>
</body>
</html>

