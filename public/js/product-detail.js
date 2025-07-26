// Product Detail Page JS
document.addEventListener("DOMContentLoaded", function () {
    // Elements
    const decreaseBtn = document.getElementById("decrease");
    const increaseBtn = document.getElementById("increase");
    const quantityInput = document.getElementById("quantity");
    const maxStock = quantityInput
        ? parseInt(quantityInput.getAttribute("max"))
        : 1;
    const addToCartForm = document.getElementById("add-to-cart-form");
    const formQuantityInput = document.getElementById("form-quantity");
    const buyNowForm = document.querySelector('form[action*="cart.buyNow"]');
    const buyNowQuantityInput = document.getElementById("buy-now-quantity");

    // Helper: update all quantity inputs
    function syncQuantityInputs(val) {
        if (quantityInput) quantityInput.value = val;
        if (formQuantityInput) formQuantityInput.value = val;
        if (buyNowQuantityInput) buyNowQuantityInput.value = val;
    }

    // Decrease quantity
    if (decreaseBtn && quantityInput) {
        decreaseBtn.addEventListener("click", function (e) {
            e.preventDefault();
            let val = parseInt(quantityInput.value) || 1;
            if (val > 1) {
                val--;
                syncQuantityInputs(val);
            }
        });
    }

    // Increase quantity
    if (increaseBtn && quantityInput) {
        increaseBtn.addEventListener("click", function (e) {
            e.preventDefault();
            let val = parseInt(quantityInput.value) || 1;
            if (val < maxStock) {
                val++;
                syncQuantityInputs(val);
            }
        });
    }

    // Manual input: validate and sync
    if (quantityInput) {
        quantityInput.addEventListener("input", function () {
            let val = parseInt(this.value) || 1;
            if (val < 1) val = 1;
            if (val > maxStock) val = maxStock;
            syncQuantityInputs(val);
        });
    }

    // Ensure hidden input in form is always correct before submit
    if (addToCartForm && formQuantityInput && quantityInput) {
        addToCartForm.addEventListener("submit", function () {
            let val = parseInt(quantityInput.value) || 1;
            if (val < 1) val = 1;
            if (val > maxStock) val = maxStock;
            formQuantityInput.value = val;
        });
    }

    // Ensure buy now form quantity is correct
    if (buyNowForm && buyNowQuantityInput && quantityInput) {
        buyNowForm.addEventListener("submit", function () {
            let val = parseInt(quantityInput.value) || 1;
            if (val < 1) val = 1;
            if (val > maxStock) val = maxStock;
            buyNowQuantityInput.value = val;
        });
    }
});
