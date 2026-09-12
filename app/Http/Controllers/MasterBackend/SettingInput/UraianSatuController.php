<?php

namespace App\Http\Controllers\MasterBackend\SettingInput;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingInput\UraianSatu;
use Illuminate\Http\Request;

class UraianSatuController extends Controller
{
    public function index()
    {
        $uraian_satus = UraianSatu::all();

        return view('master_backend.setting_input.uraian_satu.index', compact('uraian_satus'), [
            'title' => 'Halaman Uraian Satu',
            'title2' => 'Uraian Satu',
            'routePrefix' => 'uraian_satus',
        ]);
    }

    public function create()
    {
        return view('master_backend.setting_input.uraian_satu.create', [
            'title' => 'Halaman Uraian Satu',
            'title2' => 'Uraian Satu',
            'routePrefix' => 'uraian_satus',
        ]);
    }

    public function store(Request $request)
    {
        UraianSatu::create($this->validatedData($request));

        // Redirect ke halaman yang diinginkan dengan pesan sukses
        return redirect()
            ->route('uraian_satus.index')
            ->with('success', 'KSP berhasil ditambahkan.');
    }

    public function show(UraianSatu $uraianSatu)
    {
        //
    }

    public function edit(UraianSatu $uraianSatu)
    {
        // $uraianSatu = UraianSatu::all();
        return view('master_backend.setting_input.uraian_satu.edit', compact('uraianSatu'), [
            'title' => 'Halaman Uraian Satu',
            'title2' => 'Uraian Satu',
            'routePrefix' => 'uraian_satus',
        ]);
    }

    public function update(Request $request, UraianSatu $uraianSatu)
    {
        $uraianSatu->update($this->validatedData($request));

        // Redirect ke halaman yang diinginkan dengan pesan sukses
        return redirect()
            ->route('uraian_satus.index')
            ->with('success', 'Data uraian_satu berhasil ditambahkan.');
    }

    public function destroy(UraianSatu $uraianSatu)
    {
        $uraianSatu->delete();

        return redirect()->route('uraian_satus.index')->with('success', 'Data Berhasil di Delete');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate(['nama_uraian_1' => ['required', 'string']]);
    }
}
