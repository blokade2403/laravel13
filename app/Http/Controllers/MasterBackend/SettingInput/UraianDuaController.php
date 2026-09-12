<?php

namespace App\Http\Controllers\MasterBackend\SettingInput;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingInput\UraianDua;
use Illuminate\Http\Request;

class UraianDuaController extends Controller
{
    public function index()
    {
        $uraian_duas = UraianDua::all();

        return view('master_backend.setting_input.uraian_dua.index', compact('uraian_duas'), [
            'title' => 'Halaman Uraian Dua',
            'title2' => 'Uraian Dua',
            'routePrefix' => 'uraian_duas',
        ]);
    }

    public function create()
    {
        return view('master_backend.setting_input.uraian_dua.create', [
            'title' => 'Halaman Uraian Dua',
            'title2' => 'Uraian Dua',
            'routePrefix' => 'uraian_duas',
        ]);
    }

    public function store(Request $request)
    {
        UraianDua::create($this->validatedData($request));

        // Redirect ke halaman yang diinginkan dengan pesan sukses
        return redirect()->route('uraian_duas.index')->with('success', 'KSP berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(UraianDua $uraianDua)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UraianDua $uraianDua)
    {
        return view('master_backend.setting_input.uraian_dua.edit', [
            'uraianDua' => $uraianDua,
            'title' => 'Halaman Uraian Dua',
            'title2' => 'Uraian Dua',
            'routePrefix' => 'uraian_duas',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UraianDua $uraianDua)
    {
        $uraianDua->update($this->validatedData($request));

        // Redirect ke halaman yang diinginkan dengan pesan sukses
        return redirect()->route('uraian_duas.index')->with('success', 'Data uraian_satu berhasil ditambahkan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UraianDua $uraianDua)
    {
        $uraianDua->delete();

        return redirect()->route('uraian_duas.index')->with('success', 'Uraian Delete Berhasil');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate(['nama_uraian_2' => ['required', 'string']]);
    }
}
