<!DOCTYPE html>
<html>
<head>
    <title>Debug Session</title>
</head>
<body>
    <h1>Session Debug</h1>
    
    <h2>Auth Status:</h2>
    <p>Logged in: {{ Auth::check() ? 'YES' : 'NO' }}</p>
    @if(Auth::check())
        <p>User: {{ Auth::user()->name }}</p>
        <p>Email: {{ Auth::user()->email }}</p>
        <p>Type: {{ Auth::user()->user_type }}</p>
    @endif
    
    <h2>API Token:</h2>
    <p>Token exists: {{ session('api_token') ? 'YES' : 'NO' }}</p>
    @if(session('api_token'))
        <p>Token: {{ Str::limit(session('api_token'), 50) }}...</p>
    @endif
    
    <h2>Test API Call:</h2>
    <button onclick="testAPI()">Test /api/users</button>
    <div id="result"></div>
    
    <script>
        async function testAPI() {
            const token = '{{ session('api_token') }}';
            const resultDiv = document.getElementById('result');
            
            try {
                const response = await fetch('/api/users', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json',
                    }
                });
                
                const data = await response.json();
                resultDiv.innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
            } catch (error) {
                resultDiv.innerHTML = '<pre style="color: red;">' + error.message + '</pre>';
            }
        }
    </script>
</body>
</html>
