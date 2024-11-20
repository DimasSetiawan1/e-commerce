const url = 'https://ibnux.github.io/data-indonesia/'

$('.choose-provinsi').select2({
    placeholder: 'Pilih Provinsi',
    allowClear: true,
    width: '100%',
    minimumResultsForSearch: Infinity,
    ajax: {
        url: url + 'provinsi' + apikey,
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
            return {
                results: data['data'].map(function (item) {
                    return {
                        id: Number(item['id']),
                        text: String(item['name']).toLowerCase(),
                    }
                }),
            }
        },
    },
})
const getProvinsi = () =>
    fetch(url + 'provinsi?api_key=' + apikey, {
        method: 'GET',
    })
        .then((res) => res.json())
        .then((data) => {
            data['data'].forEach((element) => {
                $('#provinsi').append(
                    `<option value="${Number(element['id'])}">${String(
                        element['name']
                    ).toLowerCase()}</option>`
                )
            })
        })

const getKota = (id) => {
    fetch(url + 'kota/' + '?api_key=' + apikey + '&provinsi_id=' + id, {
        method: 'GET',
    })
        .then((res) => res.json())
        .then((data) => {
            $('#kota').empty()
            data['data'].forEach((element) => {
                $('#kota').append(
                    `<option value="${Number(element['id'])}">${String(
                        element['name']
                    ).toLowerCase()}</option>`
                )
            })
        })
}
