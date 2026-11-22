<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $staff = User::orderBy('created_at', 'DESC')->get();
        $editStaff = null;

        return view('staff', compact('staff', 'editStaff'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'full_name' => 'required|string|max:100',
            'password' => 'required|string|min:6',
            'avatar' => 'nullable|image',
        ]);

        $avatarFilename = null;
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $avatarFilename = $file->hashName();
            $file->move(public_path('uploads/avatars'), $avatarFilename);
        }

        User::create([
            'username' => $validated['username'],
            'full_name' => $validated['full_name'],
            'password' => $validated['password'],
            'avatar' => $avatarFilename,
            'created_at' => now(),
        ]);

        return redirect('/staff')->with('success', 'Staff member added successfully.');
    }

    public function edit(User $staff)
    {
        $staffList = User::orderBy('created_at', 'DESC')->get();

        return view('staff', [
            'staff' => $staffList,
            'editStaff' => $staff,
        ]);
    }

    public function update(Request $request, User $staff)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:50|unique:users,username,'.$staff->id,
            'full_name' => 'required|string|max:100',
            'password' => 'nullable|string|min:6',
            'avatar' => 'nullable|image',
        ]);

        $data = [
            'username' => $validated['username'],
            'full_name' => $validated['full_name'],
        ];

        if (! empty($validated['password'])) {
            $data['password'] = $validated['password'];
        }

        if ($request->hasFile('avatar')) {
            if ($staff->avatar && File::exists(public_path('uploads/avatars/'.$staff->avatar))) {
                File::delete(public_path('uploads/avatars/'.$staff->avatar));
            }
            $file = $request->file('avatar');
            $data['avatar'] = $file->hashName();
            $file->move(public_path('uploads/avatars'), $data['avatar']);
        }

        $staff->update($data);

        if (session('user_id') == $staff->id) {
            session()->put([
                'user_name' => $data['full_name'],
                'username' => $data['username'],
                'avatar' => $data['avatar'] ?? $staff->avatar,
            ]);
        }

        return redirect('/staff')->with('success', 'Staff member updated successfully.');
    }

    public function destroy(User $staff)
    {
        if (session('user_id') == $staff->id) {
            return redirect('/staff')->with('error', 'You cannot delete your own account.');
        }

        if ($staff->avatar && File::exists(public_path('uploads/avatars/'.$staff->avatar))) {
            File::delete(public_path('uploads/avatars/'.$staff->avatar));
        }
        $staff->delete();

        return redirect('/staff')->with('success', 'Staff member deleted successfully.');
    }
}
