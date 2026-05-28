<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profil;
use Illuminate\Support\Facades\File;

class ProfilController extends Controller
{
    public function index()
    {
        $user = Profil::first();

        if (!$user) {
            $user = Profil::create([
                'nama' => 'User Baru',
                'email' => 'user@gmail.com',
                'no_hp' => '-'
            ]);
        }

        return view('profil', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Profil::first();

        $validated = $request->validate([
            'nama'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_hp' => 'required|string|max:20',
            'foto'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('foto')) {

            if ($user->foto && File::exists(public_path('uploads/'.$user->foto))) {
                File::delete(public_path('uploads/'.$user->foto));
            }

            $file = $request->file('foto');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);

            $validated['foto'] = $filename;
        }

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diupdate');
    }

    public function delete()
    {
        $user = Profil::first();

        if ($user) {

            if ($user->foto && File::exists(public_path('uploads/'.$user->foto))) {
                File::delete(public_path('uploads/'.$user->foto));
            }

            $user->delete();
        }

        return back()->with('success', 'Profil berhasil dihapus');
    }
}