<script>
    $(document).ready(function() {

        $('#btnPilihKomponen').click(function() {

            let selected = $('input[name="selected_barang"]:checked');

            if (selected.length === 0) {

                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Silakan pilih komponen terlebih dahulu'
                });

                return;
            }

            $('#nama_barang').val(
                selected.val()
            );

            $('#spesifikasi').val(
                selected.data('spek')
            );

            $('#harga_satuan').val(
                selected.data('harga')
            );

            $('#satuan_1').val(
                selected.data('satuan')
            );

            $('#kode_komponen').val(
                selected.data('kode')
            );

            $('#kt_modal_new_komponen').modal('hide');

        });

    });
</script>

<script>
    $(document).ready(function() {

        function hitungAnggaran() {

            let volume1 = parseFloat($('#volume_1').val()) || 0;
            let volume2 = parseFloat($('#volume_2').val()) || 0;
            let harga = parseFloat($('#harga_satuan').val()) || 0;
            let ppn = parseFloat($('#ppn').val()) || 0;

            // jumlah volume
            let jumlahVolume = volume1 * volume2;

            // subtotal
            let subtotal = jumlahVolume * harga;

            // cek ppn aktif
            let total = subtotal;

            if ($('#is_ppn').is(':checked')) {
                total = subtotal + (subtotal * ppn / 100);
            }

            $('#total_anggaran_real').val(total);

            $('#total_anggaran').val(
                total.toLocaleString('id-ID')
            );
        }

        $('#volume_1').on('keyup change', hitungAnggaran);
        $('#volume_2').on('keyup change', hitungAnggaran);
        $('#harga_satuan').on('keyup change', hitungAnggaran);
        $('#is_ppn').on('change', hitungAnggaran);

    });
</script>

<script>
    $(document).on('click', '.btn-show', function() {

        let url = $(this).data('url');

        console.log('URL:', url);

        $.ajax({
            url: url,
            type: 'GET',

            success: function(response) {
                console.log(response);
                $('#detail-barjas-content').html(response);
            },

            error: function(xhr) {

                console.log(xhr);

                $('#detail-barjas-content').html(`
                <div class="alert alert-danger m-5">
                    Error ${xhr.status}<br>
                    ${xhr.responseText}
                </div>
            `);
            }
        });

    });
</script>


<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', '.btn-submit', function() {

        let url = $(this).data('url');

        Swal.fire({
            title: 'Ajukan RKBU?',
            text: 'RKBU akan masuk proses validasi.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Ajukan',
            cancelButtonText: 'Batal'
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: url,
                    type: 'POST',
                    success: function(response) {

                        Swal.fire(
                            'Berhasil!',
                            response.message,
                            'success'
                        );

                        $('.dataTable').DataTable().ajax.reload();
                    },

                    // error: function(xhr) {

                    //     Swal.fire(
                    //         'Error!',
                    //         'Terjadi kesalahan',
                    //         'error'
                    //     );

                    // }

                   error: function(xhr) {

    console.log('STATUS:', xhr.status);
    console.log('RESPONSE:', xhr.responseText);

    Swal.fire({
        icon: 'error',
        title: 'Error',
        html: '<pre style="text-align:left;max-height:400px;overflow:auto;">'
            + xhr.responseText +
            '</pre>'
    });

}
                });

            }
        });
    });
</script>
