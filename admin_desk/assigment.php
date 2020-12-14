<?php
require_once("../db.php");
session_start();
$id_tb = $_GET["id_tb"];
$id_lophoc = $_GET["id_lophoc"];
$sql = 'select * from thongbao where id_tb="'.$id_tb.'"';
$tb = executeResult($sql);

$sql = 'select * from file_kem_thongbao where id_thongbao = "' . $id_tb . '"';

$file_kem = executeResult($sql);

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
<!--<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>-->
<?php
$sql = 'select * from assigment where id_tb="'.$_GET["id_tb"].'"';
$result_assi = executeResult($sql);

?>
<body>
<div id="mySidenav" class="sidenav">
    <div>
        <img class="a_home MainInterfacebuttonimgUser img_avt" style="margin-left: auto; margin-right: auto" src="<?= $_SESSION['avatar'] ?>">
    </div>
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a class="a_home" href="./index.php" >Home</a>
    <?php
    $sql = 'select * from lophoc';
    $lophoc = executeResult($sql);
    $sql_tengv = 'select * from people,user_account where people.teacher=1 and people.ma_lophoc="'.$_SESSION['id_lophoc'].'" 
                and people.user_name = user_account.username
                 ';
    $tt_gv = executeResult($sql_tengv);
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
                <a class="peoplechustream" href="people.php">People</a>
                <a class="peoplechupeople" href="Baitap.php">Assigment</a>

            </div>
        </td>
        <td class="peoplebuttonavatar">
            <button type="button" class="btn MainInterfacebuttonUser">

                <img class="MainInterfacebuttonimgUser" src="<?= $tt_user[0]['avatar'] ?>">
            </button>
        </td>
    </tr>
    <tr class="people100pt">
        <th colspan="4" class="peopleallcot">
            <div class="peoplethesau">
                <a class="peoplechustream" href="Stream.php">Stream</a>
                <a class="peoplechustream" href="people.php">People</a>
                <a class="peoplechupeople" href="Baitap.php">Assigment</a>
            </div>
        </th>
    </tr>

</table>

<!-- end lam-->
<p class="maininterfacekhangtrang"></p>
<!--End Navbar-->
<div class="container people" onclick="closeNav()">

    <p></p>
    <div class="Peoplestudents">
        <div class="mb-2">
            <b>Tiêu đề: </b> <?= $tb[0]["tieude"] ?>
        </div>
        <div class="mb-2">
            <b>   Nội dung: </b> <?= $tb[0]["noidung"] ?>
            <?php


            foreach ($file_kem as $tmp) {
                echo ' <a class="ten_file_kem"  target="_blank" href="../download.php?filepath='.$tmp['duongdan_file'].'&filename='.$tmp['ten_file'].'">'.$tmp['ten_file'].'</a>';

            }
            ?>
        </div>
        <div class="mb-4">
            <b> Deadline: </b> <?= $tb[0]["deadline"] ?>
        </div>
        <?php
        if (isset($_POST[$id_tb])) {
            $id=  "idcmtassigment".time();
            $result = add_comment($id,$id_tb, $_SESSION['user'], $_POST[$id_tb] );
            $sql =  'select * from user_account, people,thongbao where user_account.username = people.user_name and thongbao.malophoc = people.ma_lophoc  and people.ma_lophoc="'.$_SESSION['id_lophoc'].'"';
            $result_mail = executeResult($sql);
            $sql_cmt ='select * from comment where id="'.$id.'"';
            $result_cmt = executeResult($sql_cmt);
            sent_email_cmt($result_mail,$result_cmt,$id_tb);
        }
        echo '      
                </div>
            <hr>  
              ';
        // truy van cmt
        $sql_cmt = 'select * from comment where id_thongbao="' . $id_tb . '"';
        $all_cmt = executeResult($sql_cmt);
        echo'<p class="Streamnumbercomment">'.count($all_cmt).' class comment</p>';
        foreach ($all_cmt as $cmt_index) {
            $sql = 'select * from user_account where username="'.$cmt_index['username_cmt'].'"';
            $user_cmt = executeResult($sql);
            echo '        
                <div class="div_cmt" >
                    <div class="cmt" >
                            <div class="div_flex">
                                <img class="MainInterfacebuttonimgUser Streamimg" src="'.$user_cmt[0]['avatar'].'">                  
                                <div class="padding-left-10">
                                    <div class="mg-top-10">'.$user_cmt[0]['firstname'].' '.$user_cmt[0]['lastname'].'</div>
                                    <div style="">' . $cmt_index['time'] . '</div>
                                </div>
                            </div>
                            <div class="Luongd3c">
                                <button class="Luongthc" data-toggle="dropdown">
                                    <i class=\'fas fa-ellipsis-v\'></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">                               
                                    <button class="dropdown-item bg-danger text-light" onclick=delete_cmt("'.$cmt_index["id"].'")>Xóa</button>
                                </div>
                            </div>                      
                    </div>
                    
                    <div class="padding-left-30"><pre>' . $cmt_index['noidung'] . '</pre></div>
                </div>        
            ';
        }
        echo'
          <div>
                 <form method="post" action="">                                            
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                             <img class="MainInterfacebuttonimgUser " src="'.$tt_user[0]['avatar'].'">   
                        </div>
                        <input required  class="form-control" type="text"  name="'.$id_tb.'" placeholder="add class comments"  >
                        <div class="input-group-append">
                            <button class="StreamSubmitComment" type="submit"  >Sent</button>
                        </div>                      
                    </div>
                </form>
            </div>
        ';
        ?>
        <span style="margin-top: 20px"> <span class="Peopleteachersword">Students </span>

            <hr class="peoplehrchinh">
        <?php
        foreach ($result_assi as $tmp){
            $sql = 'select * from user_account where username="'.$tmp["username"].'"';
            $user = executeResult($sql);
            echo'
                         <div class="infor">
                                <img class="maininterfaceavtmh" src="'.$user[0]["avatar"].'">
                                <span class="PeopleName">'.$user[0]["firstname"].' '.$user[0]["lastname"].'</span>
                                <span class="PeopleName ml-5">'.$tmp['time'].'</span>
                           
                                <a class="ten_file_kem ml-5"  target="_blank" href="../download.php?filepath='.$tmp['link'].'&filename='.$tmp['ten_file'].'">'.$tmp['ten_file'].'</a>
                         </div>
                         <hr class="">
                   ';
        }
        ?>

    </div>


</div>



</html>