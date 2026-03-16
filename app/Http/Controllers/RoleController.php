<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::latest()->paginate(10);
        return view('role.list', compact('roles'))
            ->with('i', (request()->input('page', 1) - 1) * 10);
    }

    public function create()
    {
        return view('role.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:roles,name',
            ]);

            Role::create([
                'name' => $request->name,
            ]);

            return redirect()->route('role.index')->with('success', 'Berhasil menambahkan role');
        } catch (\Throwable $th) {
            return redirect()->route('role.index')->with('error', $th->getMessage());
        }
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('role.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:roles,name,' . $id,
            ]);

            $role = Role::findOrFail($id);
            $role->update([
                'name' => $request->name,
            ]);

            return redirect()->route('role.index')->with('success', 'Berhasil update role');
        } catch (\Throwable $th) {
            return redirect()->route('role.index')->with('error', $th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);

            if ($role->users()->count() > 0) {
                return redirect()->route('role.index')->with('error', 'Role masih digunakan oleh user, tidak bisa dihapus');
            }

            $role->rolePermissions()->delete();
            $role->delete();

            return redirect()->route('role.index')->with('success', 'Berhasil menghapus role');
        } catch (\Throwable $th) {
            return redirect()->route('role.index')->with('error', $th->getMessage());
        }
    }
}
