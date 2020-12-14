<?php
session_start();
require_once('../db.php');
$id = $_SESSION['id_lophoc'] ;
// thong tin lop hoc
$sql = 'SELECT * FROM lophoc WHERE ma_lophoc="'.$id.'"';
$lophoc = executeResult($sql);
// thong tin giang vien gui mail
$sql = 'select * from people,user_account where people.user_name=user_account.username and people.ma_lophoc = "'.$id.'" and people.teacher = "1" ';
$user = executeResult($sql);


if(isset($_POST['email'])){
    $email = $_POST['email'];
//    truy van mail cua sinh vien
    $sql = 'select * from user_account where email ="'.$email.'"  and teacher="0" ';
    $tt_sv = executeResult($sql);
    if(!empty($tt_sv)){
        // check xem da co trong lop chua
        $sql = 'select * from people where ma_lophoc="'.$id.'" and user_name = "'.$tt_sv[0]['username'].'"  ';
        $check_tt = executeResult($sql);
        if(!empty($check_tt)){
            echo'<script>alert("Người này đã ở trong lớp học này rồi");</script>';
        }else{
            $sendmail = sendmailacceptjoin_ofstudent($email,$user,$id);
            echo'<script>alert("'.$sendmail.'");</script>';
        }
    }else{
//        email sai
        echo'<script>alert("Email này không tồn tại !");</script>';
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
    <title>People</title>
    <link rel="stylesheet" href="../style.css">

    <script src="../main.js"></script>
</head>

<body>
<div id="mySidenav" class="sidenav">
    <div>
        <img class="a_home MainInterfacebuttonimgUser img_avt" style="margin-left: auto; margin-right: auto" src="<?= $_SESSION['avatar'] ?>">
    </div>
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a class="a_home" href="./index.php" >Home</a>

    <?php
    $sql = 'select * from lophoc, people where lophoc.ma_lophoc=people.ma_lophoc and people.user_name="'.$_SESSION["user"].'"';
    $lophoc_1 = executeResult($sql);
    $sql_tengv = 'select * from people,user_account where people.teacher=1 and people.ma_lophoc="'.$_SESSION['id_lophoc'].'" 
                and people.user_name = user_account.username
                 ';
    $tt_gv = executeResult($sql_tengv);
    // thong tin cua admin
    $sql = 'select * from user_account where username="'.$_SESSION['user'].'"';
    $tt_user = executeResult($sql);

    foreach ($lophoc_1 as $std) {
        $sql = 'select * from people,user_account where people.teacher=1 and people.ma_lophoc="'.$std['ma_lophoc'].'" 
                and people.user_name = user_account.username
                 ';
        $ten_gv=executeResult($sql);
        echo '
        <form method="post" action="Stream.php">
             <input hidden name="id_lophoc" value="'.$std["ma_lophoc"].'">
             <button class="maininterfacemonhoclist">
                 <table>
                    <tr>
                        <td>
                            <img  class="maininterfaceavtmh" src="' . $ten_gv[0]['avatar'] . '">
                        </td>
                        <td class="maininterfaceinformonhoc">
                            <div class="maininterfacetenmonlist">'.$std['ten_lophoc'].'</div>
                            <div class="maininterfacetengvlist">'.$std['ten_monhoc'].'</div>
                        </span>
                        </td>
                    </tr>
                  </table>
             </button>
        </form>
		        ';
    }
    ?>
</div>
<!--ket thuc menu side left-->
<table class="peoplebangnavbar">
    <tr>
        <td class="peoplebuttonmenu">
            <button class="maininterfacemonhoclist">
                <span class="" onclick="openNav()"><i class="MainInterfacebuttonmenu fas fa-bars"></i></span>
            </button>
        </td>
        <td class="peopletenmon">
            <a href="Stream.php">
                <div class="peoplemonhochieth">
                    <?=$lophoc[0]['ten_lophoc']?>
                </div>
                <div class="peoplegvhieth">
                 <?=$tt_gv[0]['firstname'].' '.$tt_gv[0]['lastname']?>
                </div>
            </a>
        </td>
        <td class="peoplechochih">
            <div>
                <a class="peoplechustream" href="Stream.php">Stream</a>
                <a class="peoplechupeople" href="people.php">People</a>
                <a class="peoplechustream" href="Baitap.php">Assigment</a>
            </div>
        </td>
        <td class="peoplebuttonavatar">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="MainInterfacebuttonimgUser" src="<?=$tt_user[0]["avatar"] ?>">
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item .text-primary .bg-primary" href="#">Profile</a>
                    <a class="dropdown-item  .text-danger .bg-danger" href="../logout.php">Log Out</a>
                </div>
            </div>
        </td>
    </tr>
    <tr class="people100pt">
        <th colspan="4" class="peopleallcot">
            <div class="peoplethesau">
                <a class="peoplechustream" href="Stream.php">Stream</a>
                <a class="peoplechupeople" href="people.php">People</a>
                <a class="peoplechustream" href="Baitap.php">Assigment</a>
            </div>
        </th>
    </tr>

</table>
    <p class="maininterfacekhangtrang"></p>
    <!--End Navbar-->
<div class="container people" onclick="closeNav()">
    <div class="Peopleteacher">
        <p class="Peopleteachersword">Teachers</p>
        <hr class="peoplehrchinh">
        <!--            <div class="infor">-->
        <!--                <img class="maininterfaceavtmh" src="../image/photo.png">-->
        <!--                <span class="PeopleName">Mai Văn Mạnh</span>-->
        <!--            </div>-->
        <!--            <hr>-->
        <?php
        $sql = 'select * from people,user_account where people.teacher=1 and people.ma_lophoc="'.$id.'" 
                and people.user_name = user_account.username
                 ';
        $name_gv = executeResult($sql);
        //            number sinh vien
        $sql_slsv = 'select count(*) as soluong_sv from people,user_account where people.teacher=0 and people.ma_lophoc="'.$id.'" 
                and people.user_name = user_account.username
                 ';
        $sinhvien_sl = executeResult($sql_slsv);

        foreach ($name_gv as $std) {
            echo'
            <div class="infor">
                <img class="maininterfaceavtmh" src=" '.$std['avatar'].' ">
                <span class="PeopleName">'.$std['firstname'].' '.$std['lastname'].'</span>
            </div>
               <hr>
                    ';
        }

        ?>
    </div>
    <p></p>
    <div class="Peoplestudents">
        <span class="Peopleteachersword">Classmates </span>

        <div class="Moinguoibtadd">
            <div class="peoplenumberstd"> <?php echo($sinhvien_sl[0]['soluong_sv']);  ?> student </div>
            <button class="mnbtadd"  data-toggle="modal" data-target="#addModal">
                <i class="fas fa-user-plus"></i>
            </button>
        </div>


        <hr class="peoplehrchinh">

        <?php
        $sql = 'select * from people,user_account where people.teacher=0 and people.ma_lophoc="'.$id.'" 
                and people.user_name = user_account.username
                 ';
        $name_gv = executeResult($sql);

        foreach ($name_gv as $std) {
            echo'
            <div class="infor">
                <img class="maininterfaceavtmh" src=" '.$std['avatar'].' ">
                <span class="PeopleName" id="'.$std['username'].'">'.$std['firstname'].' '.$std['lastname'].'</span>
                <div class="people_delete">
                    <button class="mnbtnDelete delete_data" id="'.$std['username'].'"><i class="fa fa-trash"></i></button>
                </div>
            </div>
               <hr>
                    ';
        }

        ?>

    </div>
    <div class="Moinguoiemptyhs">
        <img class="imglhempty" src="..\image\empty.png">
        <p>Mời học viên tham gia lớp học</p>
        <div>
            <button class=" Moinguoibtmoi"  data-toggle="modal" data-target="#addModal">Mời</button>

        </div>
    </div>

    <!--// modal add student bang nhap email-->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h4 class="modal-title w-100 font-weight-bold">Invite students</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-form-label">Email student:</label>
                            <input name="email" required class="form-control" type="text">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="insertdata"/>Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal-->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title w-100 font-weight-bold">Delete Student</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="./xoa/delete_student.php">
                    <input type="hidden" name="delete_id" id="delete_id"/>
                    <div class="modal-body">
                        <h6 id="delete_msg">Do you want to Remove this student??</h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-primary" name="deletedata"/>Yes, Remove it</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Delete Modal-->

</div>

</body>

</html>