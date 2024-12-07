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
    const totalElement = document.getElementById(`total`)

    if (totalElement) totalElement.textContent = currencyFormat(subtotal)
}

document.querySelectorAll('.add, .minus').forEach((button) => {
    button.addEventListener('click', function () {
        const itemId = this.getAttribute('data-id')
        const action = this.classList.contains('add')
            ? 'increment'
            : 'decrement'

        updateQuantity({
            action: action,
            itemId: itemId,
            total: document.querySelector(`.total-${itemId}`),
        })
    })
})

function updateQuantity({ action, itemId, total }) {
    const xhr = new XMLHttpRequest()
    xhr.open('POST', './api/cart.php', true)
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            let data = JSON.parse(xhr.responseText)['data']
            document.getElementById(`quantity-${itemId}`).value =
                data['quantity']
            total.textContent = currencyFormat(data['price'] * data['quantity'])
            updateTotal({})
        }
    }

    switch (action) {
        case 'increment':
            xhr.send(`action=incrementCart&id=${itemId}`)
            break
        case 'decrement':
            xhr.send(`action=decrementCart&id=${itemId}`)
            break
        default:
            break
    }
}

// function applyDiscount(value) {
//     const xhr = new XMLHttpRequest()
//     xhr.open('POST', './api/cart.php', true)
//     xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
//     xhr.onreadystatechange = function () {
//         if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
//             try {
//                 let response = JSON.parse(xhr.responseText)
//                 if (response['data'] !== undefined) {
//                     const data = response['data']
//                     document.querySelector('.text-notification').textContent =
//                         'Voucher discount applied!'
//                     $('#alert').removeClass('alert-danger')
//                     $('#alert').addClass('alert-success')
//                     $('#alert').removeClass('d-none')
//                     setTimeout(function () {
//                         $('#alert').addClass('d-none')
//                     }, 5000)
//                 } else {
//                     document.querySelector(
//                         '.text-notification'
//                     ).textContent = `${response['message']}`
//                     $('#alert').addClass('alert-danger')
//                     $('#alert').removeClass('d-none')
//                     setTimeout(function () {
//                         $('#alert').addClass('d-none')
//                     }, 2000)
//                 }
//             } catch (error) {
//                 // console.log(error)
//                 document.querySelector('.text-notification').textContent =
//                     'An error occurred while applying the discount!'
//                 $('#alert').addClass('alert-danger')
//                 $('#alert').removeClass('d-none')
//                 setTimeout(function () {
//                     $('#alert').addClass('d-none')
//                 }, 2000)
//             }
//         } else {
//             document.querySelector('.text-notification').textContent =
//                 'Voucher discount not found!'
//             $('#alert').addClass('alert-danger')
//             $('#alert').removeClass('d-none')
//             setTimeout(function () {
//                 $('#alert').addClass('d-none')
//             }, 2000)
//         }
//     }
//     xhr.send(`action=getDiscount&voucher=${value}`)
// }

function removeCart(id) {
    console.log(id)
    const xhr = new XMLHttpRequest()
    xhr.open('POST', './api/cart.php', true)
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

// document
//     .getElementById('addDiscount')
    // .addEventListener('keydown', function (event) {
    //     if (event.key === 'Enter') {
    //         event.preventDefault()
    //         applyDiscount(this.value)
    //     }
    // })
