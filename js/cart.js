function currencyFormat(number, currency = 'IDR', locale = 'id-ID', decimals = 0) {
    return new Intl.NumberFormat(locale, {
        style: 'currency',
        currency: currency,
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals
    }).format(number);
}
document.querySelectorAll('.add, .minus').forEach(button => {
    button.addEventListener('click', function() {
        const cartItem = this.closest('.cart-item');
        const itemId = this.getAttribute('data-id');
        let quantity = parseInt(cartItem.getAttribute('data-qty'));
        let total = parseInt(cartItem.querySelector('.total').textContent.replace(/[^0-9]/g, ''));
        let price = parseInt(cartItem.querySelector('.price').textContent.replace(/[^0-9]/g, ''));
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_cart.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                console.log(xhr.responseText); 
            }
        };

        if (this.classList.contains('add')) {
            quantity++;
            xhr.send(`action=add&id=${itemId}&qty=${quantity}`);
            cartItem.querySelector('.total').textContent = currencyFormat(total + price);
        } else if (this.classList.contains('minus') && quantity > 1) {
            quantity--;
            xhr.send(`action=minus&id=${itemId}&qty=${quantity}`);
            cartItem.querySelector('.total').textContent = currencyFormat(total - price);
        }
        cartItem.setAttribute('data-qty', quantity);
        cartItem.querySelector('.quantity').textContent = quantity;
        // console.log(`Item ID: ${itemId}, Quantity: ${quantity}`)
        
    });
});