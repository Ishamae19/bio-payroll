<?php
include '../db/conn.php';

$sql = "SELECT * FROM employees WHERE del_fingerid=0 ORDER BY id DESC";
$result = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($result, $sql)) {
    echo '<p class="error">SQL Error</p>';
} else {
    mysqli_stmt_execute($result);
    $resultl = mysqli_stmt_get_result($result);
    if (mysqli_num_rows($resultl) > 0) {
        while ($row = mysqli_fetch_assoc($resultl)) {
?>
<tr>
    <td hidden><?= $row['fingerprint_id'] ?></td>
    <td><?= $row['serialnumber'] ?></td>
    <td><?= $row['name'] ?></td>
    <td><?= $row['operation'] ?></td>
    <td><?= $row['email'] ?></td>
    <td><?= $row['phone'] ?></td>
    <td><?= $row['date_hired'] ?></td>
</tr>
<?php
        }
    } else {
        ?>
<tr>
    <td colspan="8">No employees found.</td>
</tr>

<?php
    }
}
?>