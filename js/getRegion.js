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
            res['data'].forEach(element => {
                document.querySelector('.kota').innerHTML += `<option value="${element['id']}">${element['name']}</option>`
            });
        }
    )
})
