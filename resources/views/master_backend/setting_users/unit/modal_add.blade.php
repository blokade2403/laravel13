 <div class="modal fade" id="kt_modal_new_target" tabindex="-1" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered mw-650px">
         <div class="modal-content rounded">
             <div class="modal-header pb-0 border-0 justify-content-end">
                 <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                     <span class="svg-icon svg-icon-1">
                         <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                             fill="none">
                             <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                 transform="rotate(-45 6 17.3137)" fill="black"></rect>
                             <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                 transform="rotate(45 7.41422 6)" fill="black"></rect>
                         </svg>
                     </span>
                 </div>
             </div>
             <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                 <form id="kt_modal_new_target_form" class="form fv-plugins-bootstrap5 fv-plugins-framework form-submit"
                     action="{{ route('units.store') }}" method="post">
                     @csrf
                     <div class="mb-13 text-center">
                         <h1 class="mb-3">Set Add Data</h1>
                         <div class="text-muted fw-bold fs-5">Tambah Data Unit.
                         </div>
                     </div>
                     <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                         <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                             <span class="required">Kode Unit</span>
                             <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title=""
                                 data-bs-original-title="Specify a target name for future usage and reference"
                                 aria-label="Specify a target name for future usage and reference"></i>
                         </label>
                         <input type="text" class="form-control form-control-solid" placeholder="Enter Kode Unit" name="kode_unit" required>
                         <div class="fv-plugins-message-container invalid-feedback"></div>
                     </div>
                      <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                         <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                             <span class="required">Nama Unit</span>
                             <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title=""
                                 data-bs-original-title="Specify a target name for future usage and reference"
                                 aria-label="Specify a target name for future usage and reference"></i>
                         </label>
                         <input type="text" class="form-control form-control-solid" placeholder="Enter Nama Unit"
                             name="nama_unit">
                         <div class="fv-plugins-message-container invalid-feedback"></div>
                     </div>
                      <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                         <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                             <span class="required">Jenis Unit</span>
                             <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title=""
                                 data-bs-original-title="Specify a target name for future usage and reference"
                                 aria-label="Specify a target name for future usage and reference"></i>
                         </label>
                         <input type="text" class="form-control form-control-solid" placeholder="Enter Jenis Unit"
                             name="jenis_unit" required>
                         <div class="fv-plugins-message-container invalid-feedback"></div>
                     </div>
                     <div class="d-flex flex-column mb-8 fv-row">
                         <label class="fs-6 fw-bold mb-2">Parent Unit</label>
                         <select class="form-select form-select-solid" name="parent_id" data-control="select2"
                             data-placeholder="Pilih parent unit">
                             <option value="">Tanpa Parent</option>
                             @foreach ($units as $parent)
                                 <option value="{{ $parent->id }}">
                                     {{ $parent->kode_unit }} - {{ $parent->nama_unit }}
                                 </option>
                             @endforeach
                         </select>
                     </div>
                      <div class="d-flex flex-stack mb-8">
                         <!--begin::Label-->
                         <div class="me-5">
                             <label class="fs-6 fw-bold">Status Unit</label>
                             <div class="fs-7 fw-bold text-muted">
                                 Status Unit untuk login
                             </div>
                         </div>
                         <!--end::Label-->

                         <!--begin::Switch-->
                         <label class="form-check form-switch form-check-custom form-check-solid">
                             <input class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active', true))>
                             <span class="form-check-label fw-bold text-muted">
                                 Aktif
                             </span>
                         </label>
                         <!--end::Switch-->
                     </div>
                     <!--begin::Actions-->
                     <div class="text-center">
                         <button type="reset" id="kt_modal_new_target_cancel"
                             class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                         <button type="submit" id="kt_modal_new_target_submit" class="btn btn-primary">
                             <span class="indicator-label">Submit</span>
                             <span class="indicator-progress">Please wait...
                                 <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                         </button>
                     </div>
                     <!--end::Actions-->
                     <div></div>
                 </form>
                 <!--end:Form-->
             </div>
             <!--end::Modal body-->
         </div>
         <!--end::Modal content-->
     </div>
     <!--end::Modal dialog-->
 </div>
