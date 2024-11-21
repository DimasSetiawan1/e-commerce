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

    $('#addressModal').on('shown.bs.modal', async () => {
        const listProvinsi = await getProvinsi()
        selectProvinsi.select2({
            dropdownParent: $('#addressModal'),
            width: '100%',
            placeholder: 'Pilih Provinsi',
            allowClear: true,
            data: listProvinsi.map((provinsi) => {
                return {
                    id: Number(provinsi['id']),
                    text: String(provinsi['nama']),
                }
            }),
        })

        selectProvinsi.on('change', async function () {
            const provinsiId = $(this).val()
            if (provinsiId) {
                const listKota = await getKota(provinsiId)
                selectKota.select2({
                    dropdownParent: $('#addressModal'),
                    width: '100%',
                    placeholder: 'Pilih Kota',
                    allowClear: true,
                    data: listKota.map((kota) => {
                        return {
                            id: Number(kota['id']),
                            text: String(kota['nama']),
                        }
                    }),
                })
            } else {
                selectKota.empty()
            }
        })
    })
})
