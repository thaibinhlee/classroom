<?php
session_start();
require_once('db.php');
if (isset($_SESSION['user'])) {
    if ($_SESSION['permission'] == 1) {
        header('Location: ./admin_desk/index.php');
        exit();
    } else if ($_SESSION['permission'] == 0) {
        if($_SESSION['teacher']==1){
            header('Location: ./giangvien/index.php');
            exit();
        }else{
            header('Location: ./hocvien/index.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login Page</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/a81368914c.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="style.css">
    <script src="main.js"></script>


</head>
<body id="login_body" >
<?php
$error = '';

$user = '';
$pass = '';

if (isset($_POST['user']) && isset($_POST['pass'])) {
    $user = $_POST['user'];
    $pass = $_POST['pass'];

    if (empty($user)) {
        $error = 'Please enter your username';
    }
    else if (empty($pass)) {
        $error = 'Please enter your password';
    }
    else if (strlen($pass) < 6) {
        $error = 'Password must have at least 6 characters';
    }
    else
    {
        $result = login($user,$pass);
        if($result['code']==0){
            $data=$result['data'];
            echo '<script language="javascript">alert("'.$data.'")</script>';
            $_SESSION['user'] = $user;
            $_SESSION['permission'] = $data['permission'];
            $_SESSION['name'] = $data['firstname'].' '.$data['lastname'];
            $_SESSION['teacher']=$data['teacher'];
            $_SESSION['avatar']=$data['avatar'];
            if($_SESSION['permission']==1){
                header('Location: ./admin_desk/index.php');
                exit();
            }else if($_SESSION['permission']==0)
            {
                if($_SESSION['teacher']==1){
                    header('Location: ./giangvien/index.php');
                    exit();
                }else{
                    header('Location: ./hocvien/index.php');
                    exit();
                }
            }

            exit();
        }else{
            $error = $result['error'];
        }
    }
}
?>

<img class="wave" src="image/wave.png">
<div class="container-login">
    <div class="img-login">
        <img src="image/teaching.svg">
    </div>
    <div class="login-content">
        <form action="login.php" method="post" class="form_login">
            <img src="image/avatars/avatar.svg">
            <h3 class="title">Welcome</h3>
            <h2 class="title">to Classroom</h2>
            <div class="input-div one">
                <div class="i_login">
                    <i class="fas fa-user"></i>
                </div>
                <div class="div">
                    <h5>Username</h5>
                    <input type="text" class="input_login" name="user">
                </div>
            </div>
            <div class="input-div pass">
                <div class="i_login">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="div">
                    <h5>Password</h5>
                    <input type="password" class="input_login" name="pass">
                </div>
            </div>
            <a href="./user_account/forgot.php" class="forgot_pwd">Forgot Password?</a>
            <?php
            if (!empty($error)) {
                echo '<div style="color: #fc6c56; text-align: left; margin: 1rem 0">'.$error.'</div>
                        ';
            }
            ?>
            <input type="submit" class="btn_login" value="Login">
            <p class="create_account">Don't have an account? <a href="./user_account/register.php" class="sign_up">Sign Up Now</a></p>
        </form>
    </div>
</div>
</body>

</html>