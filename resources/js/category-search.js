document.addEventListener("DOMContentLoaded", () => {
    const tableBody = document.querySelector("table tbody");
    const searchInput = document.getElementById("searchInput");
    const rows = tableBody.querySelectorAll("tr");

    // Sự kiện tìm kiếm
    searchInput.addEventListener("input", function () {
        const keyword = this.value.toLowerCase();
        rows.forEach((row) => {
            const id = row.cells[0].textContent.toLowerCase();
            const name = row.cells[1].textContent.toLowerCase();

            if (id.includes(keyword) || name.includes(keyword)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });

    // Sự kiện xóa bằng event delegation
    tableBody.addEventListener("click", async function (e) {
        const deleteBtn = e.target.closest(".btn-delete");

        if (deleteBtn) {
            e.preventDefault();
            const id = deleteBtn.dataset.id;
            const url = deleteBtn.dataset.url;

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
                        const row = deleteBtn.closest("tr");
                        row.remove(); // Xóa hàng trong bảng

                        const successMsg =
                            document.getElementById("successMsg-delete");
                        successMsg.classList.remove("hidden");
                        setTimeout(() => {
                            successMsg.classList.add("hidden");
                        }, 3000);
                    } else {
                        alert("Xóa thất bại. Vui lòng thử lại.");
                    }
                } catch (err) {
                    console.error("Lỗi khi xóa:", err);
                }
            }
        }
    });
});
