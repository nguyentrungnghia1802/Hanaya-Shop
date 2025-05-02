document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("searchInput");

    searchInput.addEventListener("input", async function () {
        const keyword = this.value;

        try {
            const response = await fetch(
                `/admin/category/search?query=${encodeURIComponent(keyword)}`,
                {
                    headers: { Accept: "application/json" },
                }
            );

            if (response.ok) {
                const categories = await response.json();
                const tbody = document.querySelector("table tbody");
                tbody.innerHTML = "";

                categories.forEach((cat) => {
                    const row = document.createElement("tr");
                    row.classList.add("hover:bg-gray-50", "transition");
                    row.innerHTML = `
                        <td class="px-4 py-2 border-b">${cat.id}</td>
                        <td class="px-4 py-2 border-b">${cat.name}</td>
                        <td class="px-4 py-2 border-b">${
                            cat.description ?? ""
                        }</td>
                        <td class="px-4 py-2 border-b space-x-2">
                            <button class="inline-block px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 transition btn-edit"
                                data-id="${cat.id}" data-name="${
                        cat.name
                    }" data-description="${cat.description ?? ""}">
                                Edit
                            </button>
                            <button class="inline-block px-3 py-1 bg-red-500 text-white text-xs font-medium rounded hover:bg-red-600 transition btn-delete"
                                data-id="${cat.id}" data-url="/admin/category/${
                        cat.id
                    }">
                                Delete
                            </button>
                        </td>`;
                    tbody.appendChild(row);
                });

                // Rebind lại sự kiện edit nếu cần
                bindEditButtons(); // bạn cần định nghĩa lại bindEditButtons()
            }
        } catch (err) {
            console.error("Lỗi tìm kiếm:", err);
        }
    });
});
