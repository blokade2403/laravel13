<?php

namespace Database\Seeders;

use App\Models\MasterBackend\SettingUser\Position;
use App\Models\MasterBackend\SettingUser\PositionAssignment;
use App\Models\MasterBackend\SettingUser\PositionHierarchy;
use App\Models\MasterBackend\SettingUser\Unit;
use App\Models\MasterBackend\UserProfil\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | UNIT
        |--------------------------------------------------------------------------
        */

        $unit = Unit::create([
            'nama_unit' => 'Unit A',
            'kode_unit' => 'UNIT-A',
            'is_active' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | USERS
        |--------------------------------------------------------------------------
        */

        $kepala = User::create([
            'nip' => '198001010001',
            'nama' => 'Ahmad',
            'username' => 'ahmad',
            'email' => 'ahmad@example.test',
            'password' => Hash::make('password'),
            'status_user' => 'aktif',
        ]);

        $kabag = User::create([
            'nip' => '198001010002',
            'nama' => 'Budi',
            'username' => 'budi',
            'email' => 'budi@example.test',
            'password' => Hash::make('password'),
            'status_user' => 'aktif',
        ]);

        $ksp = User::create([
            'nip' => '198001010003',
            'nama' => 'Citra',
            'username' => 'citra',
            'email' => 'citra@example.test',
            'password' => Hash::make('password'),
            'status_user' => 'aktif',
        ]);

        $staf = User::create([
            'nip' => '198001010004',
            'nama' => 'Dedi',
            'username' => 'dedi',
            'email' => 'dedi@example.test',
            'password' => Hash::make('password'),
            'status_user' => 'aktif',
        ]);

        /*
        |--------------------------------------------------------------------------
        | POSITIONS
        |--------------------------------------------------------------------------
        */

        $kepalaPosition = Position::create([
            'unit_id' => $unit->id,
            'nama_jabatan' => 'Kepala Unit',
            'kode_jabatan' => 'KEPALA_UNIT',
            'level_jabatan' => 'KEPALA',
            'jenis_jabatan' => 'STRUKTURAL',
            'is_validator' => true,
            'is_active' => true,
        ]);

        $kabagPosition = Position::create([
            'unit_id' => $unit->id,
            'nama_jabatan' => 'Kabag',
            'kode_jabatan' => 'KABAG',
            'level_jabatan' => 'KABAG',
            'jenis_jabatan' => 'STRUKTURAL',
            'is_validator' => true,
            'is_active' => true,
        ]);

        $kspPosition = Position::create([
            'unit_id' => $unit->id,
            'nama_jabatan' => 'KSP',
            'kode_jabatan' => 'KSP',
            'level_jabatan' => 'KSP',
            'jenis_jabatan' => 'STRUKTURAL',
            'is_validator' => true,
            'is_active' => true,
        ]);

        $stafPosition = Position::create([
            'unit_id' => $unit->id,
            'nama_jabatan' => 'Staf',
            'kode_jabatan' => 'STAF',
            'level_jabatan' => 'STAF',
            'jenis_jabatan' => 'FUNGSIONAL',
            'is_validator' => false,
            'is_active' => true,
        ]);

        $ppkPosition = Position::create([
            'unit_id' => $unit->id,
            'nama_jabatan' => 'PPK',
            'kode_jabatan' => 'PPK',
            'level_jabatan' => 'PPK',
            'jenis_jabatan' => 'FUNGSIONAL',
            'is_validator' => true,
            'is_active' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | HIERARCHY
        |--------------------------------------------------------------------------
        */

        $this->hierarchy(
            $kepalaPosition,
            $kabagPosition,
            $unit->id
        );

        $this->hierarchy(
            $kabagPosition,
            $kspPosition,
            $unit->id
        );

        $this->hierarchy(
            $kspPosition,
            $stafPosition,
            $unit->id
        );

        /*
        |--------------------------------------------------------------------------
        | POSITION ASSIGNMENT
        |--------------------------------------------------------------------------
        */

        PositionAssignment::create([
            'position_id' => $kepalaPosition->id,
            'user_id' => $kepala->id,
            'unit_id' => $unit->id,
            'tanggal_mulai' => now()->startOfYear(),
            'assignment_type' => 'DEFINITIF',
            'is_primary' => true,
            'is_active' => true,
        ]);

        PositionAssignment::create([
            'position_id' => $kabagPosition->id,
            'user_id' => $kabag->id,
            'unit_id' => $unit->id,
            'tanggal_mulai' => now()->startOfYear(),
            'assignment_type' => 'DEFINITIF',
            'is_primary' => true,
            'is_active' => true,
        ]);

        PositionAssignment::create([
            'position_id' => $kspPosition->id,
            'user_id' => $ksp->id,
            'unit_id' => $unit->id,
            'tanggal_mulai' => now()->startOfYear(),
            'assignment_type' => 'DEFINITIF',
            'is_primary' => true,
            'is_active' => true,
        ]);

        PositionAssignment::create([
            'position_id' => $stafPosition->id,
            'user_id' => $staf->id,
            'unit_id' => $unit->id,
            'tanggal_mulai' => now()->startOfYear(),
            'assignment_type' => 'DEFINITIF',
            'is_primary' => true,
            'is_active' => true,
        ]);

        /*
        |--------------------------------------------------------------------------
        | RANGKAP JABATAN
        |--------------------------------------------------------------------------
        |
        | Budi = Kabag sekaligus PPK
        |
        */

        PositionAssignment::create([
            'position_id' => $ppkPosition->id,
            'user_id' => $kabag->id,
            'unit_id' => $unit->id,
            'tanggal_mulai' => now()->startOfYear(),
            'assignment_type' => 'DEFINITIF',
            'is_primary' => false,
            'is_active' => true,
        ]);
    }

    private function hierarchy(
        Position $parent,
        Position $child,
        string $unitId
    ): void {
        PositionHierarchy::create([
            'unit_id' => $unitId,
            'position_id' => $child->id,
            'parent_position_id' => $parent->id,
            'jenis_hubungan' => 'atasan',
            'tanggal_mulai' => now()->startOfYear(),
            'is_active' => true,
        ]);
    }
}
