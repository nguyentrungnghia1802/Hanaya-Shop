document.addEventListener("DOMContentLoaded", () => {
    const editButtons = document.querySelectorAll(".btn"); // hoặc .btn-edit nếu bạn phân loại rõ
    const form = document.getElementById("categoryForm-edit");
    const overlay = document.getElementById("overlay");

    editButtons.forEach((button) => {
        button.addEventListener("click", function (e) {
            e.preventDefault();

            // Lấy dòng (tr) chứa thông tin
            const row = this.closest("tr");
            const id = row.querySelector("td:nth-child(1)").textContent.trim();
            const name = row
                .querySelector("td:nth-child(2)")
                .textContent.trim();
            const description = row
                .querySelector("td:nth-child(3)")
                .textContent.trim();

            // Gán vào form
            document.getElementById("category_id").value = id;
            document.getElementById("name").value = name;
            document.getElementById("description").value = description;

            // Hiện form và overlay
            form.classList.remove("hidden");
            overlay.classList.remove("hidden");
        });
    });

    // Nút Hủy hoặc overlay click để ẩn form
    document.getElementById("cancelBtn").addEventListener("click", () => {
        form.classList.add("hidden");
        overlay.classList.add("hidden");
    });

    overlay.addEventListener("click", () => {
        form.classList.add("hidden");
        overlay.classList.add("hidden");
    });
});
