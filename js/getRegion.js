const url = 'https://ibnux.github.io/data-indonesia/'

const getProvinsi = async () => {
    try {
        const response = await fetch(url + 'provinsi.json', {
            method: 'GET',
        })
        const data = await response.json()
        return data
    } catch (error) {
        console.error('Error fetching provinsi:', error)
        return []
    }
}

const getKota = async (provinsiId) => {
    try {
        const response = await fetch(
            url + 'kabupaten/' + `${provinsiId}.json`,
            {
                method: 'GET',
            }
        )
        const data = await response.json()
        return data
    } catch (error) {
        console.error('Error fetching kota:', error)
        return []
    }
}

$(document).ready(async function () {
    const selectProvinsi = $('#provinsi')
    const selectKota = $('#kota')
    let provinsiMap = {}
    const titleModal = $('#addressModalLabel').text()

    $('#addressModal').on('shown.bs.modal', async () => {
        const listProvinsi = await getProvinsi()
        if (!titleModal.includes('Tambah Alamat')) {
            selectProvinsi.val(null).trigger('change')
            selectKota.val(null).trigger('change')
        } else {
            provinsiMap = listProvinsi.reduce((acc, provinsi) => {
                acc[provinsi.id] = provinsi.nama
                return acc
            }, {})
            // Buat pemetaan ID ke nama provinsi
            selectProvinsi.select2({
                dropdownParent: $('#addressModal'),
                width: '100%',
                placeholder: 'Pilih Provinsi',
                allowClear: true,
                data: listProvinsi.map((provinsi) => {
                    return {
                        id: String(provinsi['nama']),
                        text: String(provinsi['nama']),
                        provinsiId: Number(provinsi.id),
                    }
                }),
            })

            selectProvinsi.on('change', async function () {
                const provinsiNama = $(this).val()
                if (provinsiNama) {
                    const provinsiId = getProvinsiIdByNama(provinsiNama)
                    const listKota = await getKota(provinsiId)

                    selectKota.select2({
                        dropdownParent: $('#addressModal'),
                        width: '100%',
                        placeholder: 'Pilih Kota',
                        allowClear: true,
                        data: listKota.map((kota) => {
                            return {
                                id: String(kota['nama']),
                                text: String(kota['nama']),
                            }
                        }),
                    })
                } else {
                    selectKota.empty()
                }
            })

            function getProvinsiIdByNama(nama) {
                return Object.keys(provinsiMap).find(
                    (key) => provinsiMap[key] === nama
                )
            }
        }
    })
})
