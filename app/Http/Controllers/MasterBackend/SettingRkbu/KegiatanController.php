<?php

namespace App\Http\Controllers\MasterBackend\SettingRkbu;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingRkbu\Kegiatan;
use App\Models\MasterBackend\SettingRkbu\Program;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index()
    {
        $programs = Program::all();
        $kegiatans = Kegiatan::with('program')->get();

        return view(
            'master_backend.setting_rkbu.kegiatan.index',
            compact('kegiatans', 'programs'),
            [
                'title' => 'Halaman Kegiatan',
                'title2' => 'Kegiatan',
                'routePrefix' => 'kegiatans',
            ],
        );
    }

    public function create()
    {
        $programs = Program::all();

        return view('master_backend.setting_rkbu.kegiatan.create', compact('programs'), [
            'title' => 'Halaman Kegiatan',
            'title2' => 'Kegiatan',
            'routePrefix' => 'kegiatans',
        ]);
    }

    public function getProgramDetails($id)
    {
        $kegiatan = Kegiatan::with('program')->find($id);

        if ($kegiatan) {
            return response()->json([
                'kode_program' => $kegiatan->program->kode_program,
                'nama_program' => $kegiatan->program->nama_program,
            ]);
        }

        return response()->json(['error' => 'Data not found'], 404);
    }

    public function store(Request $request)
    {
        Kegiatan::create($this->validatedData($request));

        return redirect()
            ->route('kegiatans.index')
            ->with('success', 'Data Kegiatan Berhasil di Tambahkan');
    }

    public function show(Kegiatan $kegiatan) {}

    public function edit(Kegiatan $kegiatan)
    {
        $programs = Program::all();
        $kegiatans = $kegiatan->load('program');

        return view('master_backend.setting_rkbu.kegiatan.edit', compact('kegiatans', 'programs'), [
            'title' => 'Halaman Kegiatan',
            'title2' => 'Kegiatan',
            'routePrefix' => 'kegiatans',
        ]);
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $kegiatan->update($this->validatedData($request, $kegiatan));

        return redirect()->route('kegiatans.index')->with('success', 'Kegiatan Update Success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();

        return redirect()
            ->route('kegiatans.index')
            ->with('success', 'Kegiatan deleted successfully.');
    }

    private function validatedData(Request $request, ?Kegiatan $kegiatan = null): array
    {
        return $request->validate([
            'program_id' => ['required', 'exists:programs,id'],
            'kode_kegiatan' => ['required', 'string', 'max:100'],
            'nama_kegiatan' => ['required', 'string'],
        ]);
    }
}
