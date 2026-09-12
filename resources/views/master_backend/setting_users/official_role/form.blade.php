@extends('layouts.main')
@section('content')
<div class="card"><div class="card-body">
    <form method="POST" action="{{ $officialRole ? route('official-roles.update', $officialRole) : route('official-roles.store') }}">
        @csrf @if ($officialRole) @method('PUT') @endif
        <label>Kode</label><input class="form-control mb-3" name="kode" value="{{ old('kode', $officialRole?->kode) }}" required>
        <label>Nama</label><input class="form-control mb-3" name="nama" value="{{ old('nama', $officialRole?->nama) }}" required>
        <label>Deskripsi</label><textarea class="form-control mb-3" name="deskripsi">{{ old('deskripsi', $officialRole?->deskripsi) }}</textarea>
        <label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $officialRole?->is_active ?? true))> Aktif</label>
        <button class="btn btn-primary mt-3">Simpan</button>
    </form>
</div></div>
@endsection
