<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10); // Default 10 if not set
        $users = User::paginate($perPage);

        return view('user.index', compact('users'));
    }

    public function create()
    {
        return view('user.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'min:5', 'regex:/^[a-zA-Z0-9]+$/', 'unique:users,username'],
            'name' => ['required', 'string', 'min:15'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
            'access' => ['required', 'in:Admin,HR,Technical'],
        ], [
            'username.regex' => 'Username can only contain letters and numbers.',
        ]);

        // Store user
        User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'access' => $request->access,
        ]);

        return redirect()->route('users.index')->with('success', 'User added successfully!');
    }

    // Show the edit form
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'username' => ['required', 'string', 'min:5', 'regex:/^[a-zA-Z0-9]+$/'],
            'name' => ['required', 'string', 'min:15', 'max:255'],
            'email' => ['required', 'email'],
            'password' => ['nullable', 'string', 'min:8'],
            'access' => ['required', 'in:Admin,HR,Technical'],
        ];

        // only add unique rule if value changed
        if ($request->username != $user->username) {
            $rules['username'][] = 'unique:users,username';
        }

        if ($request->email != $user->email) {
            $rules['email'][] = 'unique:users,email';
        }

        $validatedData = $request->validate($rules, [
            'username.regex' => 'Username can only contain letters and numbers.',
        ]);

        $user->update([
            'username' => $validatedData['username'],
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'access' => $validatedData['access'],
            'password' => $validatedData['password'] ? Hash::make($validatedData['password']) : $user->password,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    // Delete the user
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('users.index')->with('success', 'User deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'Failed to delete user. Please try again.');
        }
    }

    public function validateField(Request $request)
    {
        $rules = [
            'username' => ['required', 'string', 'min:5', 'regex:/^[a-zA-Z0-9]+$/', 'unique:users,username'],
            'name' => ['required', 'string', 'min:15'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
            'access' => ['required', 'in:Admin,HR,Technical'],
        ];

        $messages = [
            'username.regex' => 'Username can only contain letters.',
        ];

        // Validate only the specific field
        $validator = Validator::make($request->all(), [
            $request->field => $rules[$request->field] ?? ''
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first($request->field)], 422);
        }

        return response()->json(['success' => true]);
    }

    public function validateEditField(Request $request)
    {
        $rules = [
            'username' => ['required', 'string', 'min:5', 'regex:/^[a-zA-Z0-9]+$/', 'unique:users,username,' . $request->user_id],
            'name' => ['required', 'string', 'min:15'],
            'email' => ['required', 'email', 'unique:users,email,' . $request->user_id],
            'password' => ['nullable', 'min:8'], // Password is optional in edit
            'access' => ['required', 'in:Admin,HR,Technical']
        ];

        $validator = Validator::make($request->all(), [$request->field => $rules[$request->field]]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first($request->field)], 422);
        }

        return response()->json(['success' => true]);
    }
}
