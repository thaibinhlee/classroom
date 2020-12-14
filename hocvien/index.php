<?php
session_start();
require_once('../db.php');
if (isset($_SESSION['user'])) {
    if ($_SESSION['permission'] == 1) {
        header('Location: ../admin_desk/index.php');
        exit();
    } else if ($_SESSION['permission'] == 0) {
        if($_SESSION['teacher']==1){
            header('Location: ../giangvien/index.php');
            exit();
        }
    }
} else {
    header('Location: ../login.php');
    exit();
}

if(isset($_POST['leaveclass'])){
    $ma_lophoc = $_POST['id_class'];
    $sql = 'delete  from people where user_name = "'.$_SESSION['user'].'" and ma_lophoc = "'.$ma_lophoc.'"';

    leave_class($sql);
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
    <title>Classes</title>
    <link rel="stylesheet" href="../style.css">
    <script src="../main.js"></script>

</head>

<body >

<div id="mySidenav" class="sidenav">
    <div>
        <img class="a_home MainInterfacebuttonimgUser img_avt" style="margin-left: auto; margin-right: auto" src="<?= $_SESSION['avatar'] ?>">
    </div>
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a class="a_home" href="./index.php" >Home</a>
    <?php
    $sql = 'select * from lophoc, people where lophoc.ma_lophoc=people.ma_lophoc and people.user_name="'.$_SESSION["user"].'"';
    $lophoc = executeResult($sql);

    // thong tin cua admin
    $sql = 'select * from user_account where username="'.$_SESSION['user'].'"';
    $tt_user = executeResult($sql);

    foreach ($lophoc as $std) {
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
<div class="bang fixed-top">
    <table class="peoplebangnavbar">
        <tr>
            <td class="peoplebuttonmenu">
                <button class="maininterfacemonhoclist">
                    <span class="" onclick="openNav()"><i class="MainInterfacebuttonmenu fas fa-bars"></i></span>
                </button>
            </td>
            <td class="peopletenmon">

                <a href="#">
                    <div class="peoplemonhochieth">
                        Welcome Student
                    </div>
                    <div class="peoplegvhieth">
                        <?= $_SESSION["name"] ?>
                    </div>
                </a>

            </td>
            <td class="peoplechochih">
                <div>



                </div>
            </td>
            <td class="peoplebuttonavatar">
                <button class="MainInterfacebuttonadd" type="button" class="btn" data-toggle="modal" onclick="location.href='./EnterCode.php'"><img class="MainInterfacebuttonimgUser" src="..\image\plus.png"></button>
                <button type="button" class="btn MainInterfacebuttonUser" onclick="location.href='../logout.php'">
                    <img class="MainInterfacebuttonimgUser" src="..\image\signout.png">
                </button>

                <img class="MainInterfacebuttonimgUser" src="<?= $tt_user[0]['avatar'] ?>">
            </td>
        </tr>
        <tr class="people100pt">
            <th colspan="4" class="peopleallcot">
                <div class="peoplethesau">

                </div>
            </th>
        </tr>

    </table>
</div>
<span  class="MainInterfacebuttonmenu" onclick="openNav()"><i class="MainInterfacebuttonmenu fas fa-bars"></i></span>
<img class="imagegoogle" src="..\image\google.jpg">
<span class="wordclassroom">Classroom</span>
<!-- Navbar links -->

<span style="float:right;float:top">
        <button class="MainInterfacebuttonadd" type="button" class="btn">+</button>
        <button type="button" class="btn MainInterfacebuttonUser">
            <img class="MainInterfacebuttonimgUser" src=".\image\photo.png">
        </button>

    </span>
<hr>
</span>


<!--End Navbar-->
<div class="container-fluid" onclick="closeNav()">
    <div class="row">
        <!--        1 lop hoc-->
        <?php
        $sql = 'select * from lophoc, people where lophoc.ma_lophoc=people.ma_lophoc and people.user_name="'.$_SESSION["user"].'"';
        $lophoc = executeResult($sql);

        foreach ($lophoc as $std) {
            $sql = 'select * from people,user_account where people.teacher=1 and people.ma_lophoc="'.$std['ma_lophoc'].'" 
                and people.user_name = user_account.username
                 ';

            $ten_gv=executeResult($sql);

            echo '
        
        
        <div class=" khung col-xl-3 col-lg-4 col-md-6 col-sm-12 col-12"> 
               <form method="post" action="Stream.php">  
                        <input hidden name="id_lophoc" value="'.$std["ma_lophoc"].'">                     
                        <div class="cell">
                            <div class="">
                                <div></div>
                                <div class="maininterfacekhungmh">
                                    <button class="maininterfacebt3c" data-toggle="dropdown">
                                        <i class=\'fas fa-ellipsis-v\'></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                           
                                       <button type="button" class="btn btn-primary dropdown-item" data-toggle="modal" data-target="#a'.$std['ma_lophoc'].'"> Leave  </button>
             
                                    </div>
                                    <h5>                      
                                        <button class="btn_class" >
                                            <div class="MaininterfaceTenmonhoc">'.$std['ten_lophoc'].'</div>
                                            <div class="maininterfacenamegv">'.$std['ten_monhoc'].'</div>
                                        </button>   
                                    </h5>
                                    <div>
                                        <div class="maininterfacenamegv">'.$ten_gv[0]['firstname'].' '.$ten_gv[0]['lastname'].'</div>
                                    </div>
                                </div>
                            </div>
                             <div class="interfacendtb" style="background-image: url('.$std['ten_anh'].')" >
                                <img  class="MainInterfaceavatar" src="'.$ten_gv[0]['avatar'].'">
                            </div>
                        </div>
               </form>      
        </div>
        <div class="modal fade" id="a'.$std['ma_lophoc'].'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
             <form method="post" action="./index.php">            
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <input hidden name="id_class" value="'.$std['ma_lophoc'].'">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Bạn muốn rời khỏi lớp học này ?</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>                 
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                            <button type="submit" name="leaveclass" class="btn btn-primary">Yes</button>
                        </div>
                    </div>
                </div>
              </form>
        </div>
		      ';
        }
        ?>
        <!--        1 lop hoc-->
        <!-- Button trigger modal -->

    </div>
</div>

</body>
</html>