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

    public function show(User $profile)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $task_details = $profile->tasks()->whereHas('task', function ($query) use ($currentMonth, $currentYear) {
            $query->whereYear('due_date', $currentYear)->whereMonth('due_date', $currentMonth);
        })->get();

        $task_details->each(function ($detail) {
            // Filter the feedbacks relation to check if there's any feedback from the authenticated user
            $detail->feedback_given = $detail->feedbacks->contains(function ($feedback) {
                return $feedback->user_id === auth()->id();
            });
        });

        return view('profile.show', compact('profile', 'task_details'));
    }

    public function edit(User $profile)
    {
        $user = $profile;

        // Check if the authenticated user has permission to edit this profile
        if (!auth()->user()->isAdmin() && auth()->user()->id !== $profile->id) {
            // Redirect back with an error message
            return redirect()->route('profiles.index')->with('error', 'You are not authorized to edit this profile.');
        }
        // Check if the user's role allows editing
        if (auth()->user()->isAdmin()) {
            // User has permission to edit the role
            $editableRoles = ['manager', 'bse', 'leader', 'sub-leader', 'senior-developer', 'junior-developer'];
        } else {
            // User does not have permission to edit the role
            $editableRoles = [];
        }

        return view('profile.edit', [
            'user' => $user,
            'editableRoles' => $editableRoles,
        ]);
    }


    /**
     * Update the user's profile information.
     */
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

        return Redirect::route('profiles.edit', $user->id)->with('status', 'profile-updated');
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

    public function generatePdf($profileId)
    {
        $user = User::findOrFail($profileId);
        $tasksWithFeedbacks = $user->tasks()
            ->whereHas('feedbacks')
            ->with('feedbacks')
            ->get();

        $feedbackText = "you are a manager, u get a member task complete information for summarization/evaluation. 
        say what the manager would say and nothing else. the data is= Member: {$user->name} and their role is {$user->role}, BSE means Bridge System Engineer\n\n";
        foreach ($tasksWithFeedbacks as $task) {
            foreach ($task->feedbacks as $feedback) {
                $feedbackText .= "Task: {$task->title}\n";
                $feedbackText .= "Rating: {$feedback->rating}\n";
                $feedbackText .= "Comment: {$feedback->comment}\n\n";
            }
        }

        // Make a POST request to OpenAI for summarization
        $response = Http::withHeaders([
            'Authorization' => 'Bearer key',
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/engines/gpt-3.5-turbo-instruct/completions', [
            'prompt' => $feedbackText,
            'max_tokens' => 300, // Adjust as needed
        ]);
        // Extract the summary from the API response
        $summary = json_decode($response->getBody(), true)['choices'][0]['text'];
        
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
    }
}
