<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f4f4; padding: 20px; }
        .card { background: #ffffff; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        h1 { color: #3b82f6; }
        .btn { display: inline-block; padding: 10px 20px; background: #3b82f6; color: #fff; text-decoration: none; border-radius: 10px; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Task Completed!</h1>
        <p>Hello,</p>
        <p>Good news! Your waste collection request has been marked as completed.</p>
        <p><strong>Request:</strong> {{ $wasteRequest->title }}</p>
        <p>Thank you for using EcoSmart for a cleaner environment.</p>
        <a href="{{ url('/user/requests') }}" class="btn">View My Requests</a>
    </div>
</body>
</html>
