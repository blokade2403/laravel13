<?php

namespace App\Http\Controllers\MasterBackend\SettingRkbu;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingRkbu\SumberDana;
use Illuminate\Http\Request;

class SumberDanaController extends Controller
{
    public function index()
    {
        $sumber_danas = SumberDana::all();

        return view('master_backend.setting_rkbu.sumber_dana.index', compact('sumber_danas'), [
            'title' => 'Halaman Sumber Dana',
            'title2' => 'Sumber Dana',
            'routePrefix' => 'sumber_danas',
        ]);
    }

    public function create()
    {
        return view('master_backend.setting_rkbu.sumber_dana.create', [
            'title' => 'Halaman Sumber Dana',
            'title2' => 'Sumber Dana',
            'routePrefix' => 'sumber_danas',
        ]);
    }

    public function store(Request $request)
    {
        SumberDana::create($this->validatedData($request));

        return redirect()
            ->route('sumber_danas.index')
            ->with('success', 'Sumber Dana updated successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SumberDana $sumberDana)
    {
        return view('backend.master_setting.sumber_danas.show', compact('sumberDana'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SumberDana $sumberDana)
    {
        return view('master_backend.setting_rkbu.sumber_dana.edit', compact('sumberDana'), [
            'title' => 'Halaman Sumber Dana',
            'title2' => 'Sumber Dana',
            'routePrefix' => 'sumber_danas',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SumberDana $sumberDana)
    {
        $sumberDana->update($this->validatedData($request, $sumberDana));

        return redirect()
            ->route('sumber_danas.index')
            ->with('success', 'Sumber Dana updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SumberDana $sumberDana)
    {
        $sumberDana->delete();

        return redirect()
            ->route('sumber_danas.index')
            ->with('success', 'Sumber Dana deleted successfully.');
    }

    private function validatedData(Request $request, ?SumberDana $sumberDana = null): array
    {
        return $request->validate([
            'kode_sumber_dana' => ['required', 'string', 'max:50', 'unique:sumber_danas,kode_sumber_dana,'.($sumberDana?->id ?? 'NULL')],
            'nama_sumber_dana' => ['required', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);
    }
}
