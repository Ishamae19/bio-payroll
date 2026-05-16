let selectedRow = null; // Store the selected row

$(document).ready(function () {
    // Populate table on load
    populateEmployeeTable();

    // Open modal
    $('#addEmployeeButton').on('click', function () {
        openModal('addEmployeeModal');
    });

    $('.close').on('click', function () {
        const modalId = $(this).data('modal');
        closeModal(modalId);
    });

    // Row click handler
    $('#employeeTableBody').on('click', 'tr', function () {
        selectRow($(this));
    });

    // Edit button
    $('#editButton').on('click', editEmployee);

    // Delete button
    $('#deleteButton').on('click', deleteEmployee);

    // Add Employee Form Submit
    $('#addEmployeeForm').on('submit', function (e) {
        e.preventDefault();
        $.post('employees/add_employee.php', $(this).serialize(), function () {
            closeModal('addEmployeeModal');
            populateEmployeeTable();
        });
    });

    // Edit Employee Form Submit
    $('#editEmployeeForm').on('submit', function (e) {
        e.preventDefault();
        $.post('employees/update_employee.php', $(this).serialize(), function () {
            closeModal('editEmployeeModal');
            populateEmployeeTable();
        });
    });

    // Functions
    function openModal(modalId) {
        $('#' + modalId).show();
        $('body').css('overflow', 'hidden');
    }

    function closeModal(modalId) {
        $('#' + modalId).hide();
        $('body').css('overflow', '');
    }

    function selectRow(row) {
        if (selectedRow) selectedRow.removeClass('selected');
        row.addClass('selected');
        selectedRow = row;

        $('#editButton, #deleteButton').prop('disabled', false);

        const fingerprintId = row.find('td:first').text();
        console.log(fingerprintId);

        $.get('employees/manage_users_conf.php', { select: 1, Finger_id: fingerprintId }, function (response) {
            console.log(response);
        });
    }

    function editEmployee() {
        if (!selectedRow) {
            alert('Please select an employee to edit.');
            return;
        }

        const rowData = selectedRow.find('td').map(function () {
            return $(this).text();
        }).get();

        $('#editSno').val(rowData[1]);
        $('#editName').val(rowData[2]);
        $('#editOperation').val(rowData[3]);
        $('#editEmail').val(rowData[4]);
        $('#editPhone').val(rowData[5]);
        $('#editDateHired').val(rowData[6]);

        openModal('editEmployeeModal');
    }

    function deleteEmployee() {
        if (!selectedRow) return;
    
        const serial = selectedRow.find('td:eq(1)').text();
        
        if (confirm(`Are you sure you want to delete serial number: ${serial}?`)) {
            $.ajax({
                url: 'employees/delete_employee.php', 
                type: 'POST',
                data: { id: serial },
                success: function(response) {
                    alert('Employee deleted successfully.');
                    populateEmployeeTable(); 
                    $('#editButton, #deleteButton').prop('disabled', true); 
                    selectedRow = null; 
                },
                error: function(xhr, status, error) {
                    alert(`Error: ${xhr.responseText || "Could not delete employee."}`);
                }
            });
        }
    }

    function populateEmployeeTable() {
        $.get('employees/populateEmployee.php', function (data) {
            $('#employeeTableBody').html(data);
            resetSelection();
        });
    }

    function resetSelection() {
        selectedRow = null;
        $('#editButton, #deleteButton').prop('disabled', true);
    }
});
