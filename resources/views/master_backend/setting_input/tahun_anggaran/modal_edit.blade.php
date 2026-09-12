@foreach ($tahun_anggarans as $item)
    <div class="modal fade" id="modal_edit_tahun_anggaran_{{ $item->id }}" tabindex="-1" aria-hidden="true">
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
                    <!--begin:Form-->
                    <form id="kt_modal_new_target_form"
                        class="form fv-plugins-bootstrap5 fv-plugins-framework form-submit" method="POST"
                        action="{{ route('tahun_anggarans.update', $item->id) }}">
                        @csrf
                        @method('PUT')
                        <!--begin::Heading-->
                        <div class="mb-13 text-center">
                            <!--begin::Title-->
                            <h1 class="mb-3">Edit Tahun Anggaran</h1>
                            <!--end::Title-->
                            <!--begin::Description-->
                            <div class="text-muted fw-bold fs-5">If you need more info, please check
                                <a href="#" class="fw-bolder link-primary">Project Guidelines</a>.
                            </div>
                            <!--end::Description-->
                        </div>
                        <!--end::Heading-->
                        <!--begin::Input group-->
                        <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span class="required">Tahun</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title=""
                                    data-bs-original-title="Tahun anggaran yang akan digunakan"
                                    aria-label="Tahun anggaran yang akan digunakan"></i>
                            </label>
                            <input type="number" class="form-control form-control-solid"
                                placeholder="Contoh: 2026" name="tahun" min="2000" max="2100"
                                value="{{ $item->tahun }}">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                        <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span class="required">Nama Tahun Anggaran</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title=""
                                    data-bs-original-title="Nama label untuk tahun anggaran"
                                    aria-label="Nama label untuk tahun anggaran"></i>
                            </label>
                            <input type="text" class="form-control form-control-solid"
                                placeholder="Enter Tahun Anggaran Name" name="nama_tahun_anggaran"
                                value="{{ $item->nama_tahun_anggaran }}">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                        </div>
                        <div class="row g-9 mb-8">
                            <div class="col-md-12 fv-row">
                                <label class="required fs-6 fw-bold mb-2">Status</label>
                                <select class="form-select" name="status" data-control="select2"
                                    data-placeholder="Select an option">
                                    <option value="{{ $item->status }}" data-select2-id="select2-data-63-6qoa">
                                        {{ $item->status }}
                                    </option>
                                    <option value="aktif" {{ $item->status == 'aktif' ? 'selected' : '' }}>Aktif
                                    </option>
                                    <option value="nonaktif" {{ $item->status == 'nonaktif' ? 'selected' : '' }}>
                                        Non-Aktif</option>
                                </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                        </div>
                      @include('components.modal.button_modal')
                        <div></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
