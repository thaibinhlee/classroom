<?php
    $conn = new mysqli("127.0.0.1", "root", "", "classroom");

    if(isset($_POST["deletedata"])){
        $classID = $_POST['delete_id'];

        $sql = "DELETE FROM lophoc WHERE ma_lophoc= ?";
        $stm = $conn -> prepare($sql);
        $stm -> bind_param('s',$classID);

        if($stm -> execute()){
            echo '<script> alert("Data Deleted"); </script>';
            header ('Location: ../index.php');
        } else {
            echo '<script> alert("Data Not Deleted"); </script>';
        }
    }
?>