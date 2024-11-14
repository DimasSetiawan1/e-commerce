function currencyFormat(
    number,
    currency = 'IDR',
    locale = 'id-ID',
    decimals = 0
) {
    return new Intl.NumberFormat(locale, {
        style: 'currency',
        currency: currency,
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    }).format(number)
}
function updateTotal() {
    const totalPrice = document.querySelectorAll('.total')
    let subtotal = 0
    totalPrice.forEach((element) => {
        subtotal =
            subtotal + parseInt(element.textContent.replace(/[^0-9]/g, ''))
    })

    // document.getElementById("subtotal").textContent = currencyFormat(subtotal)
    // let discount = parseInt(document.querySelector('.discount').textContent.replace('%', ''))
    // let total =  discount != 0 ? subtotal * (discount/100) : subtotal
    // document.getElementById("total").textContent = currencyFormat(total)
}
updateTotal()

document.querySelectorAll('.add, .minus').forEach((button) => {
    button.addEventListener('click', function () {
        const itemId = this.getAttribute('data-id')
        const total = document.querySelector(`.total-${itemId}`)
        let quantity = parseInt(
            document.getElementById(`quantity-${itemId}`).value
        )
        let price = parseInt(
            total.getAttribute('data-price').replace(/[^0-9]/g, '')
        )
        // const isAddAction = this.classList.contains('add');

        const xhr = new XMLHttpRequest()
        xhr.open('POST', './utils/update_cart.php', true)
        xhr.setRequestHeader(
            'Content-Type',
            'application/x-www-form-urlencoded'
        )
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                let data = JSON.parse(xhr.responseText)['data']
                document.getElementById(`quantity-${itemId}`).value =
                    data['quantity']
                total.textContent = currencyFormat(data['total'])
                updateTotal()
            }
        }

        xhr.send(
            `action=updateCart&id=${itemId}&qty=${quantity}&price=${price}`
        )
        console.log(itemId, quantity, price)
    })
})
let isDiscount = false

function applyDiscount(value) {
    if (isDiscount) return
    isDiscount = true

    const xhr = new XMLHttpRequest()
    xhr.open('POST', './utils/update_cart.php', true)
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            try {
                console.log(xhr.responseText)
                let response = JSON.parse(xhr.responseText)
                const data = response['data']

                updateTotal()
                document.querySelector('.text-notification').textContent =
                    'Voucher discount applied!'
                $('#alert').removeClass('alert-danger')
                $('#alert').addClass('alert-success')
                $('#alert').removeClass('d-none')
                setTimeout(function () {
                    $('#alert').addClass('d-none')
                }, 5000)
                isDiscount = true
            } catch (error) {
                console.log(error)
            }
        } else {
            document.querySelector('.text-notification').textContent =
                'Voucher discount not found!'
            $('#alert').addClass('alert-danger')
            $('#alert').removeClass('d-none')
            setTimeout(function () {
                $('#alert').addClass('d-none')
            }, 2000)
            isDiscount = false
        }
    }
    xhr.send(`action=getDiscount&voucher=${value}`)
}

function removeCart(id) {
    console.log(id)
    const xhr = new XMLHttpRequest()
    xhr.open('POST', './utils/update_cart.php', true)
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            let data = JSON.parse(xhr.responseText)
            if (data['status'] == 'success') {
                const alertElement =
                    document.querySelector('statusSuccessModal')
                const alertMsg = document.querySelector('.msg')
                setTimeout(() => {
                    alertMsg.textContent = data['message']
                    if (alertElement) {
                        alertElement.classList.remove('fade')
                        alertElement.classList.add('show')
                    }
                }, 2000)

                alertElement.remove()
                updateTotal()
            }
        }
    }

    xhr.send(`action=removeCart&id=${id}`)
}

document
    .getElementById('addDiscount')
    .addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault()
            applyDiscount(this.value)
        }
    })

function toCheckout() {
    window.location.href = 'checkout.php'
}
