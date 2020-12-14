<?php
require_once('./db.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Join Class</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link
      rel="stylesheet"
      href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
    />
    <link
      rel="stylesheet"
      href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
      integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
      crossorigin="anonymous"
    />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  </head>
  <body>
  <?php
    $id = $_GET['id'];
    $username= $_GET['username'];
    $ma_lophoc = $_GET['ma_lophoc'];
    $accept= $_GET['accept'];
    $result = add_acceptjoin_new($id,$ma_lophoc,$username,$accept);
    if($result['code']=="1"){
        echo($result['error']);
    }else{
        if($accept=='yes'){
            $add =  add_student_bycode($ma_lophoc,$username);
            echo($add['error']);
        }
        else{
            echo("Đã từ chối thành công");
        }
    }
  ?>
    </div>
  </body>
</html>
