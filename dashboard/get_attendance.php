<?php
include 'db/conn.php';

$sql = "SELECT * FROM attendance WHERE checkindate = CURDATE()";
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
                <td><?= $row['serialnumber'] ?></td>
                <td><?= $row['name'] ?></td>
                <td><?= $row['checkindate'] ?></td>
                <td><?= $row['timein'] ?></td>
                <td><?= $row['timeout'] ?></td>
            </tr>
        <?php
        }
    } else {
        ?>
        <tr>
            <td colspan="5">No attendance found.</td>
        </tr>

<?php
    }
}
?>