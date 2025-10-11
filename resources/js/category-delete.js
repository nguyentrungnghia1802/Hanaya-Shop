// document.addEventListener("DOMContentLoaded", () => {
//     const tableBody = document.querySelector("table tbody");

//     tableBody.addEventListener("click", async function (e) {
//         const deleteBtn = e.target.closest(".btn-delete");

//         if (deleteBtn) {
//             e.preventDefault();

//             const id = deleteBtn.dataset.id;
//             const url = deleteBtn.dataset.url;

//             if (confirm("Bạn có chắc chắn muốn xóa?")) {
//                 try {
//                     const response = await fetch(url, {
//                         method: "DELETE",
//                         headers: {
//                             "X-CSRF-TOKEN": document.querySelector(
//                                 'meta[name="csrf-token"]'
//                             ).content,
//                             Accept: "application/json",
//                         },
//                     });

//                     if (response.ok) {
//                         const row = deleteBtn.closest("tr");
//                         row.remove();

//                         const successMsg =
//                             document.getElementById("successMsg-delete");
//                         successMsg.classList.remove("hidden");
//                         setTimeout(() => {
//                             successMsg.classList.add("hidden");
//                         }, 3000);
//                     } else {
//                         console.error("Xóa thất bại");
//                     }
//                 } catch (err) {
//                     console.error("Lỗi khi xóa danh mục:", err);
//                 }
//             }
//         }
//     });
// });
