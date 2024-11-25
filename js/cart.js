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

document.addEventListener('DOMContentLoaded', function () {
    updateTotal({})
})

function updateTotal({}) {
    let subtotal = 0
    const priceElements = document.querySelectorAll('[data-price]')

    for (const item of priceElements) {
        try {
            const price =
                parseFloat(
                    item.getAttribute('data-price').replace(/[^0-9.-]+/g, '')
                ) || 0
            const quantityElement = item
                .closest('.row')
                ?.querySelector('[id^="quantity-"]')
            const quantity = parseInt(quantityElement?.value) || 0
            subtotal += price * quantity
        } catch (error) {
            console.error('Error calculating item total:', error)
        }
    }

    const subtotalElement = document.getElementById('subtotal')
    const totalElement = document.getElementById(`total`)

    if (subtotalElement) subtotalElement.textContent = currencyFormat(subtotal)
    if (totalElement) totalElement.textContent = currencyFormat(subtotal)

    const id = document.querySelectorAll('[data-discount-id]')
    let totalDiscount = 0

    for (const itemId of id) {
        const discountElement = itemId.querySelector(`#totalDiscount`)
        const discountPercentageElement =
            itemId.querySelector(`#discountPercentage`)
        const dataDiscount =
            parseInt(discountElement?.getAttribute('data-discount')) || 0
        if (discountPercentageElement) {
            discountPercentageElement.textContent = `Discount ${dataDiscount}%`
        }

        if (dataDiscount !== 0) {
            const discountAmount = subtotal * (dataDiscount / 100)

            totalDiscount += discountAmount

            if (discountElement) {
                discountElement.textContent = `${currencyFormat(
                    discountAmount
                )}`
            }
        }
    }

    const discountedTotal = subtotal - totalDiscount

    if (totalElement) {
        totalElement.textContent = currencyFormat(discountedTotal)
    }
}

document.getElementById('checkoutBtn').addEventListener('click', () => {
    window.location.href = './checkout.php'
})

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
                total.textContent = currencyFormat(
                    data['price'] * data['quantity']
                )
                updateTotal({})
            }
        }

        xhr.send(
            `action=updateCart&id=${itemId}&qty=${quantity}&price=${price}`
        )
    })
})

function applyDiscount(value) {
    const xhr = new XMLHttpRequest()
    xhr.open('POST', './utils/update_cart.php', true)
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            try {
                let response = JSON.parse(xhr.responseText)
                if (response['data'] !== undefined) {
                    const data = response['data']
                    document.querySelector('.text-notification').textContent =
                        'Voucher discount applied!'
                    $('#alert').removeClass('alert-danger')
                    $('#alert').addClass('alert-success')
                    $('#alert').removeClass('d-none')
                    setTimeout(function () {
                        $('#alert').addClass('d-none')
                    }, 5000)
                    location.reload()
                } else {
                    document.querySelector(
                        '.text-notification'
                    ).textContent = `${response['message']}`
                    $('#alert').addClass('alert-danger')
                    $('#alert').removeClass('d-none')
                    setTimeout(function () {
                        $('#alert').addClass('d-none')
                    }, 2000)
                }
            } catch (error) {
                // console.log(error)
                document.querySelector('.text-notification').textContent =
                    'An error occurred while applying the discount!'
                $('#alert').addClass('alert-danger')
                $('#alert').removeClass('d-none')
                setTimeout(function () {
                    $('#alert').addClass('d-none')
                }, 2000)
            }
        } else {
            document.querySelector('.text-notification').textContent =
                'Voucher discount not found!'
            $('#alert').addClass('alert-danger')
            $('#alert').removeClass('d-none')
            setTimeout(function () {
                $('#alert').addClass('d-none')
            }, 2000)
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
                customAlert({
                    title: 'Success',
                    message: 'Barang Berhasil dihapus dari Keranjang!',
                    status: 'success',
                })
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
