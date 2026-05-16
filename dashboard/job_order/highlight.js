let selectedRowId = null;

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

    // Check if the clicked row is already selected
    if (selectedRowId === id) {
        // Unselect the row
        row.classList.remove('selected');
        selectedRowId = null; // Reset the selectedRowId

        // Disable Edit and Delete buttons
        document.getElementById('editButton').disabled = true;
        document.getElementById('deleteButton').disabled = true;
        document.getElementById('obButton').disabled = true;

        return; // Exit the function
    }

    // If a different row is clicked, update the selection
    selectedRowId = id;

    // Highlight selected row
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach(r => r.classList.remove('selected'));
    row.classList.add('selected');

    // Enable Edit and Delete buttons
    document.getElementById('editButton').disabled = false;
    document.getElementById('deleteButton').disabled = false;
    document.getElementById('obButton').disabled = false;
}

// Add this function to handle clicks outside the table
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
        document.getElementById('obButton').disabled = true;
    }
});

function editJobOrder() {
    if (!selectedRowId) {
        alert("Please select a job order to edit.");
        return;
    }

    const selectedRow = document.querySelector(`tr[data-id='${selectedRowId}']`);

    if (!selectedRow) {
        alert("Selected row not found.");
        return;
    }

    const cmt = selectedRow.cells[0].textContent; // Get the CMT as string
    const quantityText = selectedRow.cells[1].textContent.replace(/,/g, ''); // Remove commas
    const priceText = selectedRow.cells[2].textContent.replace(/,/g, ''); // Remove commas
    const bundleText = selectedRow.cells[3].textContent;

    // Convert values and handle possible NaN cases
    const quantity = parseFloat(quantityText);
    const price = parseFloat(priceText);
    const bundle = parseInt(bundleText, 10);

    if (isNaN(quantity) || isNaN(price) || isNaN(bundle)) {
        alert("Invalid data in selected row. Please check the values.");
        return;
    }

    // Populate modal fields
    document.getElementById('editCmt').value = cmt; 
    document.getElementById('editQuantity').value = quantity; 
    document.getElementById('editPrice').value = price;
    document.getElementById('editBundle').value = bundle;
    
    // Populate hidden ID field for form submission
    document.getElementById('editId').value = selectedRowId;

    openModal('editJobOrderModal');
}

function deleteJobOrder() {
    if (selectedRowId) {
        const selectedRow = document.querySelector(`tr[data-id='${selectedRowId}']`);

        if (!selectedRow) {
            alert("Selected row not found.");
            return;
        }

        // Access the CMT value from the first cell of the selected row
        const cmt = selectedRow.cells[0].textContent;

        if (confirm(`Are you sure you want to delete CMT#: ${cmt}?`)) {
            fetch(`job_order/delete_job_order.php?id=${selectedRowId}`, {
                method: 'DELETE'
            }).then(response => {
                if (response.ok) {
                    location.reload(); // Reload the page to reflect changes
                } else {
                    alert('Error deleting job order.');
                }
            });
        }
    } else {
        alert("Please select a job order to delete.");
    }
}

function operationBreakdown() {
    if (!selectedRowId) {
        alert("Please select a job order for operation breakdown.");
        return;
    }

    const selectedRow = document.querySelector(`tr[data-id='${selectedRowId}']`);
    if (!selectedRow) {
        alert("Selected row not found.");
        return;
    }

    // Get the values from the selected row
    const cmt = selectedRow.cells[0].textContent;
    const price = selectedRow.cells[2].textContent.replace(/,/g, ''); // Remove commas from price
    const bundle = selectedRow.cells[3].textContent;

    if (!cmt || !price || !bundle) {
        alert("Some required values are missing in the selected row.");
        return;
    }

    // Redirect to operation_breakdown.php with CMT, price, and bundle as query parameters
    const redirectUrl = `job_order/operation_breakdown.php?cmt=${encodeURIComponent(cmt)}&price=${encodeURIComponent(price)}&bundle=${encodeURIComponent(bundle)}`;
    window.location.href = redirectUrl;

}

