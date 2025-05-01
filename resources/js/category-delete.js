document.addEventListener("DOMContentLoaded", () => {
    const deleteButtons = document.querySelectorAll(".btn-delete");
    deleteButtons.forEach((button) => {
        button.addEventListener("click", async function (e) {
            e.preventDefault();

            const id = this.dataset.id; // Lấy id từ data-id
            const url = this.dataset.url; // Lấy URL xóa từ data-url

            if (confirm("Bạn có chắc chắn muốn xóa?")) {
                try {
                    const response = await fetch(url, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector(
                                'meta[name="csrf-token"]'
                            ).content,
                            Accept: "application/json",
                        },
                    });

                    if (response.ok) {
                        // Xóa dòng khỏi bảng
                        const row = this.closest("tr");
                        row.remove();

                        const successMsg =
                            document.getElementById("successMsg-delete");
                        successMsg.classList.remove("hidden");
                        setTimeout(() => {
                            successMsg.classList.add("hidden");
                        }, 3000);
                    } else {
                        console.error("Xóa thất bại");
                    }
                } catch (err) {
                    console.error("Lỗi khi xóa danh mục:", err);
                }
            }
        });
    });
});
