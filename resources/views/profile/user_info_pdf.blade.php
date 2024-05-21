<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            padding: 20px;
        }
        .card {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 8px;
        }
        .card-header {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .card-body {
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h2>{{ $user->name }}</h2>
    <p><strong>Position:</strong> {{ ucfirst($user->role) }}</p>

    <div class="card">
        <div class="card-header">
            <h2>Summary</h2>
        </div>
            <div class="card-body">
                <p>{{ $summary }}</p>
            </div>
            <hr style="border-top: 1px dashed #ccc; margin: 10px 0;">
            <p style="font-size:10px">Powered by devTeam MTM</p>
    </div>
</body>
</html>
