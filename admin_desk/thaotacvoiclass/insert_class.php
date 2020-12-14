<?php
    session_start();

    $conn = new mysqli("127.0.0.1", "root","","classroom");
    if(isset($_POST['insertdata'])){
        if(!$_FILES['file']['name'] == ""){
            // them hinh
            $filename = $_FILES['file']['name'];
            // Upload file
            move_uploaded_file($_FILES['file']['tmp_name'],'../../image/upload/'.$filename);
            $href = '../image/upload/'.$filename;

            $classname = $_POST['classname'];
            $subject = $_POST['subject'];
            $room = $_POST['room'];
            $img = $href;
            $classID = uniqid();
            if(is_uploaded_file($_FILES['img']['name'])){
                $img = $_FILES['img']['name'];
            }

            $sql = 'insert into lophoc(ma_lophoc, ten_lophoc, ten_monhoc, ten_phonghoc, ten_anh) values(?,?,?,?,?)';
            $stm = $conn -> prepare($sql);
            $stm -> bind_param('sssss', $classID,$classname, $subject, $room, $img);

            if($stm -> execute()){
                $sql = 'insert into people(ma_lophoc, user_name, teacher) values(?,?,?)';
                $stm = $conn -> prepare($sql);
                $teacher = "1";
                $stm -> bind_param('sss', $classID,$_SESSION['user'],$teacher);
                if($stm -> execute()){
                    header ('Location: ../index.php');
                }

            }
        }
        else{
            $classname = $_POST['classname'];
            $subject = $_POST['subject'];
            $room = $_POST['room'];

            $classID = uniqid();

            $sql = 'insert into lophoc(ma_lophoc, ten_lophoc, ten_monhoc, ten_phonghoc) values(?,?,?,?)';
            $stm = $conn -> prepare($sql);
            $stm -> bind_param('ssss', $classID,$classname, $subject, $room);

            if($stm -> execute()){
                $sql = 'insert into people(ma_lophoc, user_name, teacher) values(?,?,?)';
                $stm = $conn -> prepare($sql);
                $teacher = "1";
                $stm -> bind_param('sss', $classID,$_SESSION['user'],$teacher);
                if($stm -> execute()){
                    header ('Location: ../index.php');
                }

            }
        }




    }
?>
