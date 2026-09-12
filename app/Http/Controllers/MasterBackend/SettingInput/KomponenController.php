<?php

namespace App\Http\Controllers\MasterBackend\SettingInput;

use App\DataTables\KomponenDataTable;
use App\Http\Controllers\Controller;
use App\Models\MasterBackend\SettingInput\Komponen;
use App\Models\MasterBackend\SettingInput\UraianSatu;
use App\Models\MasterBackend\SettingRkbu\JenisKategoriRkbu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class KomponenController extends Controller
{
    public function index(KomponenDataTable $dataTable)
    {
        $komponens = Komponen::all();
        $satuan = UraianSatu::all();
        $jenis_kategori_rkbu = JenisKategoriRkbu::all();

        return $dataTable->render('master_backend.setting_input.komponen.index', [
            'title' => 'Halaman Komponen',
            'title2' => 'Komponen',
            'routePrefix' => 'komponens',
            'komponens' => $komponens,
            'satuan' => $satuan,
            'jenis_kategori_rkbu' => $jenis_kategori_rkbu,
        ]);
    }

    public function create()
    {
        $komponens = Komponen::all();
        $jenis_kategori_rkbu = JenisKategoriRkbu::all();

        return view('master_backend.setting_input.komponen.create', compact('jenis_kategori_rkbu'), [
            'title' => 'Halaman Komponen',
            'title2' => 'Komponen',
            'routePrefix' => 'komponens',
            'komponens' => $komponens,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis_kategori_rkbu_id' => 'required|exists:jenis_kategori_rkbus,id',
            'kode_barang' => 'nullable|string|max:100',
            'kode_komponen' => 'required|string|max:100|unique:komponens,kode_komponen',
            'nama_barang' => 'required|string',
            'satuan' => 'nullable|string|max:100',
            'spek' => 'nullable|string',
            'harga_barang' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ], [
            'jenis_kategori_rkbu_id.required' => 'Jenis kategori RKBU wajib dipilih.',
            'jenis_kategori_rkbu_id.exists' => 'Jenis kategori RKBU tidak valid.',
            'kode_komponen.required' => 'Kode komponen wajib diisi.',
            'kode_komponen.unique' => 'Kode komponen sudah digunakan.',
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'harga_barang.required' => 'Harga barang wajib diisi.',
            'harga_barang.numeric' => 'Harga barang harus berupa angka.',
            'is_active.boolean' => 'Status aktif tidak valid.',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: '.$errorMessages);
        }

        Komponen::create([
            'jenis_kategori_rkbu_id' => $request->jenis_kategori_rkbu_id,
            'kode_barang' => $request->kode_barang,
            'kode_komponen' => $request->kode_komponen,
            'nama_barang' => $request->nama_barang,
            'satuan' => $request->satuan,
            'spek' => $request->spek,
            'harga_barang' => $request->harga_barang,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('komponens.index')->with('success', 'KSP berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Komponen $komponen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Komponen $komponen)
    {
        $komponen->load('jenis_kategori_rkbu');
        $jenis_kategori_rkbu = JenisKategoriRkbu::all();

        return view('master_backend.setting_input.komponen.edit', compact('komponen', 'jenis_kategori_rkbu'), [
            'title' => 'Halaman Komponen',
            'title2' => 'Komponen',
            'routePrefix' => 'komponens',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Komponen $komponen)
    {
        $validator = Validator::make($request->all(), [
            'jenis_kategori_rkbu_id' => 'required|exists:jenis_kategori_rkbus,id',
            'kode_barang' => 'nullable|string|max:100',
            'kode_komponen' => ['required', 'string', 'max:100', Rule::unique('komponens', 'kode_komponen')->ignore($komponen->id)],
            'nama_barang' => 'required|string',
            'satuan' => 'nullable|string|max:100',
            'spek' => 'nullable|string',
            'harga_barang' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ], [
            'jenis_kategori_rkbu_id.required' => 'Jenis kategori RKBU wajib dipilih.',
            'jenis_kategori_rkbu_id.exists' => 'Jenis kategori RKBU tidak valid.',
            'kode_komponen.required' => 'Kode komponen wajib diisi.',
            'kode_komponen.unique' => 'Kode komponen sudah digunakan.',
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'harga_barang.required' => 'Harga barang wajib diisi.',
            'harga_barang.numeric' => 'Harga barang harus berupa angka.',
            'is_active.boolean' => 'Status aktif tidak valid.',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorMessages = implode(' ', $errors->all());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Ada kesalahan pada input Anda, mohon periksa kembali: '.$errorMessages);
        }

        $komponen->update([
            'jenis_kategori_rkbu_id' => $request->jenis_kategori_rkbu_id,
            'kode_barang' => $request->kode_barang,
            'kode_komponen' => $request->kode_komponen,
            'nama_barang' => $request->nama_barang,
            'satuan' => $request->satuan,
            'spek' => $request->spek,
            'harga_barang' => $request->harga_barang,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('komponens.index')->with('success', 'Data komponen berhasil ditambahkan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Komponen $komponen)
    {
        $komponen->delete();

        return redirect()->route('komponens.index')
            ->with('success', 'Komponen deleted successfully.');
    }
}
