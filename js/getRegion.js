const url = "https://api.goapi.io/regional/"
const apikey = "14e6e7fc-edd1-5fbf-3545-19a5b0fa"

let getProvince = (apikey) => fetch(url + "provinsi?api_key=" + apikey, {
    method: "GET"
}).then(
    res => res.json()
)

getCity = (apikey, id) => fetch(url + "kota/" + "?api_key=" + apikey + "&provinsi_id=" + id, {
    method: "GET"
}).then(
    res => res.json()
)

getKecamatan = (apikey, id) => fetch(url + "kecamatan/" + "?api_key=" + apikey + "&kota_id=" + id, {
    method: "GET"
}).then(
    res => res.json()
)

getKelurahan = (apikey, id) => fetch(url + "kelurahan/" + "?api_key=" + apikey + "&kecamatan_id=" + id, {
    method: "GET"
}).then(
    res => res.json()
)
getProvince(apikey).then(
    res => {
        res['data'].forEach(element => {
            document.querySelector('.province').innerHTML += `<option value="${element['id']}">${element['name']}</option>`
        });
    }
)

document.querySelector('.province').addEventListener('change', (e) => {
    getCity(apikey, e.target.value).then(
        res => {
            document.querySelector('.kota').innerHTML = ""
            console.log(res)
            res['data'].forEach(element => {
                document.querySelector('.kota').innerHTML += `<option value="${element['id']}">${element['name']}</option>`
            });
        }
    )
})


document.querySelector('.kota').addEventListener('change', (e) => {
    getKecamatan(apikey, e.target.value).then(
        res => {
            document.querySelector('.kecamatan').innerHTML = ""
            res['data'].forEach(element => {
                document.querySelector('.kecamatan').innerHTML += `<option value="${element['id']}">${element['name']}</option>`
            });
        }
    )
})


document.querySelector('.kecamatan').addEventListener('change', (e) => {
    getKelurahan(apikey, e.target.value).then(
        res => {
            document.querySelector('.kelurahan').innerHTML = ""
            res['data'].forEach(element => {
                document.querySelector('.kelurahan').innerHTML += `<option value="${element['id']}">${element['name']}</option>`
            });
        }
    )
})