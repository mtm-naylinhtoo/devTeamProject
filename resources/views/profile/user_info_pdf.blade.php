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
        .rating {
            color: #555;
        }
    </style>
</head>
<body>
    <h2>User Information</h2>
    <p><strong>Name:</strong> {{ $user->name }}</p>
    <p><strong>Position:</strong> {{ ucfirst($user->role) }}</p>
    
    <h3>Feedbacks</h3>
    @if ($tasksWithFeedbacks->isNotEmpty())
        @foreach ($tasksWithFeedbacks as $task)
            <div class="card">
                <div class="card-header">
                    Task: {{ $task->task->title }}
                </div>
                @foreach ($task->feedbacks as $feedback)
                    <div class="card-body">
                        <p><strong>Feedback from:</strong> {{ $feedback->user->name }}</p>
                        <p><strong>Comment:</strong> {{ $feedback->comment }}</p>
                        <p><strong>Rating:</strong> <span class="rating">{{ $feedback->rating }}</span>/5</p>
                    </div>
                    <hr style="border-top: 1px dashed #ccc; margin: 10px 0;">
                @endforeach
            </div>
        @endforeach
    @else
        <p>No tasks with feedbacks found.</p>
    @endif
</body>
</html>
