<?php

namespace App\Http\Controllers\MasterBackend\SettingInput;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingInput\Ppn;
use Illuminate\Http\Request;

class PpnController extends Controller
{
    public function index()
    {
        $ppn = Ppn::all();

        return view('master_backend.setting_input.ppn.index', compact('ppn'), [
            'title' => 'Halaman PPN',
            'title2' => 'Status PPN',
            'routePrefix' => 'ppn',
        ]);
    }

    public function create() {}

    public function store(Request $request)
    {
        Ppn::create($this->validatedData($request));

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function show($id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ppn $statusValidasi) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ppn $ppn)
    {
        $ppn->update($this->validatedData($request));

        return redirect()->back()->with('success', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ppn $ppn)
    {
        $ppn->delete();

        return redirect()->route('ppns.index')->with('success', 'Data Berhasil di Hapus');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'ppn' => ['required', 'numeric'],
            'status' => ['required', 'string'],
        ]);
    }
}
