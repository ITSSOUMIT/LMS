<?php
    session_start();
    session_unset();
    session_destroy();
    include("../db/connection.php");

    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $query = "SELECT * FROM userbase WHERE email='$email' AND password='$password' AND status!=0";
    $queryresult = mysqli_query($conn, $query);

    if(mysqli_num_rows($queryresult) == 0){
        echo "<script>
        alert('Incorrect Credentials / User Doesnot Exist');
        window.location.href= 'login.php';
        </script>";
    }else{
        session_start();
        $_SESSION['session'] = md5(date('dmYHisu'));
        $_SESSION['start'] = time();
        $row = mysqli_fetch_array($queryresult);
        $_SESSION['userid'] = $row['userid'];
        $_SESSION['role'] = $row['role'];
        echo $_SESSION['role'];
        if(strcmp($_SESSION['role'], "admin")==0){
            header("Location: ../admin/dashboard");
        }elseif(strcmp($_SESSION['role'], "student")==0){
            header("Location: ../student/dashboard");
        }
    }
?>