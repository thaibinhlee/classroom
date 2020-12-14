<?php
    $conn = new mysqli("127.0.0.1", "root", "", "classroom");
    if (isset($_POST["class_id"])) {
        $sql = "SELECT * FROM lophoc WHERE ma_lophoc = '".$_POST["class_id"]."'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result);
        echo json_encode($row);
}
?>