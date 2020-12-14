<?php
    require_once('../db.php');
    session_start();
    if(isset($_POST['username_delete'])){
        $username_delete = $_POST['username_delete'];
        $sql = 'delete from user_account where username = "'.$username_delete.'"';
        $result = executeResult($sql);
        if(empty($result)){
            echo '
            <script>alert("Đã xóa thành công")</script>
            ';
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
    <title>Quản lí tài khoản</title>
    <link rel="stylesheet" href="../style.css">
    <script src="../main.js"></script>
    <style>
        #accounts {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #accounts td, #accounts th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        #accounts tr:nth-child(even){background-color: #f2f2f2;}

        #accounts tr:hover {background-color: #ddd;}

        #accounts th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>

<body >
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
        echo '
        <form method="post" action="Stream.php">
             <input hidden name="id_lophoc" value="'.$std["ma_lophoc"].'">
             <button class="maininterfacemonhoclist">
                 <table>
                    <tr>
                        <td>
                            <img  class="maininterfaceavtmh" src="' . $std['ten_anh'] . '">
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
<div class="bang fixed-top">
    <table class="peoplebangnavbar">
        <tr>
            <td class="peoplebuttonmenu">
                <button class="maininterfacemonhoclist">
                    <span class="" onclick="openNav()"><i class="MainInterfacebuttonmenu fas fa-bars"></i></span>
                </button>
            </td>
            <td class="peopletenmon">
                <a href="index.php">
                    <div class="peoplemonhochieth">
                        Welcome Admin
                    </div>
                    <div class="peoplegvhieth">
                        <?= $tt_user[0]["firstname"].' '.$tt_user[0]["lastname"] ?>
                    </div>
                </a>
            </td>
            <td class="peoplechochih">
                <div>
                    <a class="peoplechustream" href="./index.php">Home</a>
                    <a class="peoplechupeople" href="./quanli_taikhoan.php">Permission</a>
                </div>
            </td>
            <td class="peoplebuttonavatar">
                <button type="button" class="btn MainInterfacebuttonUser">
                    <img class="MainInterfacebuttonimgUser" src="<?= $tt_user[0]["avatar"] ?>">
                </button>
            </td>
        </tr>
        <tr class="people100pt">
            <th colspan="4" class="peopleallcot">
                <div class="peoplethesau">
                    <a class="peoplechustream" href="./index.php">Home</a>
                    <a class="peoplechupeople" href="./quanli_taikhoan.php">Permission</a>
                </div>
            </th>
        </tr>

    </table>
</div>
<span  class="MainInterfacebuttonmenu" onclick="openNav()"></span>




<span style="float:right;float:top">


    </span>
<hr>
</span>


<!--End Navbar-->
<div class="container-fluid" onclick="closeNav()">

    <table id="accounts">
        <tr>
            <th>User name</th>
            <th>Permission</th>
            <th>Teacher</th>
            <th>First name</th>
            <th>Last Name</th>
            <th>Date of birth</th>
            <th>Email</th>
            <th>Phone number</th>
            <th>Activated</th>
            <th></th>
        </tr>
        <!--         in ra tat ca san pham-->
        <?php
        $sql = 'select * from user_account ';
        $sql_test = 'select count(username) AS soluong from user_account';  // so luong
        $user_account = executeResult($sql);
        $user_account_test = executeResult($sql_test);   // so luong
        foreach ($user_account as $std) {
            if($std['permission']=="0"){
                $std['permission']= "User";
            }else{
                $std['permission']="Admin";
            }
            if($std['teacher']=="0"){
                $std['teacher']= "No";
            }else{
                $std['teacher']="Yes";
            }
            if($std['activated']=="0"){
                $std['activated']= "No";
            }else{
                $std['activated']="Yes";
            }
            echo ' <tr>
            <td>'.$std['username'].'</td>
            <td>'.$std['permission'].'</td>
			<td>'.$std['teacher'].'</td>
			<td>'.$std['firstname'].'</td>
			<td>'.$std['lastname'].'</td>
			<td>'.$std['ngaysinh'].'</td>
			<td>'.$std['email'].'</td>
			<td>'.$std['sdt'].'</td>
			<td>'.$std['activated'].'</td>
			<td><button class="btn btn-warning" onclick=\'window.open("update_account.php?id='.$std['username'].'","_self")\' > Edit </button>
			 <button  data-toggle="modal" data-target="#'.$std['username'].'" class="btn btn-danger delete " > Delete </button>	
			 </td> 
		</tr>
		        <!--// modal delete account-->
        <div class="modal fade" id="'.$std['username'].'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h4 class="modal-title w-100 font-weight-bold">Xóa tài khoản</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-form-label">Tên tài khoản:</label>
                                <input name="username_delete" readonly  required class="form-control" type="text" value="'.$std['username'].'">
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="insertdata"/>Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
		';
        }
        ?>
        <!--       in tat ca san pham -->
        <hr>
        <tr class="control" style="text-align: left; font-weight: bold; font-size: 17px">
            <td colspan="10"><b>Number account: </b><?= $user_account_test[0]['soluong'] ?></td>
        </tr>
    </table>

</div>

</body>
</html>