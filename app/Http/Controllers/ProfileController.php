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
        $tasksWithFeedbacks = $user->tasks()
            ->whereHas('feedbacks')
            ->with('feedbacks')
            ->get();

        $feedbackText = "u are a project manager, u get a member completed task informations with rating and comment for u to judge. 
        u need to provide the summarization and advise for the member.
        also dont generate it like ur writing an email. this is not an email. just generate a paragraph with between 500 to 1000 words.
        no need to add best regard or sincerely dont refer to urself. its about them not u. also dont need to mention the individual tasks.
        you dont need to give summarization for each task, read all the tasks, their feedbacks and generate what you think, as a project manager.
        dont use syntax like 'as a project manger'. dont refer to urself at all. you dont need to mention their name also.
        dont need to give the rating urself again, u only need to use the ratings provided for ur summary.
        you summary will be added in pdf. the pdf will be given to the member themself. so you need to address them directly.
        the data is= Member: {$user->name} and their role is {$user->role}, BSE means Bridge System Engineer\n\n";
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

            return response()->streamDownload(function () use ($pdfContent) {
                echo $pdfContent;
            }, "{$user->name}-review.pdf", ['Content-Type' => 'application/pdf']);
        } catch (\Exception $e) {
            return redirect()->route('profiles.show', $profileId)->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }
}
