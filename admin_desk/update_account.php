<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Thông tin sản phẩm</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php

require_once ('../db.php');
$error = '';
$msg = '';

$id = $_GET['id'];


$sql = 'select * from user_account where username= "'.$id.'"   ';

$user_account = executeResult($sql);







if (isset($_POST['username']) && isset($_POST['permission']) && isset($_POST['teacher']) && isset($_POST['sdt'])&& isset($_POST['activated'])
    && isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['ngaysinh']) && isset($_POST['email'])   )
{
    $username = $_POST['username'];
    $firstname =$_POST['firstname'];
    $lastname = $_POST['lastname'];
    $ngaysinh = $_POST['ngaysinh'];
    $email = $_POST['email'];
    $sdt = $_POST['sdt'];
    $activated = $_POST['activated'];


    if($_POST['permission']=="user"){
        $permission = "0";
    }else{
        $permission = "1";
    }
    if($_POST['teacher']=="noteacher"){
        $teacher = "0";
    }else{
        $teacher = "1";
    }
    if($_POST['activated']=="noactivated"){
        $activated = "0";
    }else{
        $activated = "1";
    }

    if (empty($username)) {
        $error = 'Please enter account name';
    }
    else if (empty($firstname)) {
        $error = 'Please enter first name';
    }
    else if (empty($lastname)) {
        $error = 'Please enter last name';
    }
    else if (empty($ngaysinh)) {
        $error = 'Please enter date of birth';
    }
    else if (empty($email)) {
        $error = 'Please enter email';
    }
    else if (empty($sdt)) {
        $error = 'Please enter phone number';
    }
    else if (empty($activated)) {
        $error = 'Please enter activated';
    }
    else {

        $result = update_account($username,$permission,$teacher,$firstname,$lastname,$ngaysinh,$email,$sdt,$activated);

        if($result['code'] ==0){
            $msg = $result['error'];

        } else {
            $error = 'An error occured. Please try again later';
        }
    }
}
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-lg-6 col-md-8 border rounded my-5 p-4  mx-3">
            <p class="mb-5"><a href="quanli_taikhoan.php">Quay lại</a></p>
            <h3 class="text-center text-secondary mt-2 mb-3 mb-3">Cập nhật tài khoản</h3>
            <form method="post" action=""  enctype="multipart/form-data">

                <div class="form-group">
                    <label for="username">User name</label>
                    <input   name="username" value="<?= $user_account[0]['username'] ?>" readonly  class="form-control" type="text" placeholder="User name" id="username">
                </div>
                <div class="form-group">
                    <label for="permission">Permission</label>
                    <select class="form-control" id="permission" name="permission">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="teacher">Teacher</label>
                    <select class="form-control" id="teacher" name="teacher">
                        <option value="noteacher">No</option>
                        <option value="yesteacher">Yes</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="firstname">First name</label>
                    <input required  name="firstname" value="<?= $user_account[0]['firstname'] ?>"  class="form-control" type="text" placeholder="First name" id="firstname">
                </div>
                <div class="form-group">
                    <label for="lastname">Last name</label>
                    <input required name="lastname" value="<?= $user_account[0]['lastname'] ?>"  class="form-control" type="text" placeholder="Last name" id="lastname">
                </div>
                <div class="form-group">
                    <label for="ngaysinh">Date of birth </label>
                    <input required id="ngaysinh" name="ngaysinh" value="<?= $user_account[0]['ngaysinh'] ?>"  type="date" class="form-control" placeholder="Date of birth">
                </div>
                <div class="form-group">
                    <label for="email">Email </label>
                    <input required id="email" value="<?= $user_account[0]['email'] ?> " type="text" name="email"  class="form-control" placeholder="Email">
                </div>
                <div class="form-group">
                    <label for="sdt">Phone number</label>
                    <input required id="sdt" value="<?= $user_account[0]['sdt'] ?>" name="sdt" type="text"  class="form-control" >
                </div>
                <div class="form-group">
                    <label for="activated">Activated</label>
                    <select class="form-control" id="activated" name="activated">
                        <option value="yesactivated">Yes</option>
                        <option value="noactivated">No</option>
                    </select>
                </div>

                <div class="form-group">
                    <?php
                    if (!empty($error)) {
                        echo "<div class='alert alert-danger'>$error</div>";
                    }
                    if (!empty($msg)) {
                        echo "<div class='alert alert-success'>$msg</div>";
                    }
                    ?>
                    <button type="submit" class="btn btn-primary px-5 mr-2">Cập nhật</button>
                </div>
            </form>

        </div>
    </div>

</div>

</body>
</html>

