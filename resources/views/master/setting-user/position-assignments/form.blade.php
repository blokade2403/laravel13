@extends('layouts.main')
@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST"
                action="{{ isset($positionAssignment) ? route('master.position-assignments.update', $positionAssignment) : route('master.position-assignments.store') }}">
                @csrf @if (isset($positionAssignment))
                    @method('PUT')
                @endif
                <label>User</label><select class="form-select mb-3" name="user_id" @disabled(isset($positionAssignment)) required>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @selected(old('user_id', $positionAssignment?->user_id) == $user->id)>{{ $user->name }}</option>
                    @endforeach
                </select>
                <label>Jabatan</label><select class="form-select mb-3" name="position_id" required>
                    @foreach ($positions as $position)
                        <option value="{{ $position->id }}" @selected(old('position_id', $positionAssignment?->position_id) == $position->id)>{{ $position->nama_jabatan }}
                        </option>
                    @endforeach
                </select>
                <label>Unit</label><select class="form-select mb-3" name="unit_id" required>
                    @foreach ($units as $unit)
                        <option value="{{ $unit->id }}" @selected(old('unit_id', $positionAssignment?->unit_id) == $unit->id)>{{ $unit->nama_unit }}</option>
                    @endforeach
                </select>
                <label>Tipe Assignment</label><select class="form-select mb-3" name="assignment_type">
                    @foreach (['DEFINITIF', 'PLT', 'PLH', 'PENGGANTI'] as $type)
                        <option @selected(old('assignment_type', $positionAssignment?->assignment_type ?? 'DEFINITIF') === $type)>{{ $type }}</option>
                    @endforeach
                </select>
                <label>Mulai</label><input class="form-control mb-3" type="date" name="tanggal_mulai"
                    value="{{ old('tanggal_mulai', $positionAssignment?->tanggal_mulai?->format('Y-m-d')) }}"
                    required><label>Selesai</label><input class="form-control mb-3" type="date" name="tanggal_selesai"
                    value="{{ old('tanggal_selesai', $positionAssignment?->tanggal_selesai?->format('Y-m-d')) }}"><label>Keterangan</label>
                <textarea class="form-control mb-3" name="keterangan">{{ old('keterangan', $positionAssignment?->keterangan) }}</textarea><label><input type="checkbox" name="is_primary" value="1"
                        @checked(old('is_primary', $positionAssignment?->is_primary))> Primary</label><button
                    class="btn btn-primary d-block mt-3">Simpan</button>
            </form>
        </div>
    </div>
@endsection
