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
        const isAddAction = this.classList.contains('add');
        
        quantity = isAddAction ? quantity + 1 : quantity > 1 ? quantity - 1 : null;
        
        
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_cart.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                let data = JSON.parse(xhr.responseText)['data'];
                cartItem.setAttribute('data-qty', data['quantity']);
                cartItem.querySelector('.quantity').textContent = data['quantity'];
                cartItem.querySelector('.total').textContent = currencyFormat(data['total']);
            }
        };

        xhr.send(`id=${itemId}&qty=${quantity}&price=${price}`);


        
    });
});