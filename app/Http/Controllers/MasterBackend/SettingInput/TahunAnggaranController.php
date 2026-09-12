<?php

namespace App\Http\Controllers\MasterBackend\SettingInput;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingInput\TahunAnggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TahunAnggaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tahun_anggarans = TahunAnggaran::orderBy('tahun')->get();

        return view('master_backend.setting_input.tahun_anggaran.index', [
            'tahun_anggarans' => $tahun_anggarans,
            'title' => 'Halaman Tahun Anggaran',
            'title2' => 'Tahun Anggaran',
            'routePrefix' => 'tahun_anggarans',
        ]);
    }

    public function create()
    {
        return view('master_backend.setting_input.tahun_anggaran.create', [
            'title' => 'Halaman Tahun Anggaran',
            'title2' => 'Tahun Anggaran',
            'routePrefix' => 'tahun_anggarans',
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required|integer|min:2000|max:2100|unique:tahun_anggarans,tahun',
            'nama_tahun_anggaran' => 'required|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'tahun.required' => 'Tahun anggaran wajib diisi.',
            'tahun.integer' => 'Tahun anggaran harus berupa angka.',
            'tahun.min' => 'Tahun anggaran minimal 2000.',
            'tahun.max' => 'Tahun anggaran maksimal 2100.',
            'tahun.unique' => 'Tahun anggaran sudah digunakan.',
            'nama_tahun_anggaran.required' => 'Nama tahun anggaran wajib diisi.',
            'nama_tahun_anggaran.string' => 'Nama tahun anggaran harus berupa teks.',
            'nama_tahun_anggaran.max' => 'Nama tahun anggaran maksimal 255 karakter.',
            'status.required' => 'Status harus dipilih.',
            'status.in' => 'Status tidak valid.',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: '.$errorMessages);
        }

        TahunAnggaran::create([
            'tahun' => $request->tahun,
            'nama_tahun_anggaran' => $request->nama_tahun_anggaran,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('tahun_anggarans.index')
            ->with('success', 'Tahun Anggaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TahunAnggaran $TahunAnggaran)
    {
        return view('master_backend.setting_input.tahun_anggaran.show', compact('TahunAnggaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TahunAnggaran $TahunAnggaran)
    {
        return view('master_backend.setting_input.tahun_anggaran.edit', [
            'TahunAnggaran' => $TahunAnggaran,
            'title' => 'Halaman Tahun Anggaran',
            'title2' => 'Tahun Anggaran',
            'routePrefix' => 'tahun_anggarans',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TahunAnggaran $TahunAnggaran)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => [
                'required',
                'integer',
                'min:2000',
                'max:2100',
                Rule::unique('tahun_anggarans', 'tahun')->ignore($TahunAnggaran->id),
            ],
            'nama_tahun_anggaran' => 'required|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'tahun.required' => 'Tahun anggaran wajib diisi.',
            'tahun.integer' => 'Tahun anggaran harus berupa angka.',
            'tahun.min' => 'Tahun anggaran minimal 2000.',
            'tahun.max' => 'Tahun anggaran maksimal 2100.',
            'tahun.unique' => 'Tahun anggaran sudah digunakan.',
            'nama_tahun_anggaran.required' => 'Nama tahun anggaran wajib diisi.',
            'nama_tahun_anggaran.string' => 'Nama tahun anggaran harus berupa teks.',
            'nama_tahun_anggaran.max' => 'Nama tahun anggaran maksimal 255 karakter.',
            'status.required' => 'Status harus dipilih.',
            'status.in' => 'Status tidak valid.',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Periksa Kembali Update Data Anda !! '.$errorMessages);
        }

        $TahunAnggaran->update([
            'tahun' => $request->tahun,
            'nama_tahun_anggaran' => $request->nama_tahun_anggaran,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('tahun_anggarans.index')
            ->with('success', 'Data Berhasil di Update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TahunAnggaran $TahunAnggaran)
    {
        $TahunAnggaran->delete();

        return redirect()
            ->route('tahun_anggarans.index')
            ->with('success', 'Tahun Anggaran deleted successfully.');
    }
}
