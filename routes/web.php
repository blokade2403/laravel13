<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\MasterBackend\SettingInput\AnggaranController;
use App\Http\Controllers\MasterBackend\SettingInput\JudulHeaderController;
use App\Http\Controllers\MasterBackend\SettingInput\KomponenController;
use App\Http\Controllers\MasterBackend\SettingInput\PpnController;
use App\Http\Controllers\MasterBackend\SettingInput\TahunAnggaranController;
use App\Http\Controllers\MasterBackend\SettingInput\TargetSpsController;
use App\Http\Controllers\MasterBackend\SettingInput\UraianDuaController;
use App\Http\Controllers\MasterBackend\SettingInput\UraianSatuController;
use App\Http\Controllers\MasterBackend\SettingRkbu\AktivitasController;
use App\Http\Controllers\MasterBackend\SettingRkbu\JenisBelanjaController;
use App\Http\Controllers\MasterBackend\SettingRkbu\JenisKategoriRkbuController;
use App\Http\Controllers\MasterBackend\SettingRkbu\KategoriRekeningController;
use App\Http\Controllers\MasterBackend\SettingRkbu\KategoriRkbuController;
use App\Http\Controllers\MasterBackend\SettingRkbu\KegiatanController;
use App\Http\Controllers\MasterBackend\SettingRkbu\ObyekBelanjaController;
use App\Http\Controllers\MasterBackend\SettingRkbu\ProgramController;
use App\Http\Controllers\MasterBackend\SettingRkbu\RekeningBelanjaController;
use App\Http\Controllers\MasterBackend\SettingRkbu\SubKategoriRekeningController;
use App\Http\Controllers\MasterBackend\SettingRkbu\SubKategoriRkbuController;
use App\Http\Controllers\MasterBackend\SettingRkbu\SubKegiatanController;
use App\Http\Controllers\MasterBackend\SettingRkbu\SumberDanaController;
use App\Http\Controllers\MasterBackend\SettingUser\FaseController;
use App\Http\Controllers\MasterBackend\SettingUser\OfficialRoleController;
use App\Http\Controllers\MasterBackend\SettingUser\PositionAssignmentController;
use App\Http\Controllers\MasterBackend\SettingUser\PositionController;
use App\Http\Controllers\MasterBackend\SettingUser\PositionDelegationController;
use App\Http\Controllers\MasterBackend\SettingUser\PositionHierarchyController;
use App\Http\Controllers\MasterBackend\SettingUser\RoleController;
use App\Http\Controllers\MasterBackend\SettingUser\RoleUserController;
use App\Http\Controllers\MasterBackend\SettingUser\UnitController;
use App\Http\Controllers\MasterBackend\SettingUser\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/', function () {
        return redirect()->route('login.form');
    });
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::prefix('setting-user')->group(function () {
    Route::resource('fases', FaseController::class);
    Route::resource('units', UnitController::class);
    Route::resource('positions', PositionController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('official-roles', OfficialRoleController::class);
    Route::resource('position-assignments', PositionAssignmentController::class)
        ->names('master.position-assignments');
    Route::resource('position-delegations', PositionDelegationController::class)
        ->names('master.position-delegations');
    Route::resource('position-hierarchies', PositionHierarchyController::class)
        ->names('master.position-hierarchies');
    Route::get('role-user', [RoleUserController::class, 'index'])->name('role_user.index');
    Route::post('role-user/assign', [RoleUserController::class, 'assign'])->name(
        'role_user.assign',
    );
    Route::delete('role-user/{user}/{role}', [RoleUserController::class, 'remove'])->name(
        'role_user.remove',
    );
});

Route::prefix('user-profil')->group(function () {
    Route::resource('users', UserController::class);
    Route::put('users/{id}/update-status', [UserController::class, 'updateStatus'])->name(
        'update.status',
    );
    // Route::resource('user-pptk', UserPptkController::class);
    // Route::resource('user-validator-roles', UserValidatorRoleController::class);
    // Route::resource('validator-roles', ValidatorRoleController::class);
});

Route::prefix('setting-input')->group(function () {
    Route::resource('tahun_anggarans', TahunAnggaranController::class);
    Route::resource('komponens', KomponenController::class);
    Route::resource('uraian_satus', UraianSatuController::class);
    Route::resource('uraian_duas', UraianDuaController::class);
    Route::resource('ppns', PpnController::class);
    Route::resource('judul_headers', JudulHeaderController::class);
    Route::resource('anggarans', AnggaranController::class);
    Route::resource('target_sps', TargetSpsController::class);
});

Route::prefix('setting-rkbu')->group(function () {
    Route::resource('aktivitas', AktivitasController::class);
    Route::resource('jenis_belanjas', JenisBelanjaController::class);
    Route::resource('programs', ProgramController::class);
    Route::resource('kegiatans', KegiatanController::class);
    Route::resource('sumber_danas', SumberDanaController::class);
    Route::resource('sub_kegiatans', SubKegiatanController::class);
    Route::resource('kategori_rekenings', KategoriRekeningController::class);
    Route::resource('jenis_kategori_rkbus', JenisKategoriRkbuController::class);
    Route::resource('obyek_belanjas', ObyekBelanjaController::class);
    Route::resource('kategori_rkbus', KategoriRkbuController::class);
    Route::resource('rekening_belanjas', RekeningBelanjaController::class);
    Route::resource('sub_kategori_rekenings', SubKategoriRekeningController::class);
    Route::resource('sub_kategori_rkbus', SubKategoriRkbuController::class);
});

// Route::middleware('auth')->group(
//     function () {
//         Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
//         Route::prefix('setting-user')->group(function () {
//             Route::resource('fases', FaseController::class);
//             Route::resource('units', UnitController::class);
//             Route::resource('positions', PositionController::class);
//             Route::resource('roles', RoleController::class);
//             Route::get('role-user', [RoleUserController::class, 'index'])->name('role_user.index');
//             Route::post('role-user/assign', [RoleUserController::class, 'assign'])->name(
//                 'role_user.assign',
//             );
//             Route::delete('role-user/{user}/{role}', [RoleUserController::class, 'remove'])->name(
//                 'role_user.remove',
//             );
//         });

//         Route::prefix('user-profil')->group(function () {
//             Route::resource('users', UserController::class);
//             Route::put('users/{id}/update-status', [UserController::class, 'updateStatus'])->name(
//                 'update.status',
//             );
//             // Route::resource('user-pptk', UserPptkController::class);
//             Route::resource('user-validator-roles', UserValidatorRoleController::class);
//             Route::resource('validator-roles', ValidatorRoleController::class);
//         });

//         Route::prefix('workflow')->group(function () {
//             Route::resource(
//                 'workflow-step-sub-kategori-rkbu',
//                 WorkflowStepSubKategoriRkbuController::class,
//             );
//             Route::resource('workflows', WorkflowController::class);
//             Route::resource('workflow_steps', WorkflowStepController::class);
//             // Route::resource('workflow_step_pptks', WorkflowStepPptkController::class);
//             Route::resource('position_sub_kategori_rkbu', PositionSubKategoriRkbuController::class);
//             // Route::resource('pptk_kategori', PptkKategoriController::class);
//             Route::get('/approvals', [ApprovalController::class, 'index'])->name('approvals.index');
//             Route::get('/approvals/{approval}', [ApprovalController::class, 'show'])->name(
//                 'approvals.show',
//             );
//             Route::post('/approvals/{approval}/approve', [ApprovalController::class, 'approve'])->name(
//                 'approvals.approve',
//             );
//             Route::post('/approvals/{approval}/reject', [ApprovalController::class, 'reject'])->name(
//                 'approvals.reject',
//             );
//         });

//         Route::prefix('workflow')
//             ->name('workflowapproval.')
//             ->group(function () {

//                 Route::get(
//                     '/approval',
//                     [WorkflowApprovalController::class, 'index']
//                 )->name('index');

//                 Route::get(
//                     '/approval/data',
//                     [WorkflowApprovalController::class, 'getData']
//                 )->name('data');

//                 Route::get(
//                     '/approval/{id}',
//                     [WorkflowApprovalController::class, 'show']
//                 )->name('show');
//                 Route::post(
//                     '/approval/{id}/approve',
//                     [WorkflowApprovalController::class, 'approve']
//                 )->name('approve');
//                 Route::post(
//                     '/approval/{id}/revision',
//                     [WorkflowApprovalController::class, 'revision']
//                 )->name('revision');

//                 Route::post(
//                     '/approval/{id}/reject',
//                     [WorkflowApprovalController::class, 'reject']
//                 )->name('reject');
//             });

//         Route::prefix('setting-input')->group(function () {
//             Route::resource('tahun_anggarans', TahunAnggaranController::class);
//             Route::resource('komponens', KomponenController::class);
//             Route::resource('uraian_satus', UraianSatuController::class);
//             Route::resource('uraian_duas', UraianDuaController::class);
//             Route::resource('ppns', PpnController::class);
//             Route::resource('judul_headers', JudulHeaderController::class);
//             Route::resource('anggarans', AnggaranController::class);
//             Route::resource('target_sps', TargetSpsController::class);
//         });

//         Route::prefix('setting-rkbu')->group(function () {
//             Route::resource('aktivitas', AktivitasController::class);
//             Route::resource('jenis_belanjas', JenisBelanjaController::class);
//             Route::resource('programs', ProgramController::class);
//             Route::resource('kegiatans', KegiatanController::class);
//             Route::resource('sumber_danas', SumberDanaController::class);
//             Route::resource('sub_kegiatans', SubKegiatanController::class);
//             Route::resource('kategori_rekenings', KategoriRekeningController::class);
//             Route::resource('jenis_kategori_rkbus', JenisKategoriRkbuController::class);
//             Route::resource('obyek_belanjas', ObyekBelanjaController::class);
//             Route::resource('kategori_rkbus', KategoriRkbuController::class);
//             Route::resource('rekening_belanjas', RekeningBelanjaController::class);
//             Route::resource('sub_kategori_rekenings', SubKategoriRekeningController::class);
//             Route::resource('sub_kategori_rkbus', SubKategoriRkbuController::class);
//         });
//     }
// );
