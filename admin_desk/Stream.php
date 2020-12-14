<?php
require_once("../db.php");
session_start();
if(isset($_POST['id_lophoc'])){
    $_SESSION['id_lophoc'] = $_POST['id_lophoc'];
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
    <title>Stream</title>
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
    <a class="a_home" href="./quanli_taikhoan.php" >Manager Account</a>

    <?php
    $sql = 'select * from lophoc';
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
<!--    in ra thong tin lop hoc-->
    <?php
    $sql = 'SELECT * FROM lophoc WHERE ma_lophoc="'.$_SESSION['id_lophoc'].'"';
    $lophoc = executeResult($sql);
    $sql_tengv = 'select * from people,user_account where people.teacher=1 and people.ma_lophoc="'.$_SESSION['id_lophoc'].'" 
                and people.user_name = user_account.username
                 ';
    $tt_gv = executeResult($sql_tengv);



//    in ra thog tin tren menu
    echo'
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
                            '.$lophoc[0]['ten_lophoc'].'
                        </div>
                        <div class="peoplegvhieth">
                            '.$tt_gv[0]['firstname'].' '.$tt_gv[0]['lastname'].'
                        </div>
                    </a>
                </td>
                <td class="peoplechochih">
                    <div>
                        <a class="peoplechupeople" href="./Stream.php">Stream</a>
                        <a class="peoplechustream" href="./people.php">People</a>
                        <a class="peoplechustream" href="./Baitap.php">Assigment</a>
                        
                    </div>
                </td>
                <td class="peoplebuttonavatar">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="MainInterfacebuttonimgUser" src="'.$tt_user[0]["avatar"].'">
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
                        <a class="peoplechupeoplesau" href="#">Stream</a>
                        <a class="peoplechustreamsau" href="#">People</a>
                    </div>
                </th>
            </tr>

        </table>
    </div> ';
 ?>
    <p class="maininterfacekhangtrang"></p>
    <!--End Navbar-->
    <div class="container thanhduoi" onclick="closeNav()">
        <div class="StreamInforSj" >
            <p class="StreamnameSj">  <?= $lophoc[0]['ten_lophoc'] ?> </p>
            <p class="Streamnamegv"> Subject name: <?= $lophoc[0]['ten_monhoc'] ?> </p>
            <p class="Streamnamegv"> Room: <?= $lophoc[0]['ten_phonghoc'] ?> </p>
            <p class="Streamnamegv"> Class code: <?= $lophoc[0]['ma_lophoc'] ?> </p>
            <div class="Streamnamegv"><img class="EntercodeImg avt Streamimage"  src="<?= $tt_gv[0]['avatar']?>"> <?= $tt_gv[0]['firstname'].' '.$tt_gv[0]['lastname'] ?> </div>
        </div>

        <?php

        ?>
        <?php
//       da khac phuc auto post, gửi mail thông báo khi có ng đăng, đăng thông báo

        if(isset($_POST['submit'])){
            $id_tb = "idtb".time();
            if($_POST['content_tb']){
                $malophoc= $_SESSION['id_lophoc'];
                $username_post = $_SESSION['user'];
                $noidung = $_POST['content_tb'];
                $result = add_thongbao($id_tb,$malophoc,$username_post,$noidung);

                $sql =  'select * from user_account, people,thongbao where user_account.username = people.user_name and thongbao.malophoc = people.ma_lophoc  and people.ma_lophoc="'.$_SESSION['id_lophoc'].'"';
                $result_mail = executeResult($sql);
                $sql_tb ='select * from thongbao where id_tb="'.$id_tb.'"';
                $result_tb = executeResult($sql_tb);
                sent_email_post_tb($result_mail,$result_tb);
            }
            if(isset($_FILES['file'])){
                // Count total files
                // Looping all files
                $filename = $_FILES['file']['name'];
                // Upload file
                move_uploaded_file($_FILES['file']['tmp_name'],'../upload/'.$filename);

                $href = './upload/'.$filename;
                $result = add_filekem($id_tb,$filename,$href);
                if($result['code']==1){
                    echo '<script> alert("Thất bại, file phải có dung lượng dưới 400KB"); </script>';
                }
            }
        }
        // chinh sua
        $conn = onpen_database();
        if(isset($_POST['submit_edit'])){
            $id_tb = $_POST["id_thongbao_edit"];
            if($_POST['content_tb_edit']){
                $time = date("Y/m/d");
                $noidung = $_POST['content_tb_edit'];
                $sql = 'UPDATE thongbao set time="'.$time.'", noidung="'.$noidung.'" where id_tb = "'.$id_tb.'"';
                $resultset = mysqli_query($conn,$sql);
            }
            if(isset($_FILES['file'])){
                // Count total files
                $countfiles = count($_FILES['file']['name']);
                // Looping all files
                $sql = 'DELETE FROM file_kem_thongbao WHERE id_thongbao="'.$_POST["id_thongbao_edit"].'"';
                $resultset = mysqli_query($conn,$sql);
                for($i=0;$i<$countfiles;$i++){
                    $filename = $_FILES['file']['name'][$i];
                    // Upload file
                    move_uploaded_file($_FILES['file']['tmp_name'][$i],'../upload/'.$filename);
                    $href = './upload/'.$filename;
                    $result = add_filekem($id_tb,$filename,$href);
                    if($result['code']==1){
                        echo("that bai file kem");
                    }
                }
            }
        }
        // phan tao assigment
        if(isset($_POST['submit_assigment'])){
            if($_POST['noidung'] && $_POST['tieude']){
                $id_tb = "idtb".time();
                $malophoc= $_SESSION['id_lophoc'];
                $username_post = $_SESSION['user'];
                $noidung = $_POST['noidung'];
                $tieude = $_POST["tieude"];
                $deadline = $_POST["deadline"];
                $result = add_thongbao_assigment($tieude,$id_tb,$malophoc,$username_post,$noidung,$deadline);
                $sql =  'select * from user_account, people,thongbao where user_account.username = people.user_name and thongbao.malophoc = people.ma_lophoc  and people.ma_lophoc="'.$_SESSION['id_lophoc'].'"';
                $result_mail = executeResult($sql);
                $sql_tb ='select * from thongbao where id_tb="'.$id_tb.'"';
                $result_tb = executeResult($sql_tb);
                sent_email_post_tb($result_mail,$result_tb);
            }
            if(isset($_FILES['file'])){
                // Count total files
                // Looping all files
                    $filename = $_FILES['file']['name'];
                    // Upload file
                    move_uploaded_file($_FILES['file']['tmp_name'],'../upload/'.$filename);
                    $href = './upload/'.$filename;
                    $result = add_filekem($id_tb,$filename,$href);
                    if($result['code']==1){
                        echo("that bai file kem");
                    }
            }
        }
        ?>
        <!-- Thêm  -->
    <form method="post" action="#" enctype="multipart/form-data">
        <div class="StreamSharesomething">
            <div class="streamtheanh">
                <img id="Streamimage" class="MainInterfacebuttonimgUser Streamimg" src="<?= $tt_user[0]['avatar'] ?>">
            </div>
            <textarea required id="Streaminput"  name="content_tb" onclick="Sharesomething()" class="StreamInputsaysomething" type="text" placeholder="Share something with your class...."></textarea>
            <div id="Streambutton">
                <div class="the1">
                    <input class="streamchontep" type="file" placeholder="file" name="file" >
                </div>
                <div class="the2">
                    <input type="submit" name="submit"  class="Streambuttonpost"></input>
                    <button onclick="closethebuttton()" class="Streambuttoncancel">Cancel</button>
                </div>
            </div>
        </div>
    </form>
        <!-- End  -->
        <?php
        $sql = 'select * from thongbao where malophoc="'.$_SESSION['id_lophoc'].'"';
        $thongbao = executeResult($sql);
        // in ra ten người đăng

//        in file dinh kem

        foreach ($thongbao as $index_tb) {
        if($index_tb['deadline']==null){
            $sql = 'select * from file_kem_thongbao where id_thongbao = "' . $index_tb['id_tb'] . '"';
            $file_kem = executeResult($sql);
            // add cmt va gui mail
            if (isset($_POST[$index_tb['id_tb']])) {
                $id=  "idcmt".time();
                $result = add_comment($id,$index_tb['id_tb'], $_SESSION['user'], $_POST[$index_tb['id_tb']] );
                $sql =  'select * from user_account, people,thongbao where user_account.username = people.user_name and thongbao.malophoc = people.ma_lophoc  and people.ma_lophoc="'.$_SESSION['id_lophoc'].'"';
                $result_mail = executeResult($sql);
                $sql_cmt ='select * from comment where id="'.$id.'"';
                $result_cmt = executeResult($sql_cmt);
                sent_email_cmt($result_mail,$result_cmt,$index_tb['id_tb']);
            }
            $sql = 'select * from user_account where username="'.$index_tb['username_post'].'"';
            $user_post=executeResult($sql);
            echo '
        <div class="LuongThongbao">
            <div class="Luongo1">
                <div>
                    <div class="Luongphandau">
                        <img class="EntercodeImg avt Streamimage"  src="' . $user_post[0]['avatar'] . '">
                        <div class="Luongntcmt">
                            <div class="Luongnamecmt"><span> ' . $user_post[0]['firstname'] . ' ' . $user_post[0]['lastname'] . ' </span></div><br>
                            <div class="luongtgcmt"><p>' . $index_tb['time'] . '</p></div>
                        </div>
                    </div>
     
                    <div class="Luongd3c">
                        <button class="Luongthc" data-toggle="dropdown">
                            <i class=\'fas fa-ellipsis-v\'></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                 
                             <!-- Trigger the modal with a button -->
                            <button type="button" class="btn btn-success w-100 h-25" data-toggle="modal" data-target="#'.$index_tb["id_tb"].'">Chỉnh sửa</button>
                            <br>                                                                      
                            <button class="btn bg-danger text-light w-100 h-25" onclick=delete_tb("'.$index_tb["id_tb"].'")>Xóa</button>
                        </div>
                    </div>
                </div> 
        <div class="container">
            <!-- Modal -->        
            <div class="modal fade" id="'.$index_tb["id_tb"].'" role="dialog">
              <form method="post" action="" enctype="multipart/form-data">
                <input hidden name="id_thongbao_edit" value="'.$index_tb["id_tb"].'"></input>
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">                    
                            <h4 class="modal-title"><i class="fa fa-bullhorn" aria-hidden="true"></i>Announcement</h4>
                        </div>
                        <div class="modal-body">
                            <textarea required name="content_tb_edit" class="form-control" style="height: 30vh" aria-label="With textarea" placeholder="Share something with your class...."></textarea>
                            <input  name="file[]" type="file" multiple>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn_tb btn-default" data-dismiss="modal">Close</button>
                            <button name="submit_edit" type="submit" >Ok</button>
                        </div>
                    </div>
                    <!--                onclick="javascript:window.location.reload()"-->
                </div>
              </form>
                
            </div>
        </div>
                <br>   
                <br> 
                <br> 
                <div class="Luongnoidungthongbao">
                    <pre>' . $index_tb['noidung'] . '</pre> 
                </div>
            </div>
            <div class="file_dinhkem">
                ';
            foreach ($file_kem as $tmp) {
                echo '<a class="ten_file_kem"  target="_blank" href="../download.php?filepath='.$tmp['duongdan_file'].'&filename='.$tmp['ten_file'].'">'.$tmp['ten_file'].'</a>';

            }
            echo '      
                </div>
            <hr>  
              ';
            // truy van cmt
            $sql_cmt = 'select * from comment where id_thongbao="' . $index_tb['id_tb'] . '"';
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
            echo '                    
            <div>
                 <form method="post" action="./Stream.php">                                            
                    <div class="input-group mb-3">
                        <div class="input-group-append">
                             <img class="MainInterfacebuttonimgUser " src="'.$tt_user[0]['avatar'].'">   
                        </div>
                        <input required  class="form-control" type="text"  name="'.$index_tb['id_tb'].'" placeholder="add class comments"  >
                        <div class="input-group-append">
                            <button class="StreamSubmitComment" type="submit"  >Sent</button>
                        </div>                      
                    </div>
                </form>
            </div>
        </div>
        ';
            }
        else{
            $sql = 'select * from user_account where username="'.$index_tb['username_post'].'"';
            $user_post=executeResult($sql);
            echo '
                <form method="get" action="./assigment.php">
                    <input hidden name="id_tb" value="'.$index_tb["id_tb"].'">
                    <input hidden name="id_lophoc" value="'.$_SESSION['id_lophoc'].'">
                    <button class="Streambaitap" type="submit">
                        <img id="Streamimage" class="MainInterfacebuttonimgUser Streamimg" src="' . $tt_gv[0]['avatar'] . '">                    
                        <div class="name_time">' . $user_post[0]['firstname'] . ' ' . $user_post[0]['lastname'] . ' posted a new assignment: '.$index_tb["tieude"].' <br> 
                        '.$index_tb['time'].'                  
			            </div>
			            <div class="deadline">
			                <p>'.$index_tb["deadline"].'</p>
                        </div>				                      
                    </button>
                </form>
           
            ';
            }
        }
        ?>

    </div>



</body>
</html>