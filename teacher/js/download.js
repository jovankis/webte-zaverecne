$(document).ready(function () {

    $('#download-csv-button').click(function () {
        const table = document.querySelector("table");
        let csvContent = "\uFEFF";

        const rows = table.querySelectorAll("tr");
        rows.forEach((row) => {
            const cells = row.querySelectorAll("th, td");
            const rowData = Array.from(cells)
                .map((cell) => cell.innerHTML)
                .join(",");
            csvContent += rowData + "\r\n";
        });

        const link = document.createElement("a");
        // link.setAttribute("href", encodeURI(csvContent));
        link.setAttribute("href", "data:text/csv;charset=utf-8," + encodeURIComponent(csvContent));
        link.setAttribute("download", "table.csv");

        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
});
