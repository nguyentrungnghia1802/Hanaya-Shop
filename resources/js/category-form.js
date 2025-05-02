document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("categoryForm");

    if (form) {
        form.addEventListener("submit", async function (e) {
            e.preventDefault();
            document.getElementById("errorMsg").classList.add("hidden");

            const formData = new FormData(this);
            const url = this.getAttribute("data-url");

            try {
                const response = await fetch(url, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector(
                            'input[name="_token"]'
                        ).value,
                        Accept: "application/json",
                    },
                    body: formData,
                });

                if (response.ok) {
                    const data = await response.json(); // trả về object category mới tạo

                    // 1. Tạo dòng HTML mới
                    const newRow = document.createElement("tr");
                    newRow.innerHTML = `
                                    <td class="px-4 py-2 border-b">${
                                        data.id
                                    }</td>
                                    <td class="px-4 py-2 border-b">${
                                        data.name
                                    }</td>
                                    <td class="px-4 py-2 border-b">${
                                        data.description || ""
                                    }</td>
                                    <td class="px-4 py-2 border-b space-x-2">
                                        <a href="#"
                                            class="inline-block px-3 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 transition btn-edit"
                                            data-id="${data.id}"
                                            data-name="${data.name}"
                                            data-description="${
                                                data.description || ""
                                            }">
                                            Edit
                                        </a>
                                        <button
                                            class="inline-block px-3 py-1 bg-red-500 text-white text-xs font-medium rounded hover:bg-red-600 transition btn-delete"
                                            data-id="${data.id}"
                                            data-url="/admin/category/${
                                                data.id
                                            }">
                                            Delete
                                        </button>
                                    </td>`;

                    // 2. Thêm vào tbody
                    document.querySelector("table tbody").appendChild(newRow);

                    const successMsg = document.getElementById("successMsg");
                    successMsg.classList.remove("hidden");
                    setTimeout(() => {
                        successMsg.classList.add("hidden");
                    }, 3000);
                } else if (response.status === 422) {
                    const errorMsg = document.getElementById("errorMsg");
                    errorMsg.classList.remove("hidden");
                }
            } catch (err) {
                console.error("Lỗi gửi form:", err);
            }
        });
    }
});
