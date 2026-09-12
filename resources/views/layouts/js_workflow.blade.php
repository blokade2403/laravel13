{{-- <script>
    $('#tableApproval').DataTable({
        processing: true,
        serverSide: true,

        ajax: "{{ route('workflowapproval.data') }}",

        columns: [{
                data: 'DT_RowIndex',
                searchable: false,
                orderable: false
            },
            {
                data: 'nomor_dokumen'
            },
            {
                data: 'status'
            },
            {
                data: 'action',
                searchable: false,
                orderable: false
            }
        ]
    });
</script> --}}

<script>
    
    
    let approvalId = null;

    $(document).on('click', '.btn-detail', function() {

        approvalId = $(this).data('id');

       $.get($(this).data('url'), function(res) {

    console.log(res);

    // ==========================
    // Header RKBU
    // ==========================
    $('#detail_nomor_rkbu').text(res.rkbu?.nomor_rkbu ?? '-');

    $('#detail_unit').text(res.rkbu?.unit?.nama_unit ?? '-');

    $('#detail_tahun').text(res.rkbu?.tahun_anggaran?.nama_tahun_anggaran ?? '-');

    $('#detail_validator').text(res.approval?.validatorRole?.nama_validator ?? '-');

    $('#detail_step').text(res.approval?.workflowStep?.level_jabatan ?? '-');

    $('#detail_status').html(
        `<span class="badge badge-warning">
            ${res.approval?.workflowInstance?.status ?? '-'}
        </span>`
    );

    $('#detail_tanggal').text(res.approval?.created_at ?? '-');

    // ==========================
    // Detail Barang
    // ==========================

    let html = '';

    $.each(res.details, function(i, item) {

        html += `
        <tr>
            <td>${i+1}</td>
            <td>${item.nama_barang}</td>
            <td>${item.spesifikasi}</td>
            <td>${item.jumlah_volume}</td>
            <td>${item.satuan_1}</td>
            <td>${Number(item.harga_satuan).toLocaleString('id-ID')}</td>
            <td>${Number(item.total_anggaran).toLocaleString('id-ID')}</td>
        </tr>`;
    });

    $('#tbodyBarang').html(html);

    const modal = new bootstrap.Modal(
        document.getElementById('modalDetailApproval')
    );

    modal.show();

});

    });
</script>

<script>
    
    $('#btnApprove').click(function() {

        $.ajax({

            url: '/workflow/approval/' + approvalId + '/approve',

            type: 'POST',

            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                note: $('#approval_note').val()
            },

            success: function() {

                $('#modalDetailApproval').modal('hide');

                $('#tableApproval').DataTable().ajax.reload();

                toastr.success('Approval berhasil');

            }

             error: function(xhr){
            console.log(xhr.responseText);
        }

        });

    });
</script>

<script>
    $('#btnReject').click(function() {

        if ($('#approval_note').val() == '') {
            toastr.error('Catatan reject wajib diisi');
            return;
        }

        $.ajax({

            url: '/workflow/approval/' + approvalId + '/reject',

            type: 'POST',

            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                note: $('#approval_note').val()
            },

            success: function() {

                $('#modalDetailApproval').modal('hide');

                $('#tableApproval').DataTable().ajax.reload();

                toastr.success('Dokumen berhasil direject');

            }

        });

    });
</script>

<script>
   $('#btnRevision').click(function() {

    if ($('#approval_note').val() == '') {
        toastr.error('Catatan revisi wajib diisi');
        return;
    }

    $.ajax({

        url: '/workflow/approval/' + approvalId + '/revision',

        type: 'POST',

        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            note: $('#approval_note').val()
        },

        success: function(res) {

            $('#modalDetailApproval').modal('hide');

            $('#tableApproval')
                .DataTable()
                .ajax.reload();

            toastr.success(res.message);
        },

        error: function(xhr) {

            console.log(xhr);

            toastr.error(
                xhr.responseJSON?.message ??
                'Terjadi kesalahan'
            );
        }
    });
});
</script>

<script>
    $('#validator_type').change(function(){

    let type = $(this).val();

    $('#hierarchy_area').hide();

    $('#position_area').hide();

    $('#role_area').hide();

    if(type=='hierarchy'){

        $('#hierarchy_area').show();

    }

    if(type=='position'){

        $('#position_area').show();

    }

    if(type=='role'){

        $('#role_area').show();

    }

});

$('#validator_type').trigger('change');
</script>

{{-- <script>
    
  $(document).on('click', '.btn-detail', function() {

    console.log('DETAIL CLICK');

    let url = $(this).data('url');

    console.log(url);

    $.ajax({
        url: url,
        type: 'GET',

        success: function(res) {

            console.log(res);

            $('#nomor_rkbu').text(res.rkbu.nomor_rkbu ?? '-');
            $('#unit').text(res.rkbu.unit?.nama_unit ?? '-');
            $('#tahun').text(res.rkbu.tahun_anggaran?.tahun ?? '-');

            let html = '';

            $.each(res.rkbu.detail_barjas ?? [], function(i, item) {

                html += `
                    <tr>
                        <td>${item.nama_barang ?? ''}</td>
                        <td>${item.spesifikasi ?? ''}</td>
                        <td>${item.volume_1 ?? ''}</td>
                        <td>${item.harga_satuan ?? ''}</td>
                    </tr>
                `;
            });

            $('#tableDetailBarang tbody').html(html);

            let modal = new bootstrap.Modal(
                document.getElementById('modalDetailApproval')
            );

            modal.show();
        },

        error: function(xhr) {

            console.log(xhr);

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: xhr.responseJSON?.message ??
                    xhr.responseText
            });
        }
    });

});
</script> --}}
