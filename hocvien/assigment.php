<?php

require_once("../db.php");
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}
$id_tb = $_GET["id_tb"];
$id_lophoc = $_GET["id_lophoc"];
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
    <title>Assigment</title>
    <link rel="stylesheet" href="../style.css">
    <script src="../main.js"></script>

</head>
<?php
$sql = 'select * from thongbao where id_tb="'.$id_tb.'"';
$thongbao = executeResult($sql);
$sql = 'select * from file_kem_thongbao where id_thongbao = "' . $id_tb . '"';
$file_kem = executeResult($sql);

// thong tin lop hoc
$sql = 'SELECT * FROM lophoc WHERE ma_lophoc="'.$id_lophoc.'"';
$lophoc = executeResult($sql);

//lam phan add cmt
if(isset($_POST["submit"])){
    $id=  "idcmtassigment".time();
    $noidung = $_POST["noidung"];
    $id_thongbao = $id_tb;
    $time = date("Y-m-d");
    $addcmt = add_comment($id,$id_thongbao,$_SESSION['user'],$noidung);
}
// submit file

if(isset($_POST['submit_file'])){
    if(isset($_FILES['file'])){
        // Count total files
        $conn = onpen_database();
        // Looping all files
        $sql = 'DELETE FROM assigment WHERE username="'.$_SESSION['user'].'"';
        $resultset = mysqli_query($conn,$sql);

        $filename = $_FILES['file']['name'];
        // Upload file
        move_uploaded_file($_FILES['file']['tmp_name'],'../upload/'.$filename);
        $href = './upload/'.$filename;
        $result = add_fileassigment($id_tb,$_SESSION['user'],$filename,$href);
        if($result['code']==1){
            echo '<script language="javascript">alert("Nộp file thất bại")</script>';
        }elseif ($result['code']==2){
            echo '<script language="javascript">alert("Nộp thất bại, đã trễ deadline")</script>';
        }
        else{
            echo '<script language="javascript">alert("Nộp file thành công")</script>';
        }
    }
}
?>
<body>
<div id="mySidenav" class="sidenav">
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
                <a href="Stream.php">
                    <div class="peoplemonhochieth">
                        <?= $lophoc[0]["ten_lophoc"] ?>
                    </div>

                </a>
            </td>

            <td class="peoplebuttonavatar">
                <button type="button" class="btn MainInterfacebuttonUser">
                    <img class="MainInterfacebuttonimgUser" src="..\image\photo.png">
                </button>
            </td>
        </tr>
    </table>
    <hr class="thehran">
</div>

<p class="maininterfacekhangtrang"></p>

<div class="container" onclick="closeNav()">
    <div class="assigmentthe1">
        <div class="assigmenticonlist">
            <i class="fas fa-clipboard-list"></i>
        </div>
    </div>

    <div class="assigmentogiua">
        <div>
            <div>
                <div>
                    <div class="assigmentnoidungbt"><?= $thongbao[0]["tieude"] ?> </div>
                </div>
                <div class="assigmentggigi">
                    <?= $thongbao[0]["time"] ?>
                </div>
                <div>
                    <div class="assigmentdeadline">
                        <?= $thongbao[0]["deadline"] ?>
                    </div>
                </div>

            </div>
            <hr class="thehrxanh">
            <?php echo'<pre>Nội dung: '.$thongbao[0]["noidung"].'</pre>' ?>
<!--            in ra file kem-->
            <?php
            foreach ($file_kem as $tmp) {
                echo 'File Đính kèm: <a class="ten_file_kem"  target="_blank" href="../download.php?filepath='.$tmp['duongdan_file'].'&filename='.$tmp['ten_file'].'">'.$tmp['ten_file'].'</a>';
            }
            ?>
            <hr>

        </div>
        <div class="noidungcmt">

            <?php
            $sql_cmt = 'select * from comment where id_thongbao="'.$id_tb.'"';
            $all_cmt = executeResult($sql_cmt);
            echo'<p class="Streamnumbercomment"><b>'.count($all_cmt).' class comment</b></p>';
            foreach ($all_cmt as $cmt_index) {
                $sql = 'select * from user_account where username="'.$cmt_index['username_cmt'].'"';
                $user_cmt = executeResult($sql);
                echo '
                <div class="div_cmt" >
                    <div class="cmt" >
                        <div class="div_flex">
                            <img class="MainInterfacebuttonimgUser Streamimg" src="'.$user_cmt[0]['avatar'].'">
                            <div class="padding-left-10">
                                <div class="mg-top-10">' . $cmt_index['username_cmt'] .'</div>
                                <div style="">' . $cmt_index['time'] . '</div>
                            </div>
                        </div>
                    </div>
                    <div class="padding-left-30"><pre>' . $cmt_index['noidung'] . '</pre></div>
                </div>
                ';
            }
            ?>
        </div>

        <!--            comment-->
        <form method="post" action="">
            <div class="assigmenttc">
                <div>
                    <img class="EntercodeImg assigmentanhcmt" src="..\image\photo.png">
                    <div class="assigmentonhapcmt">
                        <textarea name="noidung" class="assigmentoinput" type="text" placeholder="Add class comment ....."></textarea>
                        <button type="submit" name="submit" class="assigmentbtgui">
                            <i class="far fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <form method="post" action="" enctype="multipart/form-data">
        <div class="assigmentthecuoi">
            <div>
                <span class="assigmentyw">
                        Your Work
                    </span>
                <?php

                if(isset($result["code"]) && $result["code"]==0 ) {
                    echo'
                            <span class="assigmentassigment">
                                 Đã nộp
                            </span>
                        ';
                }
                ?>
            </div>
            <div >
                <input class="input_file" type="file" name="file" >
                <br>
                <button type="submit" name="submit_file" class="assigmentmarkasdone" >Mark as done</button>
            </div>
        </div>
    </form>



</div>

</div>


</body>

</html>
