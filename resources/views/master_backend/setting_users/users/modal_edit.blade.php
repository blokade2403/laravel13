 @foreach ($users as $item)
     @php
         $activeAssignment = $item->activePositionAssignments->first();
     @endphp
     <div class="modal fade" id="kt_modal_edit_user_{{ $item->id }}" tabindex="-1" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered mw-650px">
             <div class="modal-content rounded">

                 <!--begin::Modal header-->
                 <div class="modal-header pb-0 border-0 justify-content-end">
                     <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">

                         <span class="svg-icon svg-icon-1">
                             <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none">

                                 <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                     transform="rotate(-45 6 17.3137)" fill="black" />

                                 <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                     transform="rotate(45 7.41422 6)" fill="black" />
                             </svg>
                         </span>
                     </div>
                 </div>
                 <!--end::Modal header-->

                 <!--begin::Modal body-->
                 <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">

                     <form action="{{ route('users.update', $item->id) }}" method="POST" class="form">
                         @csrf
                         @method('PUT')

                         <!--begin::Heading-->
                         <div class="mb-13 text-center">
                             <h1 class="mb-3">Edit User</h1>

                             <div class="text-muted fw-bold fs-5">
                                 Silahkan ubah data user sesuai kebutuhan
                             </div>
                         </div>
                          <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                         <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                             <span class="required">Nama User</span>
                             <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title=""
                                 data-bs-original-title="Nama Pegawai sesuai dengan data kepegawaian"
                                 aria-label="Nama Pegawai sesuai dengan data kepegawaian"></i>
                         </label>
                         <input type="text" class="form-control form-control-solid" placeholder="Enter Nama User"
                             name="nama" value="{{ old('nama', $item->nama) }}" required>
                         <div class="fv-plugins-message-container invalid-feedback"></div>
                     </div>
                     <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                         <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                             <span class="required">NIP User</span>
                             <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title=""
                                 data-bs-original-title="Nomor Induk Pegawai atau NRK Pegawai"
                                 aria-label="Nomor Induk Pegawai atau NRK Pegawai"></i>
                         </label>
                         <input type="text" class="form-control form-control-solid" placeholder="Enter NIP User"
                             name="nip" value="{{ old('nip', $item->nip) }}" required>
                         <div class="fv-plugins-message-container invalid-feedback"></div>
                     </div>
                     <div class="row g-9 mb-8">
                         <!--begin::Col-->
                         <div class="col-md-6 fv-row fv-plugins-icon-container">
                             <label class="required fs-6 fw-bold mb-2">Username</label>
                             <input type="text" class="form-control form-control-solid" placeholder="Enter Username"
                                 name="username" value="{{ old('username', $item->username) }}" required>
                             <div class="fv-plugins-message-container invalid-feedback"></div>
                         </div>
                         <div class="col-md-6 fv-row">
                             <label class="required fs-6 fw-bold mb-2">Email</label>
                             <div class="position-relative d-flex align-items-center">
                                 <input class="form-control form-control-solid" placeholder="Enter Email" name="email"
                                     type="email" value="{{ old('email', $item->email) }}">
                             </div>
                             <!--end::Input-->
                         </div>
                         <!--end::Col-->
                     </div>
                     <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                         <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                             <span class="required">Password</span>
                             <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title=""
                                 data-bs-original-title="Password untuk login" aria-label="Password untuk login"></i>
                         </label>
                         <input type="password" class="form-control form-control-solid"  placeholder="Kosongkan jika tidak diubah"
                             name="password">
                         <div class="fv-plugins-message-container invalid-feedback"></div>
                     </div>
                     <div class="d-flex flex-stack mb-8">
                         <!--begin::Label-->
                         <div class="me-5">
                             <label class="fs-6 fw-bold">Status Aktif User</label>
                             <div class="fs-7 fw-bold text-muted">
                                 Status aktif untuk login
                             </div>
                         </div>
                         <!--end::Label-->

                         <!--begin::Switch-->
                         <label class="form-check form-switch form-check-custom form-check-solid">
                             <input type="hidden" name="status_user" value="nonaktif">
                             <input class="form-check-input" type="checkbox" name="status_user"  value="aktif"
                                 {{ old('status_user', $item->status_user) == 'aktif' ? 'checked' : '' }}>
                             <span class="form-check-label fw-bold text-muted">
                                 Aktif
                             </span>
                         </label>
                         <!--end::Switch-->
                     </div>
                     <div class="row g-9 mb-8">
                         <div class="col-md-6 fv-row">
                             <label class="required fs-6 fw-bold mb-2">Unit</label>
                             <select class="form-select form-select-solid" name="unit_id" data-control="select2"
                                 data-placeholder="Select an option">
                                 <option value="">Pilih unit</option>
                                 @foreach ($units as $unit)
                                     <option value="{{ $unit->id }}"
                                         {{ old('unit_id', $activeAssignment?->unit_id) === $unit->id ? 'selected' : '' }}>
                                         {{ $unit->nama_unit }}
                                     </option>
                                 @endforeach
                             </select>
                         </div>
                         <div class="col-md-6 fv-row">
                             <label class="required fs-6 fw-bold mb-2">Jabatan</label>
                             <select class="form-select form-select-solid" name="position_id" data-control="select2"
                                 data-placeholder="Select an option">
                                 <option value="">Pilih jabatan</option>
                                 @foreach ($positions as $position)
                                     <option value="{{ $position->id }}"
                                         {{ old('position_id', $activeAssignment?->position_id) === $position->id ? 'selected' : '' }}>
                                         {{ $position->nama_jabatan }}
                                     </option>
                                 @endforeach
                             </select>
                         </div>
                     </div>
                       <div class="d-flex flex-column mb-8 fv-row">

                             <label class="required fs-6 fw-bold mb-2">
                                 Roles
                             </label>

                             <select name="roles[]" class="form-select form-select-solid" data-control="select2"
                                 multiple required>
                                 @foreach ($roles as $role)
                                     <option value="{{ $role->id }}"
                                         {{ $item->roles->contains('id', $role->id) ? 'selected' : '' }}>
                                         {{ $role->nama_role }}
                                     </option>
                                 @endforeach
                             </select>

                         </div>
                     <div class="mb-15 fv-row">
                         <!--begin::Wrapper-->
                         <div class="d-flex flex-stack">
                             <!--begin::Label-->
                             <div class="fw-bold me-5">
                                 <label class="fs-6">Notifications</label>
                                 <div class="fs-7 text-muted">Allow Notifications by Phone or Email</div>
                             </div>
                             <!--end::Label-->
                             <!--begin::Checkboxes-->
                             <div class="d-flex align-items-center">
                                 <!--begin::Checkbox-->
                                 <label class="form-check form-check-custom form-check-solid me-10">
                                     <input class="form-check-input h-20px w-20px" type="checkbox"
                                         name="communication[]" value="email" checked="checked">
                                     <span class="form-check-label fw-bold">Email</span>
                                 </label>
                                 <!--end::Checkbox-->
                                 <!--begin::Checkbox-->
                                 <label class="form-check form-check-custom form-check-solid">
                                     <input class="form-check-input h-20px w-20px" type="checkbox"
                                         name="communication[]" value="phone">
                                     <span class="form-check-label fw-bold">Phone</span>
                                 </label>
                                 <!--end::Checkbox-->
                             </div>
                             <!--end::Checkboxes-->
                         </div>
                         <!--end::Wrapper-->
                     </div>

                         <!-- Submit -->
                         <div class="text-center">

                             <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">
                                 Cancel
                             </button>

                             <button type="submit" class="btn btn-primary">
                                 <span class="indicator-label">
                                     Update User
                                 </span>
                             </button>

                         </div>

                     </form>

                 </div>
                 <!--end::Modal body-->

             </div>
         </div>
     </div>
 @endforeach
