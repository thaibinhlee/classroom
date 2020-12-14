

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
    <title>Enter Code Interface</title>
    <link rel="stylesheet" href="../style.css">
    <script src="../main.js"></script>


</head>

<body>
<?php

require_once ('../db.php');
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}

$sql = 'select * from user_account where username="'.$_SESSION['user'].'"';
$user = executeResult($sql);

if(isset($_POST['classcode'])){
    $code = $_POST['classcode'];

//    truy van mail cua gv
    $sql = 'select * from people,user_account where people.ma_lophoc="'.$code.'" and people.user_name=user_account.username and people.teacher ="1"  ';
    $tt_gv = executeResult($sql);
    if(!empty($tt_gv)){
        // check xem da co trong lop chua
        $sql = 'select * from people where ma_lophoc="'.$code.'" and user_name = "'.$_SESSION['user'].'" ';
        $check_tt = executeResult($sql);

        if(!empty($check_tt)){
            echo'<script>alert("Bạn đã ở trong lớp học này rồi");</script>';
        }else{
            $sendmail = sendmailacceptjoin_new($tt_gv[0]['email'],$user,$code);
            echo'<script>alert("'.$sendmail.'");</script>';
        }

    }else{
//        code sai
        echo'<script>alert("Code sai");</script>';
    }
}
?>
    <P></P>

<!--    <button class="EntercodeJoin" type="submit" form="code_form">Join</button>-->
    <button class="EntercodeClose" onclick="location.href='./index.php'">
        <i class="fas fa-times"></i>
    </button>
    <span class="EntercodeJoinclass"> Join class</span>
    <div style="float: right; margin-right: 135px;">
        <a href="javascript:$('#code_form').submit();" class="btn_join btn-white">JOIN</a>
    </div>


    <hr style="height:0.5px; border-width:0; color:gray; background-color:gray">

    <div class="container EntercodeDiv1">
        <p style="margin-top:10px;margin-left:10px;color:#6E6E6E"> You're currently signed in as</p>
        <!-- Infor user-->
        <div>
            <table style="width:100%">
                <tr>
                    <td style="width:10%">
                        <img class="EntercodeImg" src="<?= $user[0]['avatar']?>">
                    </td>
                    <td style="width:60%">
                        <span><?= $user[0]['firstname'].' '.$user[0]['lastname']?></span> <br>
                        <span style="color:#6E6E6E"><?= $user[0]['email']?></span>
                    </td>
                    <td style="width:30%">
                        <button class="EntercodeSwitchaccount" onclick="location.href='../logout.php'">Switch account</button>
                    </td>
                </tr>
            </table>
        </div>

    </div>
    <br>
    <div class="container EntercodeDiv2">
        <div style="font-size:20px;margin-top:10px;margin-left:10px">Class code</div>
        <div style="margin-top:10px;margin-left:10px">Ask your teacher for the class code, then enter it here.</div>
        <br>
        <form method="post" id="code_form" action="EnterCode.php">
            <input name="classcode" class="EntercodeClasscode" required type="text" placeholder="Class code" maxlength="13">
            <div class="err"></div>
        </form>

    </div>
    <br>
    <div class="container EntercodeDiv3">
        <strong style="margin-left:20px">To sign in with a class code</strong>
        <p></p>
        <ul>
            <li>Use an authorized account</li>
            <li>Use a class code with letters or numbers, and no spaces or symbols</li>
        </ul>

    </div>

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



</body>

</html>