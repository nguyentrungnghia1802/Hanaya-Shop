document.addEventListener("DOMContentLoaded", () => {
    const tableBody = document.querySelector("table tbody");
    const searchInput = document.getElementById("searchInput");
    
    // Only run if both elements exist
    if (!tableBody || !searchInput) {
        return;
    }
    
    const rows = tableBody.querySelectorAll("tr");

    // Search event listener
    searchInput.addEventListener("input", function () {
        const keyword = this.value.toLowerCase();
        rows.forEach((row) => {
            const id = row.cells[0].textContent.toLowerCase();
            const name = row.cells[1].textContent.toLowerCase();

            row.style.display =
                id.includes(keyword) || name.includes(keyword) ? "" : "none";
        });
    });
});
