@extends('layouts.main')
@section('content')
    <div class="card">
        <div class="card-body"><a class="btn btn-primary mb-4" href="{{ route('master.position-assignments.create') }}">Tambah
                Assignment</a>
            <table class="table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Jabatan</th>
                        <th>Unit</th>
                        <th>Mulai</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($assignments as $item)
                        <tr>
                            <td>{{ $item->user?->name }}</td>
                            <td>{{ $item->position?->nama_jabatan }}</td>
                            <td>{{ $item->unit?->nama_unit }}</td>
                            <td>{{ $item->tanggal_mulai?->format('Y-m-d') }}</td>
                            <td>{{ $item->is_active ? 'Aktif' : 'Tidak aktif' }}</td>
                            <td><a href="{{ route('master.position-assignments.edit', $item) }}">Edit</a>
                                <form class="d-inline" method="POST"
                                    action="{{ route('master.position-assignments.destroy', $item) }}">@csrf
                                    @method('DELETE')<button>Nonaktifkan</button></form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>{{ $assignments->links() }}
        </div>
    </div>
@endsection
