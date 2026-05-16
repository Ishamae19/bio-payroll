<?php 
include '../db/conn.php';
include '../db/checker.php';


if (!isset($_GET['cmt']) || empty(trim($_GET['cmt']))) {
    echo "CMT is required";
    exit;
}

$cmt = htmlspecialchars($_GET['cmt']);
$original_price = isset($_GET['price']) ? htmlspecialchars($_GET['price']) : 'N/A';
$bundle = isset($_GET['bundle']) ? htmlspecialchars($_GET['bundle']) : 'N/A';
// Sanitize the CMT value to use it as a table name safely
$cmt_table = preg_replace('/[^A-Za-z0-9_]/', '_', $cmt);

$query = "SELECT SUM(price) AS total_price FROM `$cmt_table`";
$result = $conn->query($query);
$total_price = ($result && $row = $result->fetch_assoc()) ? $row['total_price'] : 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../mdbtcr.css">
    <title>Operation Breakdown - <?= htmlspecialchars($cmt) ?></title>
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
                <a href="../index.php">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="../attendance.php">
                    <i class='bx bx-calendar'></i>
                    <span class="text">Attendance</span>
                </a>
            </li>
            <li>
                <a href="../employees.php">
                    <i class='bx bx-group'></i>
                    <span class="text">Employees</span>
                </a>
            </li>
            <li>
                <a href="../report.php">
                    <i class='bx bx-file'></i>
                    <span class="text">Report</span>
                </a>
            </li>
            <li class="active">
                <a href="../job_order.php">
                    <i class='bx bx-briefcase-alt'></i>
                    <span class="text">Job Order</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="#">
                    <i class='bx bxs-cog'></i>
                    <span class="text">Settings</span>
                </a>
            </li>
            <li>
                <a href="../../login/logout.php" class="logout">
                    <i class='bx bxs-log-out-circle'></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </section>

    <!-- CONTENT -->
    <section id="content">
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
                    <img src="../user.png">
                </a>
        </nav>

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>CMT #: <?= htmlspecialchars($cmt) ?></h1>
                    <h4>Price per Quantity: <?= htmlspecialchars($total_price) ?></h4>
                    <h4>Number of Bundles: <?= htmlspecialchars($bundle) ?></h4>
                </div>
            </div>
            <div class="container">
                <button onclick="openModal('partModal')">+ Add Part</button>
               <!-- Add Part Modal -->
                <div id="partModal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal('partModal')">&times;</span>
                        <h2>Add CMT Parts</h2>
                        <form method="POST" action="part/add_part.php">
                            <input type="hidden" name="CMT_id" value="<?= htmlspecialchars($cmt) ?>">
                            <input type="hidden" name="oriPrice" value="<?= htmlspecialchars($original_price) ?>">
                            <input type="hidden" name="oriBundle" value="<?= htmlspecialchars($bundle) ?>">

                            <label for="addPart">Part:</label>
                            <input type="text" id="addPart" name="part" placeholder="Enter Part" required>
                            
                            <label for="addPrice">Price:</label>
                            <input type="number" id="addPrice" name="price" placeholder="Enter Price" step="0.01" required>

                            <!-- Dynamic bundle inputs for Add Part -->
                            <?php for ($i = 1; $i <= $bundle; $i++): ?>
                                <label for="bundle<?= $i ?>">Bundle <?= $i ?> Quantity:</label>
                                <input type="number" id="bundle<?= $i ?>" name="bundle[<?= $i ?>]" placeholder="Enter Quantity for Bundle <?= $i ?>" required>
                            <?php endfor; ?>

                            <button type="submit">Add CMT Part</button>
                        </form>
                    </div>
                </div>

                <!-- Edit Part Modal -->
                <div id="editPartModal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal('editPartModal')">&times;</span>
                        <h2>Edit CMT Parts</h2>
                        <form method="POST" action="part/edit_part.php">
                            <input type="hidden" id="editId" name="CMT_Id" value="<?= htmlspecialchars($cmt) ?>">
                            <input type="hidden" id="Id" name="part_id">
                            <label for="editPart">Part:</label>
                            <input type="text" id="editPart" name="part" required>
                            
                            <label for="editPrice">Price:</label>
                            <input type="number" id="editPrice" name="price" required>

                            <!-- Dynamic bundle inputs for Edit Part -->
                            <?php for ($i = 1; $i <= $bundle; $i++): ?>
                                <label for="editBundle<?= $i ?>">Bundle <?= $i ?> Quantity:</label>
                                <input type="number" id="editBundle<?= $i ?>" name="bundle[<?= $i - 1 ?>]" required>
                            <?php endfor; ?>

                            <button type="submit">Edit CMT Part</button>
                        </form>
                    </div>
                </div>
                <div class="table-wrapper">
                    <table class="table-fixed">
                        <thead>
                            <tr>
                                <th>Part Name</th>
                                <th>Price</th>
                                <?php for ($i = 1; $i <= $bundle; $i++): ?>
                                    <th>Bundle <?= $i ?></th>
                                <?php endfor; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch parts and bundle data from the dynamic CMT table
                            $query = "SELECT * FROM `$cmt_table`";
                            $result = $conn->query($query);

                            if ($result && $result->num_rows > 0):
                                while($row = $result->fetch_assoc()): 
                            ?>
                            <tr onclick="handleRowClick(this)" data-id="<?= $row['id'] ?>">
                                <td><?= htmlspecialchars($row['part_name']) ?></td>
                                <td><?= htmlspecialchars($row['price']) ?></td> 
                                <?php for ($i = 1; $i <= $bundle; $i++): ?>
                                    <td class="bundle-column"><?= htmlspecialchars($row["bdl$i"]) ?? 0 ?></td>
                                <?php endfor; ?>
                            </tr>
                            <?php 
                                endwhile; 
                            else: 
                            ?>
                            <tr>
                                <td colspan="<?= 2 + $bundle ?>">No records found for CMT <?= htmlspecialchars($cmt) ?>.</td>
                            </tr>
                            <?php 
                            endif;
                            $conn->close(); 
                            ?>
                        </tbody>
                            <div class="action-buttons">
                                <button id="editButton" onclick="editCMTPart()" disabled>Edit</button>
                                <button id="deleteButton" onclick="deleteCMTPart()" disabled>Delete</button>
                            </div>
                            <div class="back">
                                <button onclick="goToJobOrder()">Back</button>
                            </div>
                    </table>
                </div>
            </div>
        </main>
    </section>

    <script src="part/detail.js"></script>
    <script src="../script.js"></script>

    <script>
        function goToJobOrder() {
            window.location.href = '../job_order.php';
        }
    </script>
</body>
</html>
