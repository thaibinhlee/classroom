<?php
require_once ('../../db.php');
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = 'delete from comment where id = "'.$id.'"';
    $conn = onpen_database();
    mysqli_query($conn, $sql);
    echo mysqli_query;

}
