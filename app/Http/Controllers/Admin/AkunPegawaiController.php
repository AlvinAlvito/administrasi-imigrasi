<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PegawaiProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AkunPegawaiController extends Controller
{
    public function index()
    {
        $pegawai = User::with('pegawaiProfile')
            ->where('role', 'pegawai')
            ->orderBy('name')
            ->get();

        return view('admin.pegawai', compact('pegawai'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'nip' => ['nullable', 'string', 'max:30'],
            'jabatan' => ['nullable', 'string', 'max:100'],
            'password' => ['nullable', 'string', 'min:6'],

            // profile
            'no_hp' => ['nullable', 'string', 'max:20'],
            'unit_kerja' => ['nullable', 'string', 'max:100'],
            'alamat' => ['nullable', 'string'],
            'jenis_kelamin' => ['nullable', 'in:L,P'],
            'eselon' => ['nullable', 'string', 'max:20'],
            'pangkat_gol' => ['nullable', 'string', 'max:50'],
            'tmt' => ['nullable', 'date'],
            'pendidikan' => ['nullable', 'string', 'max:100'],
            'diklat_teknis' => ['nullable', 'string', 'max:120'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'] ?? '12345678'),
            'role' => 'pegawai',
            'nip' => $data['nip'] ?? null,
            'jabatan' => $data['jabatan'] ?? null,
        ]);

        PegawaiProfile::create([
            'user_id' => $user->id,
            'no_hp' => $data['no_hp'] ?? null,
            'unit_kerja' => $data['unit_kerja'] ?? null,
            'alamat' => $data['alamat'] ?? null,
            'jenis_kelamin' => $data['jenis_kelamin'] ?? null,
            'eselon' => $data['eselon'] ?? null,
            'pangkat_gol' => $data['pangkat_gol'] ?? null,
            'tmt' => $data['tmt'] ?? null,
            'pendidikan' => $data['pendidikan'] ?? null,
            'diklat_teknis' => $data['diklat_teknis'] ?? null,
        ]);

        return back()->with('success', 'Akun pegawai berhasil dibuat.');
    }


    public function update(Request $request, $id)
    {
        $user = User::where('role', 'pegawai')->findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($user->id)],
            'nip' => ['nullable', 'string', 'max:30'],
            'jabatan' => ['nullable', 'string', 'max:100'],
            'password' => ['nullable', 'string', 'min:6'],

            // profile
            'no_hp' => ['nullable', 'string', 'max:20'],
            'unit_kerja' => ['nullable', 'string', 'max:100'],
            'alamat' => ['nullable', 'string'],
            'jenis_kelamin' => ['nullable', 'in:L,P'],
            'eselon' => ['nullable', 'string', 'max:20'],
            'pangkat_gol' => ['nullable', 'string', 'max:50'],
            'tmt' => ['nullable', 'date'],
            'pendidikan' => ['nullable', 'string', 'max:100'],
            'diklat_teknis' => ['nullable', 'string', 'max:120'],
        ]);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'nip' => $data['nip'] ?? null,
            'jabatan' => $data['jabatan'] ?? null,
            'password' => isset($data['password']) ? Hash::make($data['password']) : $user->password,
        ]);

        $profile = PegawaiProfile::firstOrNew(['user_id' => $user->id]);
        $profile->no_hp = $data['no_hp'] ?? $profile->no_hp;
        $profile->unit_kerja = $data['unit_kerja'] ?? $profile->unit_kerja;
        $profile->alamat = $data['alamat'] ?? $profile->alamat;
        $profile->jenis_kelamin = $data['jenis_kelamin'] ?? $profile->jenis_kelamin;
        $profile->eselon = $data['eselon'] ?? $profile->eselon;
        $profile->pangkat_gol = $data['pangkat_gol'] ?? $profile->pangkat_gol;
        $profile->tmt = $data['tmt'] ?? $profile->tmt;
        $profile->pendidikan = $data['pendidikan'] ?? $profile->pendidikan;
        $profile->diklat_teknis = $data['diklat_teknis'] ?? $profile->diklat_teknis;
        $profile->save();

        return back()->with('success', 'Akun pegawai berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $user = User::where('role', 'pegawai')->findOrFail($id);
        $user->delete(); // profiles ikut terhapus karena FK cascade
        return back()->with('success', 'Akun pegawai berhasil dihapus.');
    }
}
