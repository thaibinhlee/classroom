<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';


define('HOST','127.0.0.1');
define('USER','root');
define('PASS','');
define('DB','classroom');

function onpen_database(){
    $conn = new mysqli(HOST,USER,PASS,DB);
    if($conn -> connect_error)
    {
        die('Connect error: '.$conn -> connect_error);

    }

    return $conn;
}
function login($user,$pass)
{
    $sql = "select * from user_account where username= ?";
    $conn = onpen_database();

    $stm = $conn -> prepare($sql);
    $stm -> bind_param('s',$user);
    if(!$stm -> execute())
    {
        return array('code' => 1,'error' => 'Can not excute command');
    }

    $result = $stm -> get_result();
    if($result -> num_rows ==0){
        return array('code' => 1,'error' => 'User does not exists');
    }

    $data = $result -> fetch_assoc();
    $hashed_password = $data['password'];
    if(!password_verify($pass,$hashed_password))
    {
        return array('code' => 2,'error' => 'Invalid password');
    }else if($data['activated'] ==0){
        return array('code' => 3, 'error' => 'This account is not activated');
    }
    else return array('code' => 0, 'error' => '','data' => $data);
}
function is_email_exists($email){
    $sql = 'select username from user_account where email = ?';
    $conn = onpen_database();

    $stm = $conn -> prepare($sql);
    $stm -> bind_param('s',$email);
    if(!$stm -> execute())
    {
        die('Query error: '.$stm -> error);
    }
    $result = $stm -> get_result();
    if($result -> num_rows > 0){
        return true;
    } else{
        return false;
    }

}

function register($username,$first_name,$last_name,$ngaysinh,$email,$sdt,$password){
    if(is_email_exists($email))
    {
        return array('code' => 1, 'error' => 'Email exists');
    }

    $hash = password_hash($password,PASSWORD_DEFAULT);
    $rand = random_int(0,1000);
    $token = md5($username.'+'.$rand);
    $sql = 'insert into user_account(username,firstname,lastname,ngaysinh,email,sdt,password,activate_token) values(?,?,?,?,?,?,?,?)';
    $conn = onpen_database();
    $stm = $conn -> prepare($sql);
    $stm -> bind_param('ssssssss', $username,$first_name,$last_name,$ngaysinh,$email,$sdt,$hash,$token);
    if(!$stm -> execute()){
        return array('code' => 2, 'error' => 'Can not execute command');
    }
    sendActivationEmail($email,$token);
    return array('code' => 0, 'error' => 'Create account successful');
}
function sendActivationEmail($email,$token){
    $mail = new PHPMailer(true);
    $error = 'adsf';
    try {
        //Server settings
//        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail -> CharSet = 'UTF-8';
        $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'thptso1tuyphuot@gmail.com';                     // SMTP username
        $mail->Password   = 'h t f d s u b h j l o i u d q x';                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('thptso1tuyphuot@gmail.com', 'Admin của classroom');
        $mail->addAddress($email, 'Người nhận');     // Add a recipient
//        $mail->addAddress('ellen@example.com');               // Name is optional
//        $mail->addReplyTo('info@example.com', 'Information');
//        $mail->addCC('cc@example.com');
//        $mail->addBCC('bcc@example.com');
//
//        // Attachments
//        $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
//
//        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Xác minh tài khoản của bạn';
        $mail->Body    = "Click <a href='http://localhost/user_account/activate.php?email=$email&token=$token'> vào đây </a> để xác minh tài khoản của bạn";
        $mail->send();
        return true;
    } catch (Exception $error ) {

        return false;
    }
}
function send_reset_Email($email,$token){
    $mail = new PHPMailer(true);
    $error = 'adsf';
    try {

        //Server settings
//        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP

        $mail->CharSet = 'UTF-8';
        $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'thptso1tuyphuot@gmail.com';                     // SMTP username
        $mail->Password   = 'h t f d s u b h j l o i u d q x';                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('thptso1tuyphuot@gmail.com', 'Admin của classroom');
        $mail->addAddress($email, 'Người nhận');     // Add a recipient
//        $mail->addAddress('ellen@example.com');               // Name is optional
//        $mail->addReplyTo('info@example.com', 'Information');
//        $mail->addCC('cc@example.com');
//        $mail->addBCC('bcc@example.com');
//
//        // Attachments
//        $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
//
//        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Khôi phục mật khẩu của bạn';
        $mail->Body    = "Click <a href='http://localhost/user_account/reset_password.php?email=$email&token=$token'> vào đây </a> để khôi phục mật khẩu của bạn";
//        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        return true;
    } catch (Exception $error ) {

        return false;
    }
}

function activeAccount($email,$token){
    $sql = 'select username from user_account where email = ? and activate_token = ? and activated = 0';
    $conn = onpen_database();
    $stm = $conn -> prepare($sql);
    $stm -> bind_param('ss',$email,$token);
    if(!$stm -> execute()){
        return array('code' => 1,'error' => 'Can not execute command');
    }
    $result = $stm -> get_result();
    if($result -> num_rows ==0){
        return array('code' => 2,'error' => 'Email address or token not found');
    }

    $sql = "update user_account set activated = 1, activate_token='' where email = ?";
    $stm = $conn -> prepare($sql);
    $stm -> bind_param('s',$email);

    if(!$stm -> execute()){
        return array('code' => 1,'error' => 'Can not execute command');
    }
    return array('code' => 0,'message' => 'Account activated');
}

function reset_password($email){
    if(!is_email_exists($email)){
        return array('code' => 1, 'error' => 'Email does not exist') ;
    }
    $token = md5($email.'+'. random_int(1000,2000));
    $sql = 'update reset_token set token = ? where email =?';

    $conn = onpen_database();
    $stm = $conn -> prepare($sql);
    $stm -> bind_param('ss',$token,$email);

    if(!$stm -> execute()){
        return array('code' => 2, 'error' => 'Can not execute command') ;
    }
    if($stm -> affected_rows ==0){
        $exp = time() + 3600*24;
        $sql = 'insert into reset_token values(?,?,?)';
        $stm = $conn -> prepare($sql);
        $stm -> bind_param('ssi',$email,$token,$exp);

        if(!$stm -> execute()){
            return array('code' => 1, 'error' => 'Can not execute command') ;
        }

    }
    $success = send_reset_Email($email,$token);
    return array('code' => 0, 'success' => $success);
}
function update_password($email,$pass){
    $hash = password_hash($pass,PASSWORD_DEFAULT);
    $sql = 'update user_account set password = ? where email = ?';
    $conn = onpen_database();
    $stm = $conn -> prepare($sql);
    $stm -> bind_param('ss',$hash,$email);
    if(!$stm -> execute()){
        return array('code' => 1, 'error' => 'Can not execute command');
    }
    return array('code' => 0, 'error' => 'update password successful');
}
function update_account($username,$permission,$teacher,$firstname,$lastname,$ngaysinh,$email,$sdt,$activated){

    $sql = ' UPDATE user_account
            SET  permission = ?, teacher = ?, firstname = ? , lastname = ?, ngaysinh = ?, email = ?, sdt = ?, activated = ?
            WHERE username=? ';
    $conn = onpen_database();
    $stm = $conn -> prepare($sql);
    $stm -> bind_param('iisssssis', $permission,$teacher,$firstname,$lastname,$ngaysinh,$email,$sdt,$activated,$username);
    if(!$stm -> execute()){
        return array('code' => 1, 'error' => 'Can not execute command');
    }

    return array('code' => 0, 'error' => 'Update successful');
}

//truy xuat ra man hinh
function executeResult($sql) {
    //create connection toi database
    $conn = onpen_database();
    //query
    $resultset = mysqli_query($conn,$sql);
    $list      = [];
    while ($row = mysqli_fetch_array($resultset,1)) {
        $list[] = $row;
    }

    //dong connection
    mysqli_close($conn);

    return $list;
}




function permission($user)
{
    $sql = "select * from user_account where username= ?";
    $conn = onpen_database();

    $stm = $conn -> prepare($sql);
    $stm -> bind_param('s',$user);
    if(!$stm -> execute())
    {
        return array('code' => 1,'error' => 'Can not excute command');
    }

    $result = $stm -> get_result();

    $data = $result -> fetch_assoc();

    return $data[0]['permission'];
}

function add_thongbao($id_tb,$malophoc,$username_post,$noidung){


    $sql = 'insert into thongbao(id_tb,malophoc,username_post,noidung) values(?,?,?,?)';
    $conn = onpen_database();
    $stm = $conn -> prepare($sql);
    $stm -> bind_param('ssss', $id_tb,$malophoc,$username_post,$noidung);


    if(!$stm -> execute()) {
        return array('code' => 1, 'error' => 'Can not execute command');
    }

    return array('code' => 0, 'error' => 'Add  successful');
}
function add_thongbao_assigment($tieude,$id_tb,$malophoc,$username_post,$noidung,$deadline){
    $sql = 'insert into thongbao(tieude,id_tb,malophoc,username_post,noidung,deadline) values(?,?,?,?,?,?)';
    $conn = onpen_database();
    $stm = $conn -> prepare($sql);
    $stm -> bind_param('ssssss', $tieude,$id_tb,$malophoc,$username_post,$noidung,$deadline);
    if(!$stm -> execute()) {
        return array('code' => 1, 'error' => 'Can not execute command');
    }
    return array('code' => 0, 'error' => 'Add  successful');
}

function add_filekem($id_thongbao,$tenfile,$duongdan_file){
//    check file zize <= 100kb
//    if(filesize($tenfile)>100)
    $random = time();
    $sql_dinhkem = 'insert into file_kem_thongbao(id,ten_file,id_thongbao,duongdan_file) values(?,?,?,?)';
    $id_kem = "idkem".$random;
    $conn = onpen_database();
    $stm_dinhkem = $conn -> prepare($sql_dinhkem);
    $stm_dinhkem -> bind_param('ssss', $id_kem,$tenfile,$id_thongbao,$duongdan_file);
    if(!$stm_dinhkem -> execute()) {
        return array('code' => 1, 'error' => 'Can not execute command');
    }
    return array('code' => 0, 'error' => 'Add  successful');
}
function add_comment($id,$id_thongbao,$username_cmt,$noidung){

    $sql = 'insert into comment(id,id_thongbao,username_cmt,noidung) values(?,?,?,?)';
    $conn = onpen_database();
    $stm = $conn -> prepare($sql);
    $stm -> bind_param('ssss', $id,$id_thongbao,$username_cmt,$noidung);

    if(!$stm -> execute()) {
        return array('code' => 1, 'error' => 'Can not execute command');
    }

    return array('code' => 0, 'error' => 'Add  successful');
}
function delete_cmt($id){

    $sql = 'insert into comment(id,id_thongbao,username_cmt,noidung) values(?,?,?,?)';
    $conn = onpen_database();
    $stm = $conn -> prepare($sql);
    $stm -> bind_param('ssss', $id,$id_thongbao,$username_cmt,$noidung);

    if(!$stm -> execute()) {
        return array('code' => 1, 'error' => 'Can not execute command');
    }

    return array('code' => 0, 'error' => 'Add  successful');
}
function sent_email_post_tb($result,$tt_tb){

    $malophoc = $tt_tb[0]["malophoc"];
    $sql = 'select * from lophoc where ma_lophoc="'.$malophoc.'"';
    $thongtinlop = executeResult($sql);
    $link = "http://localhost/hocvien/Stream.php";
    $mail = new PHPMailer(true);

            $mail->isSMTP();                                            // Send using SMTP
            $mail -> CharSet = 'UTF-8';
            $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'thptso1tuyphuot@gmail.com';                     // SMTP username
            $mail->Password   = 'h t f d s u b h j l o i u d q x';                               // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            //Recipients
            $mail->setFrom('thptso1tuyphuot@gmail.com', 'Admin của classroom');

            foreach ($result as $tmp){
                $mail->addAddress($tmp["email"], 'Người nhận');     // Add a recipient
            }
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'New announcement';
            $mail->Body    = '

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gmail</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src=\'https://kit.fontawesome.com/a076d05399.js\'></script>

</head>
<body>
    <div class="gmailtt" style="        padding: 20px;
        text-align: center;
        background-color: #0080FF;
        color: white;
        height: 120px;
        margin-bottom: 50px;">
        <div>
            <img class="gmailimage" style="   width: 60px;
        height: 45px;
        background-color: #58ACFA;" src="https://www.clipartmax.com/png/middle/231-2313362_go-technology-or-go-old-school-google-classroom-logo-vector.png" alt="">
        </div>
        <div>
            <h2><b>Google</b> Classroom</h2>
        </div>
    </div>
<div class="container gmail" style="width: 50%;">
    <h3><b>
        Hi,
    </b></h3>
    <div>
       Your teacher posted a new announcenment in 
        <b >'.$thongtinlop[0]["ten_lophoc"].'</b>
    </div>
    <div class="" style="margin-top: 20px;border: 1px solid black;padding: 20px;border-radius: 10px;background-color: #FAFAFA;">
        <div class="" style="font-size: 30px;">
            <i style=" float: left;" class="fas fa-comment"></i>
        </div>
        <div class="" style="margin-left: 50px">
            <pre class="" style=" font-size: 25px;">'.$tt_tb[0]["noidung"].'</pre>
            <br>
            <form method="post" action="http://localhost/hocvien/Stream.php">  
                        <input type="hidden" name="id_lophoc" value="'.$malophoc.'">
                        <button type="submit">Open</button>
            </form>
        </div>
    </div>
    <br>

</div>
</body>
</html>
   
            ';
            $mail->send();

}
function sent_email_cmt($result,$tt_cmt,$id_tb){
    $sql_tb = 'select * from thongbao where id_tb="'.$id_tb.'"';
    $result_tb = executeResult($sql_tb);
    $sql_lophoc ='select * from lophoc where ma_lophoc="'.$result_tb[0]["malophoc"].'"';
    $result_lophoc = executeResult($sql_lophoc);

    $mail = new PHPMailer(true);
    $mail->isSMTP();                                            // Send using SMTP
    $mail -> CharSet = 'UTF-8';
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'thptso1tuyphuot@gmail.com';                     // SMTP username
    $mail->Password   = 'h t f d s u b h j l o i u d q x';                               // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
    //Recipients
    $mail->setFrom('thptso1tuyphuot@gmail.com', 'Admin của classroom');

    foreach ($result as $tmp){
        $mail->addAddress($tmp["email"], 'Người nhận');     // Add a recipient
    }
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'New comment in your class';
    $mail->Body    = '
    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gmail</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src=\'https://kit.fontawesome.com/a076d05399.js\'></script>

</head>
<body>
    <div class="gmailtt" style="        padding: 20px;
        text-align: center;
        background-color: #0080FF;
        color: white;
        height: 120px;
        margin-bottom: 50px;">
        <div>
            <img class="gmailimage" style="   width: 60px;
        height: 45px;
        background-color: #58ACFA;" src="https://www.clipartmax.com/png/middle/231-2313362_go-technology-or-go-old-school-google-classroom-logo-vector.png" alt="">
        </div>
        <div>
            <h2><b>Google</b> Classroom</h2>
        </div>
    </div>
<div class="container gmail" style="width: 50%;">
    <h3><b>
        Hi,
    </b></h3>
    <div>
       '.$tt_cmt[0]["username_cmt"].' posted a new announcenment in 
        <b >'.$result_lophoc[0]["ten_lophoc"].'</b>
    </div>
    <div class="" style="margin-top: 20px;border: 1px solid black;padding: 20px;border-radius: 10px;background-color: #FAFAFA;">
        <div class="" style="font-size: 30px;">
            <i style=" float: left;" class="fas fa-comment"></i>
        </div>
        <div class="" style="margin-left: 50px">
            <pre class="" style=" font-size: 25px;">'.$tt_cmt[0]["noidung"].'</pre>
            <br>
            <form method="post" action="http://localhost/hocvien/Stream.php">  
                        <input type="hidden" name="id_lophoc" value="'.$result_tb[0]["malophoc"].'">
                        <button type="submit">Open</button>
            </form>
        </div>
    </div>
    <br>

</div>
</body>
</html>

    ';
    $mail->send();

}
function reArrayFiles(&$file_post)
{
    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);
    for ($i = 0; $i < $file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }
    return $file_ary;
}
function add_fileassigment($id_tb,$username,$ten_file,$link){
//    kiem tra tg vs dead
    $time = date("Y-m-d h:i:sa");
    $sql = 'select * from thongbao where id_tb="'.$id_tb.'"';
    $result = executeResult($sql);
    $deadline = $result[0]["deadline"];
    if($time > $deadline){
        return array('code' => 2, 'error' => 'Bạn đã nộp trễ');
    }
    $random = time();
    $sql = 'insert into assigment(id,id_tb,username,ten_file,link) values(?,?,?,?,?)';
    $id = "idassigment".$random;
    $conn = onpen_database();
    $stm = $conn -> prepare($sql);
    $stm -> bind_param('sssss', $id,$id_tb,$username,$ten_file,$link);
    if(!$stm -> execute()) {
        return array('code' => 1, 'error' => 'Can not execute command');
    }
    return array('code' => 0, 'error' => 'Add  successful');
}
function add_student_bycode($ma_lophoc,$user_name){
    $conn = onpen_database();
    $sql = 'insert into people(ma_lophoc,user_name) values(?,?)';
    $stm = $conn -> prepare($sql);
    $stm -> bind_param('ss', $ma_lophoc,$user_name);
    if(!$stm -> execute()) {
        return array('code' => 1, 'error' => 'Join class failed, Code wrong or joined');
    }
    return array('code' => 0, 'error' => 'Join class successful');
}

function add_acceptjoin_new($id,$ma_lophoc,$username,$accept){
    $conn = onpen_database();
    $sql = 'insert into acceptjoin_new(id,ma_lophoc,username,accept) values(?,?,?,?)';
    $stm = $conn -> prepare($sql);
    $stm -> bind_param('ssss', $id,$ma_lophoc,$username,$accept);
    if(!$stm -> execute()) {
        return array('code' => 1, 'error' => 'Yêu cầu xử lý không còn hiệu lực');
    }
    return array('code' => 0, 'error' => '');
}
//gui mail gv xac nhan tg
function sendmailacceptjoin_new($email,$user,$ma_lophoc){
    $id = uniqid();
    $name = $user[0]['firstname'].' '.$user[0]['lastname'];
    $usermail = $user[0]['email'];
    $username = $user[0]['username'];
    $mail = new PHPMailer(true);
    $error = 'adsf';
    try {
        //Server settings
//        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail -> CharSet = 'UTF-8';
        $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'thptso1tuyphuot@gmail.com';                     // SMTP username
        $mail->Password   = 'h t f d s u b h j l o i u d q x';                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        //Recipients
        $mail->setFrom('thptso1tuyphuot@gmail.com', 'Admin của classroom');
        $mail->addAddress($email, 'Người nhận');     // Add a recipient
//        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Xác nhận yêu cầu tham gia lớp học';
        $mail->Body    = '

<body style="background-color: #E6E6E6">
    <div class="gmailtt">
        <div>
            <h2><b>Google</b> Classroom</h2>
        </div>
    </div>
<div class="container gmail">
    <h3><b>
        Hi,
    </b></h3>
    <div>
        <p>Thông tin người dùng có yêu cầu tham gia lớp học</p> 
        <p>Họ và Tên: '.$name.'</p>
        <p>Email: '.$usermail.'</p>
    </div>
    <div class="Gmailkhuin">
        <div class="Gmailicon">
            <i class="fas fa-comment"></i>
        </div>
        <div class="gmailcontent">
             <a href="http://localhost/acceptjoin_newclass.php?id='.$id.'&username='.$username.'&ma_lophoc='.$ma_lophoc.'&accept=yes">Click</a> để chấp nhận yêu cầu
            
        </div>
         <div class="gmailcontent">
             <a href="http://localhost/acceptjoin_newclass.php?id='.$id.'&username='.$username.'&ma_lophoc='.$ma_lophoc.'&accept=no">Click</a> để từ chối yêu cầu           
        </div>
         <br> 
    </div>
    <br>
    <hr>
</div>
</body>

';

        $mail->send();
        return "Đã gửi mail, vui lòng đợi giảng viên xác nhận";
    } catch (Exception $error ) {

        return "Gửi mail thất bại, vui lòng thử lại sau";
    }
}
//gui mail xac nhan sinh vien tg
function sendmailacceptjoin_ofstudent($email,$user,$ma_lophoc){
    $id = uniqid();
    $name = $user[0]['firstname'].' '.$user[0]['lastname'];
    $usermail = $user[0]['email'];
    $username = $user[0]['username'];
    $mail = new PHPMailer(true);

    // truy van thong tin lop hoc
    $sql = 'select * from lophoc where ma_lophoc = "'.$ma_lophoc.'"';
    $lophoc = executeResult($sql);
    // truy van thong tin cua sinh vien
    $sql = 'select * from user_account where email = "'.$email.'"';
    $student = executeResult($sql);

    $error = 'adsf';
    try {
        //Server settings
//        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail -> CharSet = 'UTF-8';
        $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'thptso1tuyphuot@gmail.com';                     // SMTP username
        $mail->Password   = 'h t f d s u b h j l o i u d q x';                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        //Recipients
        $mail->setFrom('thptso1tuyphuot@gmail.com', 'Admin của classroom');
        $mail->addAddress($email, 'Người nhận');     // Add a recipient
//        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Xác nhận yêu cầu tham gia lớp học';
        $mail->Body    = '

<body style="background-color: #E6E6E6">
    <div class="gmailtt">
        <div>
            <h2><b>Google</b> Classroom</h2>
        </div>
    </div>
<div class="container gmail">
    <h3><b>
        Hi,
    </b></h3>
    <div>
        <p>Thông tin giảng viên mời bạn tham gia lớp học</p> 
        <p>Họ và Tên: '.$name.'</p>
        <p>Email: '.$usermail.'</p>
        <p>Thông tin khóa học</p>
        <p>Tên lớp học: '.$lophoc[0]['ten_lophoc'].'</p>
        <p>Tên môn học: '.$lophoc[0]['ten_monhoc'].'</p>
        <p>Tên Phòng học: '.$lophoc[0]['ten_phonghoc'].'</p>
    </div>
    <div class="Gmailkhuin">
        <div class="Gmailicon">
            <i class="fas fa-comment"></i>
        </div>
        <div class="gmailcontent">
             <a href="http://localhost/acceptjoin_newclass.php?id='.$id.'&username='.$student[0]['username'].'&ma_lophoc='.$ma_lophoc.'&accept=yes">Click</a> để chấp nhận yêu cầu
            
        </div>
         <div class="gmailcontent">
             <a href="http://localhost/acceptjoin_newclass.php?id='.$id.'&username='.$student[0]['username'].'&ma_lophoc='.$ma_lophoc.'&accept=no">Click</a> để từ chối yêu cầu           
        </div>
         <br> 
    </div>
    <br>
    <hr>
</div>
</body>

';

        $mail->send();
        return "Đã gửi mail, vui lòng đợi học sinh xác nhận";
    } catch (Exception $error ) {

        return "Gửi mail thất bại, vui lòng thử lại sau";
    }
}

function leave_class($sql) {

    $conn = onpen_database();
    // sql to delete a record

    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $conn->close();
}

?>