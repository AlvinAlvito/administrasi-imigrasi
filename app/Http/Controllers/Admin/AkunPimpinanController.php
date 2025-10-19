<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PimpinanProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AkunPimpinanController extends Controller
{
    public function index()
    {
        $pimpinan = User::with('pimpinanProfile')
            ->where('role', 'pimpinan')
            ->orderBy('name')
            ->get();

        return view('admin.pimpinan', compact('pimpinan'));
    }

    private function makeUniqueEmailFromName(string $name, string $domain = 'noemail.local'): string
{
    $base = \Illuminate\Support\Str::of($name)->lower()->slug('.');
    if ($base->isEmpty()) $base = 'pimpinan';

    $email = $base.'@'.$domain;
    $i = 1;
    while (User::where('email', $email)->exists()) {
        $email = $base.'-'.$i.'@'.$domain;
        $i++;
    }
    return $email;
}


public function store(Request $request)
{
    $data = $request->validate([
        'name'          => ['required', 'string', 'max:100'],
        'email'         => ['nullable', 'email', 'max:100'], // <-- dibuat nullable (tidak pakai unique di sini)
        'nip'           => ['nullable', 'string', 'max:30'],
        'jabatan'       => ['nullable', 'string', 'max:100'],
        'password'      => ['nullable', 'string', 'min:6'],
        'tanda_tangan'  => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],

        'jenis_kelamin' => ['nullable', 'in:L,P'],
        'eselon'        => ['nullable', 'string', 'max:20'],
        'pangkat_gol'   => ['nullable', 'string', 'max:50'],
        'tmt'           => ['nullable', 'date'],
        'pendidikan'    => ['nullable', 'string', 'max:100'],
        'diklat_teknis' => ['nullable', 'string', 'max:120'],
    ]);

    // Siapkan email yang dijamin unik (atau dibuatkan kalau kosong / tabrakan)
    $email = $data['email'] ?? null;
    if (empty($email)) {
        // Tidak diisi -> buatkan alamat dummy unik
        $email = $this->makeUniqueEmailFromName($data['name']);
    } else {
        // Diisi, tapi kalau sudah dipakai -> buatkan alias unik
        if (User::where('email', $email)->exists()) {
            $email = $this->makeUniqueEmailFromName($data['name']);
        }
    }

    // Simpan user
    $user = User::create([
        'name'     => $data['name'],
        'email'    => $email, // <-- sudah dijamin unik
        'password' => Hash::make($data['password'] ?? '12345678'),
        'role'     => 'pimpinan',
        'nip'      => $data['nip'] ?? null,
        'jabatan'  => $data['jabatan'] ?? null,
    ]);

    // Upload TTD ke public/
    $path = null;
    if ($request->hasFile('tanda_tangan')) {
        $file = $request->file('tanda_tangan');
        if ($file->isValid()) {
            $dir = public_path('uploads/tanda_tangan');
            if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
            $ext = strtolower($file->getClientOriginalExtension() ?: 'png');
            $base = \Illuminate\Support\Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $filename = $base . '-' . time() . '.' . $ext;
            $file->move($dir, $filename);
            $path = 'uploads/tanda_tangan/' . $filename; // relative path untuk asset()
        }
    }

    PimpinanProfile::create([
        'user_id'       => $user->id,
        'nip'           => $data['nip'] ?? null,
        'jabatan'       => $data['jabatan'] ?? null,
        'tanda_tangan'  => $path,
        'jenis_kelamin' => $data['jenis_kelamin'] ?? null,
        'eselon'        => $data['eselon'] ?? null,
        'pangkat_gol'   => $data['pangkat_gol'] ?? null,
        'tmt'           => $data['tmt'] ?? null,
        'pendidikan'    => $data['pendidikan'] ?? null,
        'diklat_teknis' => $data['diklat_teknis'] ?? null,
    ]);

    return back()->with('success', 'Akun pimpinan berhasil dibuat.');
}


    public function update(Request $request, $id)
    {
        $user = User::where('role', 'pimpinan')->findOrFail($id);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($user->id)],
            'nip' => ['nullable', 'string', 'max:30'],
            'jabatan' => ['nullable', 'string', 'max:100'],
            'password' => ['nullable', 'string', 'min:6'],
            'tanda_tangan' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],

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

        $profile = PimpinanProfile::firstOrNew(['user_id' => $user->id]);
        $profile->nip = $data['nip'] ?? $profile->nip;
        $profile->jabatan = $data['jabatan'] ?? $profile->jabatan;

        if ($request->hasFile('tanda_tangan')) {
            if ($profile->tanda_tangan)
                Storage::disk('public')->delete($profile->tanda_tangan);
            $profile->tanda_tangan = $request->file('tanda_tangan')->store('tanda_tangan', 'public');
        }

        $profile->jenis_kelamin = $data['jenis_kelamin'] ?? $profile->jenis_kelamin;
        $profile->eselon = $data['eselon'] ?? $profile->eselon;
        $profile->pangkat_gol = $data['pangkat_gol'] ?? $profile->pangkat_gol;
        $profile->tmt = $data['tmt'] ?? $profile->tmt;
        $profile->pendidikan = $data['pendidikan'] ?? $profile->pendidikan;
        $profile->diklat_teknis = $data['diklat_teknis'] ?? $profile->diklat_teknis;
        $profile->save();

        return back()->with('success', 'Akun pimpinan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::where('role', 'pimpinan')->findOrFail($id);
        // hapus file ttd jika ada
        if ($user->pimpinanProfile && $user->pimpinanProfile->tanda_tangan) {
            Storage::disk('public')->delete($user->pimpinanProfile->tanda_tangan);
        }
        $user->delete();
        return back()->with('success', 'Akun pimpinan berhasil dihapus.');
    }
}
