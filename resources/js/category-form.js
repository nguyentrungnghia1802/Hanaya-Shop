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
                    newRow.innerHTML = `<td>${data.id}</td>
                        <td>${data.name}</td>
                        <td>${data.description || ""}</td>
                        <td>
                            <a href="#" class="btn">Edit</a>
                            <button class="btn btn-danger btn-delete">Delete</button>
                            <a href="#" class="btn-view btn">view</a>
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
