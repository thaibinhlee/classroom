<?php
require_once('../db.php');
session_start();
if (isset($_SESSION['user'])) {
    if ($_SESSION['permission'] == 1) {
        header('Location: ../admin_desk/index.php');
        exit();
    } else if ($_SESSION['permission'] == 0) {
        if(!$_SESSION['teacher']==1){
            header('Location: ../hocvien/index.php');
            exit();
        }
    }
} else {
    header('Location: ../login.php');
    exit();
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
    <style>

    </style>
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
                 <table >
                    <tr >
                        <td>
                            <img  class="maininterfaceavtmh" src="' . $ten_gv[0]['avatar'] . '">
                        </td>
                        <td class="maininterfaceinformonhoc ">
                            <div class="maininterfacetenmonlist  " >'.$std['ten_lophoc'].'</div>
                            <div class="maininterfacetengvlist  ">'.$std['ten_monhoc'].'</div>
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
                        Welcome Teacher
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
                <button class="MainInterfacebuttonadd" type="button" class="btn" data-toggle="modal" data-target="#addModal"><img class="MainInterfacebuttonimgUser" src="..\image\plus.png"></button>
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
        <button class="MainInterfacebuttonadd" type="button" class="btn" data-toggle="modal" data-target="#addModal"><img class="MainInterfacebuttonimgUser" src="..\image\plus.png"></button>
        <button type="button" class="btn MainInterfacebuttonUser">
            <img class="MainInterfacebuttonimgUser" src=".\image\photo.png">
        </button>

    </span>
    <hr>
    </span>
<!--tim kiem lop hoc-->


<section class="search-sec m-5">
    <div class="container">
        <form   method="post"  action="">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12 p-0">
                            <input type="text" name="search" required class="form-control search-slt" placeholder="   Search..">
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 p-0">
                            <select name="typesearch" class="form-control search-slt" id="exampleFormControlSelect1">
                                <option value="classname">Class name</option>
                                <option value="subjectname">Subject name</option>
                                <option value="room">Room</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-2 col-sm-12 p-0">
                            <button type="submit" class="btn btn-danger wrn-btn">Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>




<!-- Insert Modal form-->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold">Create class</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="./thaotacvoiclass/insert_class.php" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-form-label">Class name:</label>
                        <input name="classname" required class="form-control" type="text">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Subject:</label>
                        <input name="subject" required class="form-control" type="text">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Room:</label>
                        <input name="room" required class="form-control" type="text">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Class Image:</label>
                        <input type="file" name="file" accept='image/*' class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="insertdata"/>Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Insert modal form-->

<!-- Edit Modal form-->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold">Edit Class Information</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="./thaotacvoiclass/update_class.php" enctype="multipart/form-data">
                <input type="hidden" name="class_id" id="class_id"/>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-form-label">Class name:</label>
                        <input name="classname" id="classname" required class="form-control" type="text">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Subject:</label>
                        <input name="subject" id="subject" required class="form-control" type="text">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Room:</label>
                        <input name="room" id="room" required class="form-control" type="text">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Class Image:</label>
                        <input type="file" name="file" accept='image/*' class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="updatedata"/>Save change</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Edit modal form-->

<!-- Delete Modal-->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title w-100 font-weight-bold">Delete Class</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="./thaotacvoiclass/delete_class.php">
                <input type="hidden" name="delete_id" id="delete_id"/>
                <div class="modal-body">
                    <h5 id="delete_msg">Do you want to Remove this class??</h5>
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

    <!--End Navbar-->
    <div class="container-fluid " onclick="closeNav()">
        <?php
        if(isset($_POST["search"])){
            echo '<button style="margin-bottom: 25px;" type="button" class="btn btn-danger" onclick="location.href=\'./index.php\'" >Close Search</button>';
        }
        ?>

        <div class="row">
<!--        1 lop hoc-->
            <?php
if(isset($_POST["search"]) ){
        if($_POST["typesearch"]=="classname"){
            $sql = 'select * from lophoc, people where lophoc.ma_lophoc=people.ma_lophoc and people.user_name="'.$_SESSION["user"].'" and lophoc.ten_lophoc like "%'.$_POST["search"].'%"';

            $lophoc = executeResult($sql);
        }elseif ($_POST["typesearch"]=="subjectname"){
            $sql = 'select * from lophoc, people where lophoc.ma_lophoc=people.ma_lophoc and people.user_name="'.$_SESSION["user"].'" and lophoc.ten_monhoc like "%'.$_POST["search"].'%" ';
            $lophoc = executeResult($sql);

        }else{
            $sql = 'select * from lophoc, people where lophoc.ma_lophoc=people.ma_lophoc and people.user_name="'.$_SESSION["user"].'" and lophoc.ten_phonghoc like "%'.$_POST["search"].'%"';
            $lophoc = executeResult($sql);

        }
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
                                        <a class="dropdown-item edit_data" id="'.$std['ma_lophoc'].'" href="#">Edit</a>
                                        <a class="dropdown-item delete_data" id="'.$std['ma_lophoc'].'" href="#">Remove</a>
                                    </div>
                                    <h5>
                                    
                                       <button class="btn_class" >
                                            <div class="MaininterfaceTenmonhoc">'.$std['ten_lophoc'].'</div>
                                            <div class="maininterfacenamegv">'.$std['ten_monhoc'].'</div>
                                            <div class="maininterfacenamegv">'.$std['ten_phonghoc'].'</div>
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
		      ';
    }
}else{
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
                                        <a class="dropdown-item edit_data" id="'.$std['ma_lophoc'].'" href="#">Edit</a>
                                        <a class="dropdown-item delete_data" id="'.$std['ma_lophoc'].'" href="#">Remove</a>
                                    </div>
                                    <h5>
                                    
                                        <button class="btn_class" >
                                            <div class="MaininterfaceTenmonhoc">'.$std['ten_lophoc'].'</div>
                                            <div class="maininterfacenamegv">'.$std['ten_monhoc'].'</div>
                                              <div class="maininterfacenamegv">'.$std['ten_phonghoc'].'</div>
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
		      ';
    }
}
            ?>
 <!--        1 lop hoc-->
        </div>
    </div>

</body>
</html>