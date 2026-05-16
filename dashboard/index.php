<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db/conn.php';
include 'db/checker.php';


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="style.css">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
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
			<li class="active">
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
					<h1>Dashboard</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Dashboard</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#">Home</a>
						</li>
					</ul>
				</div>
				
			</div>

			<ul class="box-info">
				<li>
					<i class='bx bx-group'></i>
					<span class="text">
						<h3>60</h3>
						<a class="active" href="#">Total Employees</a>
					</span>
				</li>
				<li>
					<i class='bx bxs-time-five' ></i>
					<span class="text">
						<h3>50</h3>
						<a class="active" href="#">On Time Today</a>
					</span>
				</li>
				<li>
					<i class='bx bxs-timer' ></i>
					<span class="text">
						<h3>10</h3>
						<a class="active" href="#">Late Today</a>
					</span>
				</li>
			</ul>

			<div class="time">
    			<h1 id="current-time"></h1>
			</div>
	</section>
	<script src="script.js"></script>
</body>
</html>