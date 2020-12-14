<?php
$conn = new mysqli("127.0.0.1", "root", "", "classroom");

if(isset($_POST["updatedata"])){
    $classID = $_POST['class_id'];
    $classname = $_POST['classname'];
    $subject = $_POST['subject'];
    $room = $_POST['room'];
    $img = "";
    print_r($_FILES);
        if(!$_FILES['file']['name'] == ""){
            // them hinh
            $filename = $_FILES['file']['name'];
            // Upload file
            move_uploaded_file($_FILES['file']['tmp_name'],'../../image/upload/'.$filename);
            $href = '../image/upload/'.$filename;

            $img = $href;


            $sql = "UPDATE lophoc SET ten_lophoc = ?, ten_monhoc = ?, ten_phonghoc = ?, ten_anh = ? WHERE ma_lophoc= ?";
            $stm = $conn -> prepare($sql);
            $stm -> bind_param('sssss', $classname,$subject, $room,$img ,$classID);

            if($stm -> execute()){
                echo '<script> alert("Data Updated"); </script>';
                header ('Location: ../index.php');
            } else {
                echo '<script> alert("Data Not Updated"); </script>';
            }
        }
        else{
            $sql = "UPDATE lophoc SET ten_lophoc = ?, ten_monhoc = ?, ten_phonghoc = ? WHERE ma_lophoc= ?";
            $stm = $conn -> prepare($sql);
            $stm -> bind_param('ssss', $classname,$subject, $room ,$classID);

            if($stm -> execute()){
                echo '<script> alert("Data Updated"); </script>';
                header ('Location: ../index.php');
            } else {
                echo '<script> alert("Data Not Updated"); </script>';
            }
        }


}
?>


