<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('user.index', compact('users'));
    }

    public function edit($id)
    {
        // PENGAMAN: Jika user coba edit ID orang lain lewat URL, lempar error 403
        if ($id != Auth::id()) {
            abort(403, 'Anda hanya boleh mengedit profil Anda sendiri!');
        }

        $user = User::where('id_user', $id)->firstOrFail();
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        if ($id != Auth::id()) {
            abort(403);
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id.',id_user',
            'password_konfirmasi' => 'required'
        ]);

        $user = User::where('id_user', $id)->firstOrFail();

        // Verifikasi password sebelum update
        if (!Hash::check($request->password_konfirmasi, $user->password)) {
            return redirect()->back()->with('error', 'Password salah! Perubahan ditolak.');
        }

        $user->update([
            'nama' => $request->nama,
            'email' => $request->email,
        ]);

        return redirect()->route('user.index')->with('success', 'Profil Anda berhasil diperbarui!');
    }
}