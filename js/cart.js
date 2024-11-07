function currencyFormat(number, currency = 'IDR', locale = 'id-ID', decimals = 0) {
    return new Intl.NumberFormat(locale, {
        style: 'currency',
        currency: currency,
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals
    }).format(number);
}
function updateTotal() {
    const totalPrice = document.querySelectorAll('.total');
    let subtotal = 0    
    totalPrice.forEach(element => {
            subtotal = subtotal + parseInt(element.textContent.replace(/[^0-9]/g, ''))        
    });
    
    document.getElementById("subtotal").textContent = currencyFormat(subtotal)
    let discount = parseInt(document.querySelector('.discount').textContent.replace('%', ''))
    let total =  discount != 0 ? subtotal * (discount/100) : subtotal 
    document.getElementById("total").textContent = currencyFormat(total)
    
}
updateTotal();

document.querySelectorAll('.add, .minus').forEach(button => {
    button.addEventListener('click', function() {
        const cartItem = this.closest('.cart-item');
        const itemId = this.getAttribute('data-id');
        let quantity = parseInt(cartItem.getAttribute('data-qty'));
        // let total = parseInt(cartItem.querySelector('.total').textContent.replace(/[^0-9]/g, ''));
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
                updateTotal();


            }
        };

        xhr.send(`action=updateCart&id=${itemId}&qty=${quantity}&price=${price}`);

        
        
    });
});
let isDiscount = false;

function applyDiscount(){
    if (isDiscount) return; 
    isDiscount = true;

    const cartDiscount = document.querySelector('.cart-discount');
    const setDiscount = document.querySelector('.setDiscount');
    let inputValue = cartDiscount.querySelector('.inputVoucher').value;

    
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_cart.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            let data = JSON.parse(xhr.responseText)['data'];
            setDiscount.querySelector('.discount').textContent = `${data['discount']}%`;
            updateTotal();
            $('#msg').removeClass('d-none');
            setTimeout(function () {
                $('#msg').addClass('d-none');
            }, 2000);


        }else{
            setDiscount.querySelector('.discount').textContent = 0;
        }
    };

    xhr.send(`action=getDiscount&voucher=${inputValue}`);
}


document.querySelector('.addDiscount').addEventListener('click', applyDiscount);

// Event listener untuk "Enter" pada input voucher
document.querySelector('.inputVoucher').addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault(); // Mencegah submit form jika ada
        applyDiscount();
    }
});
