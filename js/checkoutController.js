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
    const shippingCostElement = document.getElementById('shippingCost')
    const totalPaymentElement = document.getElementById('totalPayment')

    function updateTotalPayment() {
        // Ambil total harga barang
        const totalPrice = parseFloat(
            totalPriceElement.textContent
                .replace(/[^\d,-]/g, '')
                .replace(',', '.')
        )

        // Ambil biaya pengiriman
        const shippingCost = parseFloat(
            shippingCostElement.textContent
                .replace(/[^\d,-]/g, '')
                .replace(',', '.')
        )

        // Hitung total diskon
        let totalDiscount = 0
        const discountElements = document.querySelectorAll('[id^="discount"]')
        discountElements.forEach((element) => {
            const discountPercentage = element.dataset.discount

            totalDiscount += (totalPrice * discountPercentage) / 100
        })

        // Hitung total pembayaran
        const totalPayment = totalPrice + shippingCost - totalDiscount

        // Update total pembayaran di HTML
        totalPaymentElement.textContent = totalPayment.toLocaleString('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0,
            minimumFractionDigits: 0,
        })
    }

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

    // Update total pembayaran saat halaman dimuat
    updateTotalPayment()

    courierSelected.addEventListener('change', function () {
        const selectedCourier = this.value

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
        updateTotalPayment()
    })
})
