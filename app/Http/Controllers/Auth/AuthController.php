<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingInput\TahunAnggaran;
use App\Models\MasterBackend\SettingUser\PositionAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        $tahun = TahunAnggaran::where('status', 'aktif')
            ->with('fase')
            ->orderByDesc('nama_tahun_anggaran')
            ->get();

        return view('login.index', compact('tahun'));
    }

    public function login(Request $request)
    {
        $request->validate(
            [
                'username' => 'required|string',
                'password' => 'required|string',
                'tahun_anggaran_id' => 'required|exists:tahun_anggarans,id',
            ],
            [
                'username.required' => 'Username wajib diisi',
                'password.required' => 'Password wajib diisi',
                'tahun_anggaran_id.required' => 'Tahun anggaran wajib dipilih',
                'tahun_anggaran_id.exists' => 'Tahun anggaran tidak valid',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | 1. AUTHENTICATION
        |--------------------------------------------------------------------------
        */

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (! Auth::attempt($credentials)) {
            return back()
                ->withInput($request->only('username', 'tahun_anggaran_id'))
                ->with('error', 'Username atau password salah.');
        }

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | 2. CEK STATUS USER
        |--------------------------------------------------------------------------
        */

        if ($user->status_user !== 'aktif') {
            Auth::logout();

            return back()
                ->withInput($request->only('username', 'tahun_anggaran_id'))
                ->with('error', 'User tidak aktif.');
        }

        /*
        |--------------------------------------------------------------------------
        | 3. AMBIL TAHUN ANGGARAN
        |--------------------------------------------------------------------------
        */

        $tahun = TahunAnggaran::with('fase')
            ->where('id', $request->tahun_anggaran_id)
            ->where('status', 'aktif')
            ->first();

        if (! $tahun) {
            Auth::logout();

            return back()
                ->withInput($request->only('username'))
                ->with('error', 'Tahun anggaran tidak valid atau tidak aktif.');
        }

        /*
        |--------------------------------------------------------------------------
        | 4. AMBIL POSITION ASSIGNMENT AKTIF
        |--------------------------------------------------------------------------
        */

        $assignment = PositionAssignment::with([
            'unit',
            'position',
        ])
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->whereDate('tanggal_mulai', '<=', now())
            ->where(function ($query) {
                $query->whereNull('tanggal_selesai')
                    ->orWhereDate('tanggal_selesai', '>=', now());
            })
            ->orderByDesc('is_primary')
            ->orderByDesc('tanggal_mulai')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | 5. USER HARUS MEMILIKI PENEMPATAN JABATAN
        |--------------------------------------------------------------------------
        */

        if (! $assignment) {
            Auth::logout();

            return back()
                ->withInput($request->only('username', 'tahun_anggaran_id'))
                ->with(
                    'error',
                    'User belum memiliki penempatan jabatan yang aktif.'
                );
        }

        $unit = $assignment->unit;
        $position = $assignment->position;

        /*
        |--------------------------------------------------------------------------
        | 6. REGENERATE SESSION
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | 7. SIMPAN SESSION
        |--------------------------------------------------------------------------
        */

        Session::put([
            'user_id' => $user->id,
            'nip' => $user->nip,
            'nama' => $user->nama,

            // Assignment
            'position_assignment_id' => $assignment->id,
            'unit_id' => $assignment->unit_id,
            'position_id' => $assignment->position_id,

            // Jabatan
            'nama_jabatan' => $position?->nama_jabatan,

            // Unit
            'nama_unit' => $unit?->nama_unit,

            // Tahun anggaran
            'tahun_anggaran_id' => $tahun->id,
            'tahun_anggaran' => $tahun->nama_tahun_anggaran,

            // Fase
            'fase_tahun_anggaran_id' => $tahun->fase_id,
            'fase_tahun_anggaran' => $tahun->fase?->nama_fase,
        ]);

        /*
        |--------------------------------------------------------------------------
        | 8. REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form');
    }
}
