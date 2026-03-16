<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->latest()->paginate(10);
        return view('user.list', compact('users'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function create()
    {
        $roles = Role::all();
        return view('user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
                'role_id' => 'required|exists:roles,id',
            ]);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'role_id' => $request->role_id,
            ]);

            return redirect()->route('user.index')->with('success', 'Berhasil menambahkan user');
        } catch (\Throwable $th) {
            return redirect()->route('user.index')->with('error', $th->getMessage());
        }
    }

    public function edit($id)
    {
        $user = User::where('id', $id)->firstOrFail();
        $roles = Role::all();
        return view('user.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users', 'email')->ignore($id),
                ],
                'role_id' => 'required|exists:roles,id',
                'password' => 'nullable|string|min:6|confirmed',
            ]);

            $user = User::findOrFail($id);

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $request->role_id,
            ];

            if ($request->filled('password')) {
                $data['password'] = $request->password;
            }

            $user->update($data);

            return redirect()->route('user.index')->with('success', 'Berhasil update user');
        } catch (\Throwable $th) {
            return redirect()->route('user.index')->with('error', $th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->id === auth()->id()) {
                return redirect()->route('user.index')->with('error', 'Tidak bisa menghapus akun sendiri');
            }

            $user->delete();
            return redirect()->route('user.index')->with('success', 'Berhasil menghapus user');
        } catch (\Throwable $th) {
            return redirect()->route('user.index')->with('error', $th->getMessage());
        }
    }
}
