<?php
include 'db/conn.php';
include 'db/checker.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="mdbtcr.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>JDO - Employees</title>
</head>

<body>

    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bxs-business'></i>
            <span class="text">JDO Garments</span>
        </a>
        <ul class="side-menu top">
            <li>
                <a href="index.php">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="attendance.php">
                    <i class='bx bx-calendar'></i>
                    <span class="text">Attendance</span>
                </a>
            </li>
            <li class="active">
                <a href="employees.php">
                    <i class='bx bx-group'></i>
                    <span class="text">Employees</span>
                </a>
            </li>
            <li>
                <a href="report.php">
                    <i class='bx bx-file'></i>
                    <span class="text">Report</span>
                </a>
            </li>
            <li>
                <a href="job_order.php">
                    <i class='bx bx-briefcase-alt'></i>
                    <span class="text">Job Order</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="settings.php">
                    <i class='bx bxs-cog'></i>
                    <span class="text">Settings</span>
                </a>
            </li>
            <li>
                <a href="../login/logout.php" class="logout">
                    <i class='bx bxs-log-out-circle'></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- SIDEBAR -->

    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu'></i>
            <form action="#">
                <div class="form-input">
                    <!--<input type="search" placeholder="Search...">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>-->
                </div>
            </form>
            <a href="#" class="notification">
                <i class='bx bxs-bell'></i>
                <span class="num">8</span>
            </a>
            <a href="#" class="profile">
                <img src="user.png">
            </a>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Employees</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Employees</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">List</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Employee Table -->
            <div class="container">
                <button id="addEmployeeButton">+ Add Employee</button>
                <div id="addEmployeeModal" class="modal">
                    <div class="modal-content">
                        <span class="close" data-modal="addEmployeeModal">&times;</span>
                        <h2>Add New Employee</h2>
                        <form id="addEmployeeForm">
                            <label for="sNO">Serial Number:</label>
                            <input type="tel" id="sno" name="sno" required>

                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" required>

                            <label for="operation">Operation:</label>
                            <input type="text" id="operation" name="operation" required>

                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" required>

                            <label for="phone">Phone:</label>
                            <input type="tel" id="phone" name="phone" required>

                            <label for="date_hired">Date Hired:</label>
                            <input type="date" id="date_hired" name="date_hired" required>

                            <button type="submit">Add Employee</button>
                        </form>
                    </div>
                </div>

                <div id="editEmployeeModal" class="modal">
                    <div class="modal-content">
                        <span class="close" data-modal="editEmployeeModal">&times;</span>
                        <h2>Edit Employee</h2>
                        <form id="editEmployeeForm">
                            <input type="hidden" id="id">
                            <label for="sNO">Serial Number:</label>
                            <input type="tel" id="editSno" name="sno" required readonly>

                            <label for="editName">Name:</label>
                            <input type="text" id="editName" name="name" required>

                            <label for="editOperation">Operation:</label>
                            <input type="text" id="editOperation" name="operation" required>

                            <label for="editEmail">Email:</label>
                            <input type="email" id="editEmail" name="email" required>

                            <label for="editPhone">Phone:</label>
                            <input type="tel" id="editPhone" name="phone" required>

                            <label for="editDateHired">Date Hired:</label>
                            <input type="date" id="editDateHired" name="date_hired" required>

                            <button type="submit">Update Employee</button>
                        </form>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Serial Number</th>
                            <th>Name</th>
                            <th>Operation</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Date Hired</th>
                        </tr>
                    </thead>
                    <tbody id="employeeTableBody"></tbody>
                </table>

                <div class="action-buttons">
                    <button id="editButton" disabled>Edit</button>
                    <button id="deleteButton" disabled>Delete</button>
                </div>
            </div>
        </main>
        <!-- MAIN -->

    </section>
    <!-- CONTENT -->
    <script src="employees/highlight.js"></script>
    <!--<script src ="employees/script.js"></script>-->
    <script src="script.js"></script>
</body>

</html>