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
    <title>AdminHub - Job Orders</title>
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
            <li>
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
            <li class="active">
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
                    <h1>Job Order</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Job Order</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="#">Home</a>
                        </li>
                    </ul>
                </div>              
            </div>
            
            <!-- Job Order Table -->
            <div class="container">
                <button onclick="openModal('jobOrderModal')">+ Add Job Order</button>

                <div id="jobOrderModal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal('jobOrderModal')">&times;</span>
                        <h2>Add Job Order</h2>
                        <form method="POST" action="job_order/add_job_order.php">
                            <input type="hidden" id="addId" name="job_order_id">
                            <label for="addCmt">CMT#:</label>
                            <input type="text" id="addCmt" name="cmt" placeholder="Enter CMT #" required>
                            
                            <label for="addQuantity">Quantity:</label>
                            <input type="text" id="addQuantity" name="quantity" placeholder="Enter Quantity" required>

                            <!--<label for="addPrice">Price:</label>
                            <input type="number" id="addPrice" name="price" placeholder="Enter Price per Quantity" required>-->
                            <label for="addPrice">Price:</label>
                            <input type="number" id="addPrice" name="price" placeholder="Calculated Automatically" readonly>

                            <label for="addBundle">Bundle:</label> <!-- New Bundle Field -->
                            <input type="number" id="addBundle" name="bundle" placeholder="Enter Number of Bundles" required>

                            <button type="submit">Add Job Order</button>
                        </form>
                    </div>
                </div>

                <div id="editJobOrderModal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal('editJobOrderModal')">&times;</span>
                        <h2>Edit Job Order</h2>
                        <form method="POST" action="job_order/edit_job_order.php">
                            <input type="hidden" id="editId" name="job_order_id">

                            <label for="editCmt">CMT#:</label>
                            <input type="text" id="editCmt" name="cmt" required readonly>

                            <label for="editQuantity">Quantity:</label>
                            <input type="number" id="editQuantity" name="quantity" required>

                            <!--<label for="editPrice">Price:</label>
                            <input type="number" id="editPrice" name="price" required>-->
                            <label for="editPrice">Price:</label>
                            <input type="number" id="editPrice" name="price" placeholder="Calculated Automatically" readonly>

                            <label for="editBundle">Bundle:</label> <!-- New Bundle Field -->
                            <input type="number" id="editBundle" name="bundle" required>

                            <button type="submit">Update Job Order</button>
                        </form>
                    </div>
                </div>
                <!-- Fetch job orders -->
                <table>
                    <thead>
                        <tr>
                            <th>CMT #</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Bundle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // Fetch job orders from database
                            $result = $conn->query("SELECT * FROM job_orders");

                            if ($result->num_rows > 0):
                                while($row = $result->fetch_assoc()): 
                        ?>
                        <tr onclick="handleRowClick(this)" data-id="<?= $row['id'] ?>">
                                <td><?= $row['CMT'] ?></td>
                                <td><?= number_format($row['quantity'], 0) ?></td>
                                <td><?= number_format($row['price'], 0) ?></td> 
                                <td><?= $row['bundle'] ?></td>
                        </tr>
                        <?php 
                                endwhile; 
                            else: 
                        ?>
                        <tr>
                            <td colspan="4">No job orders found.</td>
                        </tr>
                            <?php endif; ?>

                            <?php $conn->close(); ?>
                    </tbody>
                        <div class="action-buttons">
                            <button id="editButton" onclick="editJobOrder()" disabled>Edit</button>
                            <button id="deleteButton" onclick="deleteJobOrder()" disabled>Delete</button>
                            <button id="obButton" onclick="operationBreakdown()" disabled>Operation Breakdown</button>
                        </div>
                </table>
            </div>
        </main>
        <!-- MAIN -->

    </section>  
    <!-- CONTENT -->

    <script src="job_order/highlight.js"></script>
    <script src="script.js"></script>
    <script>
        async function fetchAndUpdatePrices() {
            // Fetch all rows in the job order table
            const rows = document.querySelectorAll("tbody tr[data-id]");
            for (const row of rows) {
                const cmt = row.cells[0].textContent.trim(); // Get the CMT number from the first cell
                const priceCell = row.cells[2]; // The Price column

                try {
                    // Fetch the total price from calculation.php
                    const response = await fetch(`job_order/calculation_price.php?cmt=${encodeURIComponent(cmt)}`);
                    const data = await response.json();

                    if (data.total_price) {
                        // Update the Price cell with the total price
                        priceCell.textContent = parseFloat(data.total_price).toFixed(2);
                    }
                } catch (error) {
                    console.error(`Error fetching price for CMT ${cmt}:`, error);
                }
            }
        }

        // Run the function on page load
        window.onload = fetchAndUpdatePrices;
    </script>


</body>
</html>
