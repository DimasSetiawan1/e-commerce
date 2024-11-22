const createAddress = () => {
    $.ajax({
        url: './utils/profileHandler.php',
        type: 'POST',
        data: $('#addressForm').serialize(),
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                customAlert({
                    status: 'success',
                    title: 'Sukses!',
                    message: 'Alamat berhasil ditambahkan',
                })
            } else {
                customAlert({
                    status: 'error',
                    title: 'Oops...',
                    message: 'Gagal menambahkan alamat',
                })
            }
        },
        error: function () {
            customAlert({
                status: 'error',
                title: 'Oops...',
                message: 'Terjadi kesalahan saat memproses permintaan Anda',
            })
        },
    })
}

const setDefaultAddress = (id) => {
    $.ajax({
        url: './utils/profileHandler.php',
        type: 'POST',
        data: 'action=set_default&address_id=' + id,
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                customAlert({
                    status: 'success',
                    title: 'Sukses!',
                    message: 'Alamat sebagai alamat default berhasil diubah',
                })
            } else {
                customAlert({
                    status: 'error',
                    title: 'Oops...',
                    message: 'Gagal mengubah alamat sebagai alamat default',
                })
            }
        },
        error: function () {
            customAlert({
                status: 'error',
                title: 'Oops...',
                message: 'Terjadi kesalahan saat memproses permintaan Anda',
            })
        },
    })
}

const editAddress = (id) => {
    $('#phoneNumber').val('123')
    $('#addressModal').modal('show')

    $.ajax({
        url: './utils/profileHandler.php',
        type: 'GET',
        data: { id: id },
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                $('#nama_lengkap').val(response.addresses.nama)
                $('#phoneNumber').val(response.addresses.nomor_telepon)
                $('#provinsi').val(response.addresses.provinsi)
                $('#kota').val(response.addresses.kota)
                $('#kode_pos').val(response.addresses.kode_pos)
                $('#fullAddress').val(response.addresses.alamat_lengkap)
                $('#detail_alamat').val(response.addresses.detail_alamat)
                $(
                    `input[name="label"][value="${response.addresses.label}"]`
                ).prop('checked', true)

                // Ubah judul modal dan action form
                $('#addressModalLabel').text('Edit Alamat')
                $('input[name="action"]').val('edit')
                $('#addressModal').modal('show')
            } else {
                customAlert({
                    status: 'error',
                    title: 'Oops...',
                    message: 'Gagal mendapatkan alamat',
                })
            }
        },
        error: function () {
            customAlert({
                status: 'error',
                title: 'Oops...',
                message: 'Terjadi kesalahan saat memproses permintaan Anda',
            })
        },
    })
}
