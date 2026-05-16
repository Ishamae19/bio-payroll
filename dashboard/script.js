// SAPAG LIPAT NG PAGE
const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top li a');

allSideMenu.forEach(item => {
    const li = item.parentElement;

    item.addEventListener('click', function () {
        allSideMenu.forEach(i => {
            i.parentElement.classList.remove('active');
        });
        li.classList.add('active');
    });
});

// SAPAG TAGO NG MENU
const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

menuBar.addEventListener('click', function () {
    sidebar.classList.toggle('hide');
});

// Update the time every second
document.addEventListener('DOMContentLoaded', function () {
    let time = document.getElementById("current-time");

    function updateTime() {
        let d = new Date();
        time.innerHTML = d.toLocaleTimeString();
    }

    // Initial call to display time immediately
    updateTime();
    // Update time every second
    setInterval(updateTime, 1000);
});

function populateAttendanceTable() {
    $.get('get_attendance.php', function (data) {
        $('#attendanceTableBody').html(data);
    });
};

$(document).ready(function () {
    populateAttendanceTable();
    setInterval(populateAttendanceTable, 3000);
});