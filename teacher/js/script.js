$(document).ready(function() {
    const refreshFilesButton = document.getElementById("refresh-button");

    refreshFilesButton.addEventListener("click", (event) => {
        // Send a GET request to refresh_files.php
        fetch("actions/refresh_files.php", {
            method: "GET"
        })
            .then(response => {
                if (response.ok) {
                    alert("Súbory boli úspešne obnovené.");
                    // Perform any additional actions after refreshing files
                } else {
                    console.error("Obnovenie súborov zlyhalo.");
                }
            })
            .catch(error => {
                console.error("Pri obnovovaní súborov sa vyskytla chyba:", error);
            });
    });

    // Get the visits table element
    const filesTable = document.querySelector('#files-table');
    filesTable.addEventListener('click', async (event) => {
        // Check if the clicked element is a table row
        if (event.target.tagName === 'TD') {
            const filename = event.target.parentNode.dataset.filename;
            const dialog = document.createElement('dialog');
            dialog.setAttribute('id', 'math-dialog');

            if(!filename){
                dialog.innerHTML = `<h2>Cannot parse file</h2>`;
            } else {
                // Fetch the country data
                const response = await fetch("actions/get_tasks.php?filename=" + filename);
                const data = await response.json();

                const table = document.createElement('table');
                table.setAttribute("class", "table");
                const headerRow = document.createElement('tr');
                const headerSection = document.createElement('th');
                headerSection.textContent = 'Section';
                const headerTaskDescription = document.createElement('th');
                headerTaskDescription.textContent = 'Task description';
                const headerSolution = document.createElement('th');
                headerSolution.textContent = 'Solution';
                const headerImagePath = document.createElement('th');
                headerImagePath.textContent = 'Image path';
                headerRow.appendChild(headerSection);
                headerRow.appendChild(headerTaskDescription);
                headerRow.appendChild(headerSolution);
                headerRow.appendChild(headerImagePath)
                table.appendChild(headerRow);

                for (const {section, taskDescription, solution, imagePath} of data) {
                    const row = document.createElement('tr');
                    const cellSection = document.createElement('td');
                    cellSection.textContent = section;
                    const cellTaskDescription = document.createElement('td');
                    cellTaskDescription.textContent = taskDescription.replace(/\$/g, function() {
                        return "$$";
                    });
                    var str = taskDescription.replace(/\$/g, function() {
                        return "$$";
                    });
                    const cellSolution = document.createElement('td');
                    cellSolution.textContent = solution.replace(/\$/g, function() {
                        return "$$";
                    });
                    const cellImagePath = document.createElement('td');
                    cellImagePath.textContent = imagePath;
                    row.appendChild(cellSection);
                    row.appendChild(cellTaskDescription);
                    row.appendChild(cellSolution);
                    row.appendChild(cellImagePath);
                    table.appendChild(row);
                }

                // Create the dialog element
                dialog.innerHTML = `
                <h2>${filename}</h2>
            `;
                dialog.appendChild(table);
            }

            const button = document.createElement('button');
            button.innerText = "Zavrieť";
            dialog.appendChild(button);

            dialog.querySelector('button').addEventListener('click', () => {
                dialog.close();
            });

            document.body.appendChild(dialog);
            // Open the dialog
            dialog.showModal();
            MathJax.typesetPromise().then(() => {
                // MathJax has finished typesetting
            });
        }
        else if (event.target.tagName == 'BUTTON' || event.target.tagName === 'I') {
            const generatingEnabled = event.target.closest('tr').dataset.generatingEnabled == 0 ? false : true;

            $('#edit-modal-id').val(event.target.closest('tr').dataset.id);
            $('#edit-modal-generating-enabled').prop('checked', generatingEnabled);
            $('#edit-modal-start-date').val(event.target.closest('tr').dataset.startDate);
            $('#edit-modal-end-date').val(event.target.closest('tr').dataset.endDate);
            $('#edit-modal-points').val(event.target.closest('tr').dataset.points);

            // Open the editing dialog
            $('#edit-modal').modal('show');
        }
    });

    $('#edit-modal-save').click(function() {
        // Get the updated data from the editing dialog input fields
        const id = $('#edit-modal-id').attr('value');
        const generatingEnabled = $('#edit-modal-generating-enabled').prop('checked');
        const startDate = $('#edit-modal-start-date').val();
        const endDate = $('#edit-modal-end-date').val();
        const points = $('#edit-modal-points').val();

        if (points <= 0) {
            alert('Pocet bodov musi byt vacsi ako 0.');
            return;
        }

        // Send the updated data to the server
        $.ajax({
            url: 'actions/update_file.php',
            method: 'POST',
            data: {
                id: id,
                generatingEnabled: generatingEnabled ? 1 : 0,
                startDate: startDate ? startDate : null,
                endDate: endDate ? endDate : null,
                points: points
            },
            success: function(response) {
                // Close the editing dialog
                $('#edit-modal').modal('hide');
                alert("Úprava bola úspešne vykonaná. Obnovte stránku.")
            },
            error: function(xhr, status, error) {
                // Handle the AJAX error here
                alert("Vyskytla sa chyba. Prosím skúste znova.")
            }
        });
    });
});
