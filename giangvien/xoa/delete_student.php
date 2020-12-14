<?php
$conn = new mysqli("127.0.0.1", "root", "", "classroom");

if (isset($_POST["deletedata"])) {
    $username = $_POST['delete_id'];

    $sql = "DELETE FROM people WHERE user_name= ?";
    $stm = $conn->prepare($sql);
    $stm->bind_param('s', $username);

    if ($stm->execute()) {
        echo '<script> alert("Student Removed"); </script>';
        header('Location: ../people.php');
    } else {
        echo '<script> alert("Student Not Removed"); </script>';
    }
}
?>
