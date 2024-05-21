<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Carbon;
use Spatie\LaravelPdf\Facades\Pdf;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Http;


class ProfileController extends Controller
{
    public function index()
    {
        $profiles = User::all();

        return view('profile.index', compact('profiles'));
    }

    public function show(Request $request, User $profile)
    {
        $currentMonth = $request->input('month', now()->month);
        $currentYear = $request->input('year', now()->year);

        $task_details = $profile->sortedTasks($currentYear, $currentMonth);
        $leaders = User::whereIn('role', ['leader', 'sub-leader'])->get(['id', 'name']);
        $task_details->each(function ($detail) {
            $detail->feedback_given = $detail->feedbacks->contains(function ($feedback) {
                return $feedback->user_id === auth()->id();
            });
        });
    
        return view('profile.show', compact('profile', 'task_details', 'leaders'));
    }

    public function edit(User $profile)
    {
        $user = $profile;

        if (!auth()->user()->isAdmin() && auth()->user()->id !== $profile->id) {
            return redirect()->route('profiles.index')->with('error', 'You are not authorized to edit this profile.');
        }
        if (auth()->user()->isAdmin()) {
            $editableRoles = ['manager', 'bse', 'leader', 'sub-leader', 'senior-developer', 'junior-developer'];
        } else {
            $editableRoles = [];
        }

        return view('profile.edit', [
            'user' => $user,
            'editableRoles' => $editableRoles,
        ]);
    }

    public function update(ProfileUpdateRequest $request, $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        // Check if the email being updated is already taken by another user
        $existingUser = User::where('email', $request->input('email'))->first();
        if ($existingUser && $existingUser->id !== $user->id) {
            throw ValidationException::withMessages([
                'email' => ['The email address is already taken by another user.'],
            ])->redirectTo(route('profiles.edit', $user->id));
        }
        // Fill the user model with validated data
        $user->fill($request->validated());
    
        // Check if the email has been updated and reset email verification
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if (auth()->user()->isAdmin() && $request->filled('role')) {
            // Validate the role field
            $request->validate([
                'role' => 'required|in:manager,bse,leader,sub-leader,senior-developer,junior-developer',
            ]);
    
            // Update the user's role
            $user->role = $request->input('role');
        }

        $user->save();

        return redirect()->route('profiles.show', $user)->with('success', 'Task updated successfully.');

    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function assignLeader(Request $request, $userId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->assigned_to = $request->assigned_to;
            $user->save();
            return response()->json(['message' => 'Leader assigned successfully']);
        }

        return response()->json(['message' => 'User not found'], 404);
    }
    
    public function generatePdf($profileId)
    {
        $user = User::findOrFail($profileId);
        $currentYear = now()->year;
        $tasksWithFeedbacks = $user->tasks()
            ->whereHas('feedbacks')
            ->whereYear('created_at', $currentYear)
            ->with('feedbacks')
            ->get();
    
        $feedbackText = "You are a project manager reviewing a team member's completed tasks, including ratings and comments. 
            Your goal is to summarize the feedback and provide advice for the team member. 
            Write a single paragraph between 500 to 1000 words. Do not format it as an email or refer to yourself. 
            Focus on addressing the team member directly and offering constructive advice. 
            Do not list individual tasks or their specific feedback; instead, provide an overall assessment based on all tasks and ratings. 
            You can use <br> to separate ideas for clarity. The summary will be included in a PDF and given to the team member.
            Don't use ** ** or ## for header title, in fact don't use header titles at all.
            Here is the data: Member: {$user->name}, Role: {$user->role} (BSE means Bridge System Engineer).\n\n";
        foreach ($tasksWithFeedbacks as $task) {
            foreach ($task->feedbacks as $feedback) {
                $feedbackText .= "Task: {$task->task->title}\n";
                $feedbackText .= "Rating: {$feedback->rating}\n";
                $feedbackText .= "Comment: {$feedback->comment}\n\n";
            }
        }
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer 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',
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $feedbackText
                    ]
                ],
                'model' => 'gemma-7b-it',
                'temperature' => 1,
                'max_tokens' => 1024,
                'top_p' => 1,
                'stop' => null
            ]);
    
            $summary = json_decode($response->getBody(), true)['choices'][0]['message']["content"];
            $html = view('profile.user_info_pdf', [
                'user' => $user,
                'tasksWithFeedbacks' => $tasksWithFeedbacks,
                'summary' => $summary
            ])->render();
    
            $pdfContent = Browsershot::html($html)
                ->format('A4')
                ->pdf();
    
            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', "attachment; filename=\"{$user->name}-review.pdf\"");
        } catch (\Exception $e) {
            return redirect()->route('profiles.show', $profileId)->with('error', 'Failed to generate PDF: Try after after a few seconds.');
        }
    }
    
}
