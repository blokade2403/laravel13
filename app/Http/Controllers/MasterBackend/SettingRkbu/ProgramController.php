<?php

namespace App\Http\Controllers\MasterBackend\SettingRkbu;

use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingRkbu\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $programs = Program::all();

        return view('master_backend.setting_rkbu.program.index', compact('programs'), [
            'title' => 'Halaman Program',
            'title2' => 'Program',
            'routePrefix' => 'programs',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master_backend.setting_rkbu.program.create', [
            'title' => 'Halaman Program',
            'title2' => 'Program',
            'routePrefix' => 'programs',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Program::create($this->validatedData($request));

        return redirect()->route('programs.index')->with('success', 'Program berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Program $program)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Program $program)
    {
        // $program   = Program::all();
        return view('master_backend.setting_rkbu.program.edit', compact('program'), [
            'title' => 'Halaman Program',
            'title2' => 'Program',
            'routePrefix' => 'programs',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Program $program)
    {
        $program->update($this->validatedData($request, $program));

        return redirect()->route('programs.index')->with('success', 'Program Edit Success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Program $program)
    {
        $program->delete();

        return redirect()->route('programs.index')
            ->with('success', 'Program deleted successfully.');
    }

    private function validatedData(Request $request, ?Program $program = null): array
    {
        return $request->validate([
            'kode_program' => ['required', 'string', 'max:100', 'unique:programs,kode_program,'.($program?->id ?? 'NULL')],
            'nama_program' => ['required', 'string'],
        ]);
    }
}
