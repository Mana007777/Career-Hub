<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Users - Quick Login</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #0f172a;
            color: #fff;
            padding: 2rem;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        h1 {
            margin-bottom: 2rem;
            color: #60a5fa;
        }
        .warning {
            background: #fbbf24;
            color: #000;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
        }
        .user-list {
            display: grid;
            gap: 1rem;
        }
        .user-card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 0.5rem;
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .user-info h3 {
            color: #60a5fa;
            margin-bottom: 0.5rem;
        }
        .user-info p {
            color: #94a3b8;
            font-size: 0.875rem;
        }
        .login-btn {
            background: #3b82f6;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background 0.2s;
        }
        .login-btn:hover {
            background: #2563eb;
        }
        .login-btn.new-window {
            background: #10b981;
        }
        .login-btn.new-window:hover {
            background: #059669;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 Test Users - Quick Login</h1>
        
        <div class="warning">
            <strong>⚠️ Development Only:</strong> This page is only available in local environment. 
            Use different browsers or incognito windows to test with multiple users simultaneously.
        </div>

        <div class="user-list">
            @foreach($users as $user)
                <div class="user-card">
                    <div class="user-info">
                        <h3>{{ $user->name }}</h3>
                        <p>ID: {{ $user->id }} | Email: {{ $user->email }} | Username: {{ $user->username ?? 'N/A' }}</p>
                    </div>
                    <div style="display: flex; gap: 0.5rem;">
                        <a href="{{ route('test.login-as', $user->id) }}" class="login-btn">
                            Login Here
                        </a>
                        <a href="{{ route('test.login-as', $user->id) }}" target="_blank" class="login-btn new-window">
                            New Window
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
