let dataPrice = 0
function updateSelectedAddress(isDefault, name, phone_number, address) {
    $('#address-title').text(
        `${String(name).trim()} | 0${String(phone_number).trim()}`
    )
    $('#full-address').text(`${String(address).trim()}`)
    $('#is-default').text(parseInt(isDefault) === 1 ? 'Utama' : '')
}

document.addEventListener('DOMContentLoaded', function () {
    const courierSelected = document.getElementById('shippingMethod')
    const totalPriceElement = document.getElementById('totalPrice')
    const totalPaymentElement = document.getElementById('totalPayment')
    const shippingCostElement = document.getElementById('shippingCost')

    // Inisialisasi biaya pengiriman
    const initialShippingPrice = parseFloat(
        courierSelected.options[courierSelected.selectedIndex].dataset.price
    )
    shippingCostElement.textContent = initialShippingPrice.toLocaleString(
        'id-ID',
        {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0,
            minimumFractionDigits: 0,
        }
    )
    const allDiscountElements = document.querySelectorAll('[id^="discount-"]')
    allDiscountElements.forEach((element) => {
        const initialDiscount = element.classList
        if (initialDiscount.contains('disabled') === true) {
            updateTotalPayment({
                shippingCostElement: shippingCostElement,
                discounts: parseInt(
                    element.getAttribute('id').replace('discount-', '')
                ),
            })
        }
    })

    courierSelected.addEventListener('change', function () {
        const shippingPrice = parseFloat(
            this.options[this.selectedIndex].dataset.price
        )

        shippingCostElement.textContent = shippingPrice.toLocaleString(
            'id-ID',
            {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0,
                minimumFractionDigits: 0,
            }
        )

        // Update total pembayaran setiap kali kurir berubah
        updateTotalPayment({
            shippingCostElement: shippingCostElement,
            totalPaymentElement: totalPaymentElement,
            totalPriceElement: totalPriceElement,
        })
    })
})
function applyVoucher(valueDiscount, id) {
    const discountPersentage = parseInt(valueDiscount)
    const allDiscountElements = document.querySelectorAll('[id^="discount-"]')
    allDiscountElements.forEach((element) => {
        if (element.id === `discount-${valueDiscount}`) {
            element.classList.add('disabled')
        } else {
            element.classList.remove('disabled')
        }
    })

    fetch('./api/checkout.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=updateVoucher&id=${id}`,
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.status === 'success') {
                updateTotalPayment({
                    discounts: discountPersentage,
                })
            } else {
                console.error('Error applying voucher:', data.message)
            }
        })
        .catch((error) => {
            console.error('Error:', error)
        })
}

/**
 * Updates the total payment amount based on the total price, shipping cost, and applied discounts.
 *
 * @param {Object} params - The parameters object.
 * @param {HTMLElement} params.shippingCostElement - The element displaying the shipping cost.
 * @param {HTMLElement} params.totalPriceElement - The element displaying the total price.
 * @param {HTMLElement} params.totalPaymentElement - The element where the updated total payment will be displayed.
 * @param {number} params.discounts - The element where the updated total payment will be displayed.
 * @returns {void} This function does not return a value, it updates the DOM directly.
 */
function updateTotalPayment({
    shippingCostElement = document.getElementById('shippingCost'),
    totalPriceElement = document.getElementById('totalPrice'),
    totalPaymentElement = document.getElementById('totalPayment'),
    discounts,
}) {
    // Ambil total harga barang
    const totalPrice = parseFloat(
        totalPriceElement.textContent.replace(/[^\d,-]/g, '').replace(',', '.')
    )

    // Ambil biaya pengiriman
    const shippingCost = parseFloat(
        shippingCostElement.textContent
            .replace(/[^\d,-]/g, '')
            .replace(',', '.')
    )

    let totalPayment = totalPrice + shippingCost

    if (discounts !== null && discounts !== undefined) {
        const setDiscount = document.getElementById('discount')
        const discountLabel = document.getElementById('discount-label')
        discountLabel.innerHTML = `Diskon ${discounts}%`
        setDiscount.innerHTML = `<del>${totalPayment.toLocaleString('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0,
            minimumFractionDigits: 0,
        })}</del>`

        totalPayment = totalPayment * (1 - discounts / 100)
    }

    // const totalPayment = totalPrice + shippingCost - totalDiscount

    // Update total pembayaran di HTML

    dataPrice = totalPayment

    totalPaymentElement.textContent = totalPayment.toLocaleString('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
        minimumFractionDigits: 0,
    })
}

function processCheckout() {
    //  all data
    const address = document.getElementById('address-title').dataset.id
    const courier = document.getElementById('shippingMethod').value
    const totalPayment = document
        .getElementById('totalPayment')
        .textContent.trim()
        .replace('Rp', '')
        .replace('.', '')
        .replace(' ')
    let discountSelect
    const allDiscountElements = document.querySelectorAll('[id^="discount-"]')
    allDiscountElements.forEach((element) => {
        const initialDiscount = element.classList
        if (initialDiscount.contains('disabled') === true) {
            discountSelect = element.dataset.id
        }
    })

    fetch('./api/checkout.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=checkout&address=${address}&courier=${courier}&total_payment=${dataPrice}&voucher=${
            discountSelect || ''
        }`,
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.status === 'success') {
                // Handle successful checkout
                window.location('./index.php')
            } else {
                // Handle checkout error
                console.error('Checkout failed:', data.message)
                // You might want to show an error message to the user
            }
        })
        .catch((error) => {
            console.error('Error during checkout:', error)
            // Handle any network errors or exceptions
        })
}
