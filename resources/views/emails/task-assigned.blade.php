<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f4f4; padding: 20px; }
        .card { background: #ffffff; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        h1 { color: #10b981; }
        .btn { display: inline-block; padding: 10px 20px; background: #10b981; color: #fff; text-decoration: none; border-radius: 10px; }
    </style>
</head>
<body>
    <div class="card">
        <h1>New Task Assigned!</h1>
        <p>Hello,</p>
        <p>A new waste collection request has been assigned to you.</p>
        <p><strong>Request:</strong> {{ $wasteRequest->title }}</p>
        <p><strong>Address:</strong> {{ $wasteRequest->address }}</p>
        <p>Please log in to your dashboard to view the location and details.</p>
        <a href="{{ url('/worker/tasks') }}" class="btn">View Tasks</a>
    </div>
</body>
</html>
