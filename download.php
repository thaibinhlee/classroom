<?php
require_once 'db.php';
$conn = new mysqli("127.0.0.1", "root", "", "classroom");
if (isset($_GET['filepath'])) {
    $filepath = $_GET['filepath'];
	$filename =  $_GET['filename'];
    // fetch file to download from database
	print_r($_GET);

    if (file_exists($filepath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($filepath));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize('uploads/' . $filename));
        readfile('uploads/' . $filename);


        exit;
    }

}
?>