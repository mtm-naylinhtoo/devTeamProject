<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'task_detail_id' => 'required|exists:task_details,id',
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:255',
        ]);

        // Create the feedback
        $feedback = Feedback::create([
            'task_detail_id' => $request->task_detail_id,
            'user_id' => $request->user_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Optionally, you can return a response or redirect
        return response()->json(['message' => 'Feedback submitted successfully'], 200);
    }

    public function show(Feedback $feedback)
    {
        return view('feedbacks.show', compact('feedback'));
    }

    public function edit(Feedback $feedback)
    {
        if ($feedback->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'You are not authorized to edit this feedback.');
        }

        return view('feedbacks.edit', compact('feedback'));
    }

    public function update(Request $request, Feedback $feedback)
    {
        // Validate the request data
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);
        // Check if the authenticated user is authorized to update this feedback
        if ((int)$request->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'You are not authorized to update this feedback.');
        }

        // Update the feedback
        $feedback->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Optionally, you can return a response or redirect
        return redirect()->route('feedbacks.show', $feedback->id)->with('success', 'Feedback updated successfully.');
    }



}
