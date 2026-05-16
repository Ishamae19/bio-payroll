<?php
include 'db/checker.php';
include 'db/conn.php';

// Fetch employees and their operations
$query = "SELECT name, operation FROM employees";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="mdbtcr.css">

	<title>JDO</title>
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
					<i class='bx bxs-dashboard' ></i>
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
			<li class="active">
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
					<i class='bx bxs-cog' ></i>
					<span class="text">Settings</span>
				</a>
			</li>
			<li>
				<a href="../login/logout.php" class="logout">
					<i class='bx bxs-log-out-circle' ></i>
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
			<i class='bx bx-menu' ></i>
			<form action="#">
				<div class="form-input">
					<!--<input type="search" placeholder="Search...">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>-->
				</div>
			</form>
			<a href="#" class="notification">
				<i class='bx bxs-bell' ></i>
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
					<h1>Report</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Report</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#">Home</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="container">
				<h2>Employee Report</h2>
					<table>
						<thead>
							<tr>
								<th>Employee Name</th>
								<th>Operation</th>
								<th>Day 1</th>
								<th>Day 2</th>
								<th>Day 3</th>
								<th>Day 4</th>
								<th>Day 5</th>
								<th>Day 6</th>
								<th>Total</th>
							</tr>
						</thead>
						<tbody>
							<?php
							// Fetch employee data from database
							$query = "SELECT name, operation FROM employees";
							$result = mysqli_query($conn, $query);

							// Placeholder for demonstration purposes
							while($row = mysqli_fetch_assoc($result)) {
								$employeeName = $row['name'];
								$operation = $row['operation'];
								$days = [rand(0, 10), rand(0, 10), rand(0, 10), rand(0, 10), rand(0, 10), rand(0, 10)]; // Placeholder day values
								$total = array_sum($days);

								echo "<tr>";
								echo "<td>$employeeName</td>";
								echo "<td>$operation</td>";
								foreach($days as $day) {
									echo "<td>$day</td>";
								}
								echo "<td>$total</td>";
								echo "</tr>";
							}
							?>
						</tbody>
					</table>
			</div>
		</main>


	<script src="script.js"></script>
</body>
</html>