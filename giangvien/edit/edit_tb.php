<?php
require_once ('../../db.php');
$id_tb = $_POST['id_tb'];
$time = date("Y/m/d");
$noidung = $_POST["noidung"];

if (isset($_POST['id_tb'])) {
    $sql = 'UPDATE thongbao set time="'.$time.'", noidung="'.$noidung.'" where id_tb = "'.$id_tb.'"';
    $conn = onpen_database();
    mysqli_query($conn, $sql);
    $sql = 'UPDATE file_kem_thongbao set time="'.$time.'", noidung="'.$noidung.'" where id_tb = "'.$id_tb.'"';
    $conn = onpen_database();
    mysqli_query($conn, $sql);

}
