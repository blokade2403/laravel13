@extends('layouts.main')
@section('content')
<div class="card"><div class="card-body">
    <a class="btn btn-primary mb-4" href="{{ route('official-roles.create') }}">Tambah Official Role</a>
    <table class="table"><thead><tr><th>Kode</th><th>Nama</th><th>Status</th><th></th></tr></thead><tbody>
    @foreach ($officialRoles as $officialRole)
        <tr><td>{{ $officialRole->kode }}</td><td>{{ $officialRole->nama }}</td><td>{{ $officialRole->is_active ? 'Aktif' : 'Tidak aktif' }}</td><td>
            <a href="{{ route('official-roles.edit', $officialRole) }}">Edit</a>
            <form class="d-inline" method="POST" action="{{ route('official-roles.destroy', $officialRole) }}">@csrf @method('DELETE')<button type="submit">Hapus</button></form>
        </td></tr>
    @endforeach
    </tbody></table>
</div></div>
@endsection
