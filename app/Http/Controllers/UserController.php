<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        // Ambil data user dan relasi role-nya
        $users = User::with('role')->get();
        // misal ambil semua user dari tabel users
        //$users = User::all();
        return view('pages.users.index', compact('users'));
    }

    public function create()
    {
        $roles = \App\Models\Role::all();
        return view('pages.users.create', compact('roles'));
    }

    public function store(Request $request)
    //store = save/proses
    {
        $request->validate([
            'username' => 'required|string|max:50',
            'email' => 'required|string|max:100|unique:users,email',
            'password' => 'required|string|max:500',
            'full_name' => 'required|string|max:100',
            'role_id' => 'required|exists:tb_role,id',
            'status' => 'required|in:active,inactive',
            'img' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // upload file
        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $filename = time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('template/assets/images/user'), $filename);

            $path = $filename;
        } else {
            $path = null; // pastikan path ter-set
        }


        \App\Models\User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'full_name' => $request->full_name,
            'role_id' => $request->role_id,
            'status' => $request->status,
            'img' => $path,
        ]);
        return redirect()->route('users.index')->with('success', 'User Berhasil Ditambahkan.');
    }

    public function destroy($id)
    {
        $user = \App\Models\User::FindOrFail($id);
        //hapus foto dari folder
        if (!empty($user->img)) {
            $filePath = public_path('template/assets/images/user/' . $user->img);
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }
        //hapus data dari database
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Data Berhasil Dihapus.');
    }

    public function edit($id)
    {
        $user = \App\Models\User::FindOrFail($id);
        $roles = \App\Models\Role::All();
        return view('pages.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:50',
            'email' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'email')->ignore($id),
            ],
            'password' => 'nullable|string|max:500',
            'full_name' => 'required|string|max:100',
            'role_id' => 'required|exists:tb_role,id',
            'status' => 'required|in:active,inactive',
            'img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->username = $request->username;
        $user->email = $request->email;
        $user->full_name = $request->full_name;
        $user->role_id = $request->role_id;
        $user->status = $request->status;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        if ($request->hasFile('img')) {
            if (!empty($user->img)) {
                $oldFile = public_path('template/assets/images/user/' . $user->img);
                if (file_exists($oldFile)) {
                    @unlink($oldFile);
                }
            }
            $file = $request->file('img');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('template/assets/images/user'), $filename);
            $user->img = $filename;
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Data Berhasil Diperbarui.');
    }
}
