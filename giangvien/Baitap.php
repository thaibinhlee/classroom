<?php
require_once("../db.php");

session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bai tap</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
    <link rel="stylesheet" href="../style.css">
    <script src="../main.js"></script>
</head>
<body>

<img class="img_exam" src="../image/exam.svg">
<?php


?>
<form method="post" action="./Stream.php" enctype="multipart/form-data">
    <div class="Baitapthanhotr">
        <a class="Baitapbuttonthoat" href="Stream.php">
            <i class="fas fa-times"></i>
        </a>

        <div class="baitapkbt">
            Bài tập
        </div>
        <div class="Baitapdaluugiaobai">
            <span> <i>Đã lưu  </i> </span>

            <button class="Moinguoigiaobai" type="submit" name="submit_assigment" >
                Giao Bài
            </button>
        </div>
    </div>
    <p></p>
    <hr>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-9 col-sm-12 Baitapbentrai">

                <div>
                    <div class="Baitapiconlist">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div>
                        <textarea class="Baitapiptt" type="text" required name="tieude" placeholder="Tiêu đề"></textarea>
                    </div>
                </div>
                <div class="khoangbd">
                    <div class="Baitapiconlist">
                        <i class="fas fa-stream"></i>
                    </div>
                    <div>
                        <textarea class="Baitapiphd" type="text" required name="noidung" placeholder="Hướng dẫn (không bắt buộc)"></textarea>
                    </div>


                    <div class="file-field big">
                        <a class="btn-floating btn-lg pink lighten-1 mt-0 float-left">
                            <i class="fas fa-paperclip" aria-hidden="true"></i>
                            <input type="file" name="file" >
                        </a>

                    </div>


                </div>
            </div>

            <div  class="col-xl-3 col-sm-12 Baitapbenphai">
                <div>

                    <div class="Baitapdeadline">
                        <p>Đến hạn</p>
                        <input class="Baitapdeadline" type="datetime-local" name="deadline" placeholder="Không có ngày đến hạn">
                    </div>

                </div>
            </div>
        </div>
        <div>
</form>

        

</body>
</html>