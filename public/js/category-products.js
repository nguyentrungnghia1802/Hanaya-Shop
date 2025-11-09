// Category Products Page JS
document.addEventListener("DOMContentLoaded", function () {
    // Xử lý nút Add to Cart bằng AJAX
    document
        .querySelectorAll('form[action*="cart.add"]')
        .forEach(function (form) {
            form.addEventListener("submit", function (e) {
                e.preventDefault();
                const action = form.getAttribute("action");
                const quantityInput = form.querySelector(
                    'input[name="quantity"]'
                );
                const quantity = quantityInput
                    ? parseInt(quantityInput.value) || 1
                    : 1;
                const csrf =
                    form.querySelector('input[name="_token"]')?.value ||
                    document.querySelector('meta[name="csrf-token"]')?.content;

                fetch(action, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrf,
                        Accept: "application/json",
                    },
                    body: JSON.stringify({ quantity }),
                })
                    .then(async (res) => {
                        let data;
                        try {
                            data = await res.json();
                        } catch {
                            data = {};
                        }
                        if (res.ok || data.success) {
                            // Cập nhật cart count nếu có
                            if (data.cart_count) {
                                const cartCount =
                                    document.querySelector(".cart-count");
                                if (cartCount)
                                    cartCount.textContent = data.cart_count;
                            }
                        } else {
                            // Hiển thị lỗi
                            const msg =
                                data.message ||
                                "Có lỗi xảy ra khi thêm vào giỏ hàng";
                            if (typeof Swal !== "undefined") {
                                Swal.fire({
                                    icon: "error",
                                    title: "Lỗi!",
                                    text: msg,
                                });
                            } else {
                                alert(msg);
                            }
                        }
                    })
                    .catch((error) => {
                        if (typeof Swal !== "undefined") {
                            Swal.fire({
                                icon: "error",
                                title: "Lỗi!",
                                text: "Có lỗi xảy ra khi thêm vào giỏ hàng",
                            });
                        } else {
                            alert("Có lỗi xảy ra khi thêm vào giỏ hàng");
                        }
                    });
            });
        });
});
