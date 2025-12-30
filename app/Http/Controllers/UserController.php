<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $query = User::query();
        $user = Auth::user();

        if ($user->hasRole('organizer')) {
            // Organizer sees ONLY their own created users (scanners/staff)
            // They DO NOT see attendees or other organizers
            $query->where('parent_id', $user->id);
        } elseif ($user->hasRole('admin')) {
            // Admin sees all, eager load parent for "Parent Account" viewing
            $query->with('parent');
        } else {
            // Attendees/Scanners generally shouldn't reach here due to middleware
            // but if they do, show nothing or 403
            abort(403, 'Unauthorized access to user management.');
        }

        $users = $query->latest()->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        if (!Auth::user()->hasAnyRole(['admin', 'organizer'])) {
            abort(403);
        }

        return view('users.create');
    }

    public function store(Request $request)
    {
        $currentUser = Auth::user();

        if (!$currentUser->hasAnyRole(['admin', 'organizer'])) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string'],
        ]);

        // Role Validation
        $role = $request->role;
        if ($currentUser->hasRole('organizer')) {
            if (!in_array($role, ['scanner', 'staff'])) {
                return back()->withInput()->withErrors(['role' => 'Organizers can only create Scanners or Staff users.']);
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(), 
            'status' => 'active',
            'parent_id' => $currentUser->hasRole('organizer') ? $currentUser->id : null,
        ]);

        $user->assignRole($role);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        // Permissions
        if (Auth::user()->hasRole('organizer') && $user->parent_id !== Auth::id()) {
            abort(403, 'You are not authorized to edit this user.');
        }
        
        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        if (Auth::user()->hasRole('organizer') && $user->parent_id !== Auth::id()) {
            abort(403);
        }

        $user->update($request->validated());

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }
    
    public function destroy(User $user)
    {
        $currentUser = Auth::user();

        // Organizer can only delete their children
        if ($currentUser->hasRole('organizer') && $user->parent_id !== $currentUser->id) {
            abort(403, 'You are not authorized to delete this user.');
        }

        // Admin can delete anyone (except maybe self, handled in UI usually, but good to check)
        if ($user->id === $currentUser->id) {
             return back()->withErrors(['error' => 'Cannot delete your own account.']);
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
