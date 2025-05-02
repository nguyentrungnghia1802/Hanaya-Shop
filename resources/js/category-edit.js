document.addEventListener("DOMContentLoaded", () => {
    const editButtons = document.querySelectorAll(".btn-edit");
    const form = document.getElementById("categoryForm-edit");
    const overlay = document.getElementById("overlay");

    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("btn-edit")) {
            e.preventDefault();

            const row = e.target.closest("tr");
            const id = row.querySelector("td:nth-child(1)").textContent.trim();
            const name = row
                .querySelector("td:nth-child(2)")
                .textContent.trim();
            const description = row
                .querySelector("td:nth-child(3)")
                .textContent.trim();

            document.getElementById("category_id").value = id;
            document.getElementById("name-edit").value = name;
            document.getElementById("description-edit").value = description;

            form.classList.remove("hidden");
            overlay.classList.remove("hidden");
        }
    });

    document.getElementById("cancelBtn").addEventListener("click", () => {
        form.classList.add("hidden");
        overlay.classList.add("hidden");
    });

    overlay.addEventListener("click", () => {
        form.classList.add("hidden");
        overlay.classList.add("hidden");
    });

    form.addEventListener("submit", async function (e) {
        e.preventDefault();

        const id = document.getElementById("category_id").value;
        const name = document.getElementById("name-edit").value;
        const description = document.getElementById("description-edit").value;

        const urlTemplate = form.getAttribute("data-url");
        const url = urlTemplate.replace("__ID__", id);

        try {
            const response = await fetch(url, {
                method: "PUT",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    "X-HTTP-Method-Override": "PUT",
                },
                body: JSON.stringify({ name, description }),
            });

            if (response.ok) {
                const data = await response.json();

                // Cập nhật dòng trong bảng
                const row = [
                    ...document.querySelectorAll("table tbody tr"),
                ].find((tr) => {
                    return tr.querySelector("td").textContent.trim() === id;
                });

                if (row) {
                    row.querySelector("td:nth-child(2)").textContent =
                        data.name;
                    row.querySelector("td:nth-child(3)").textContent =
                        data.description ?? "";
                }

                // Ẩn form và overlay
                form.classList.add("hidden");
                overlay.classList.add("hidden");

                const successMsg = document.getElementById("successMsg-edit");
                successMsg.classList.remove("hidden");
                setTimeout(() => {
                    successMsg.classList.add("hidden");
                }, 3000);
            } else if (response.status === 422) {
                document
                    .getElementById("errorMsg-edit")
                    .classList.remove("hidden");
            } else {
                console.error("Cập nhật thất bại");
            }
        } catch (err) {
            console.error("Lỗi cập nhật danh mục:", err);
        }
    });
});
