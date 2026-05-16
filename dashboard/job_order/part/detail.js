let selectedRowId = null;

function openModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

function handleRowClick(row) {
    const id = row.getAttribute('data-id');

    if (selectedRowId === id) {
        row.classList.remove('selected');
        selectedRowId = null;

        document.getElementById('editButton').disabled = true;
        document.getElementById('deleteButton').disabled = true;

        return;
    }

    selectedRowId = id;
    
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(r => r.classList.remove('selected'));
    row.classList.add('selected');

    document.getElementById('editButton').disabled = false;
    document.getElementById('deleteButton').disabled = false;
}

document.addEventListener('click', function(event) {
    const table = document.querySelector('table');
    const clickedInsideTable = table.contains(event.target);

    if (!clickedInsideTable) {
        const selectedRow = document.querySelector(`tr[data-id='${selectedRowId}']`);
        if (selectedRow) {
            selectedRow.classList.remove('selected'); // Unhighlight the selected row
        }
        selectedRowId = null; // Reset the selectedRowId

        // Disable Edit and Delete buttons
        document.getElementById('editButton').disabled = true;
        document.getElementById('deleteButton').disabled = true;
    }
});

function editCMTPart() {
    if (!selectedRowId) {
        alert("Please select a part to edit.");
        return;
    }

    const selectedRow = document.querySelector(`tr[data-id='${selectedRowId}']`);
    if (!selectedRow) {
        alert("Selected row not found.");
        return;
    }

    const part = selectedRow.cells[0].textContent;
    const price = selectedRow.cells[1].textContent;
    const partId = selectedRow.getAttribute('data-id');

    document.getElementById('editPart').value = part;
    document.getElementById('editPrice').value = price;
    document.getElementById('Id').value = partId;

    // Fill bundle values in the edit modal based on bundle columns
    const bundleInputs = selectedRow.querySelectorAll('td');
    for (let i = 2; i < bundleInputs.length; i++) {
        const bundleValue = bundleInputs[i].textContent || '0';
        const bundleInputId = `editBundle${i - 1}`; // Map table column to bundle field
        document.getElementById(bundleInputId).value = bundleValue;
    }

    openModal('editPartModal');
}

function deleteCMTPart() {
    if (!selectedRowId) {
        alert("Please select a part to delete.");
        return;
    }

    const selectedRow = document.querySelector(`tr[data-id='${selectedRowId}']`);
    if (!selectedRow) {
        alert("Selected row not found.");
        return;
    }

    const part = selectedRow.cells[0].textContent;
    if (confirm(`Are you sure you want to delete Part Name: ${part}?`)) {
        const cmtId = document.querySelector('input[name="CMT_id"]').value;

        // Use GET request to send data to delete_part.php
        const url = `part/delete_part.php?CMT_id=${encodeURIComponent(cmtId)}&Id=${encodeURIComponent(selectedRowId)}`;
        console.log("CMT ID: ", cmtId);
        console.log("Part ID: ", selectedRowId);

        // Perform the GET request
        fetch(url)
            .then(response => response.text())
            .then(data => {
                if (data.includes("successfully")) {
                    location.reload();  // Reload the page to update the table
                } else {
                    alert('Error deleting part: ' + data);
                }
            })
            .catch(error => {
                alert('Error: ' + error);
            });
    }
}


/*let selectedRowId = null;

function openModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    document.body.style.overflow = '';
}


function handleRowClick(row) {
    const id = row.getAttribute('data-id');

    if (selectedRowId === id) {
        row.classList.remove('selected');
        selectedRowId = null;

        document.getElementById('editButton').disabled = true;
        document.getElementById('deleteButton').disabled = true;

        return;
    }

    selectedRowId = id;
    
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(r => r.classList.remove('selected'));
    row.classList.add('selected');

    document.getElementById('editButton').disabled = false;
    document.getElementById('deleteButton').disabled = false;
}

document.addEventListener('click', function(event) {
    const table = document.querySelector('table');
    const clickedInsideTable = table.contains(event.target);

    if (!clickedInsideTable) {
        const selectedRow = document.querySelector(`tr[data-id='${selectedRowId}']`);
        if (selectedRow) {
            selectedRow.classList.remove('selected'); // Unhighlight the selected row
        }
        selectedRowId = null; // Reset the selectedRowId

        // Disable Edit and Delete buttons
        document.getElementById('editButton').disabled = true;
        document.getElementById('deleteButton').disabled = true;
    }
});

function editCMTPart() {
    if (!selectedRowId) {
        alert("Please select a part to edit.");
        return;
    }

    if (!selectedRow) {
        alert("Selected row not found.");
        return;
    }
    
    const selectedRow = document.querySelector(`tr[data-id='${selectedRowId}']`);
    const part = selectedRow.cells[0].textContent;
    const price = selectedRow.cells[1].textContent;

    document.getElementById('editPart').value = part;
    document.getElementById('editPrice').value = price;
    document.getElementById('editId').value = selectedRowId;

    // Fill bundle values in the edit modal based on bundle columns
    const bundleInputs = selectedRow.querySelectorAll('td');
    for (let i = 2; i < bundleInputs.length; i++) {
        const bundleValue = bundleInputs[i].textContent || '0';
        const bundleInputId = `editBundle${i - 1}`; // Map table column to bundle field
        document.getElementById(bundleInputId).value = bundleValue;
    }

    openModal('editPartModal');
}

function deleteCMTPart() {
    if (!selectedRowId) {
        alert("Please select a part to delete.");
        return;
    }

    const selectedRow = document.querySelector(`tr[data-id='${selectedRowId}']`);
    if (!selectedRow) {
        alert("Selected row not found.");
        return;
    }

    const part = selectedRow.cells[0].textContent;
    if (confirm(`Are you sure you want to delete Part Name: ${part}?`)) {
        const cmtId = document.querySelector('input[name="CMT_id"]').value;
        const formData = new FormData();
        formData.append('CMT_id', cmtId);
        formData.append('part_id', selectedRowId);

        fetch('job_order/delete_part.php', {
            method: 'POST',
            body: formData
        }).then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Error deleting part.');
            }
        });
    }
}*/
