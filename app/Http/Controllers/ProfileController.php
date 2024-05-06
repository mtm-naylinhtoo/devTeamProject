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
            $editableRoles = ['manager', 'leader', 'sub-leader', 'senior-developer', 'junior-developer'];
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
                'role' => 'required|in:manager,leader,sub-leader,senior-developer,junior-developer',
            ]);
    
            // Update the user's role
            $user->role = $request->input('role');
        }

        $user->save();

        return Redirect::route('profiles.edit', $user->id)->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
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
}
