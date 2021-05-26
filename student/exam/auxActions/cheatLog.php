<?php
    session_start();
    include("../../../db/connection.php");
    if(!isset($_SESSION['session']) || !isset($_SESSION['userid']) || !isset($_SESSION['role'])){
        echo "<script>
        alert('Session Not Authorized');
        window.location.href= '../../../index';
        </script>";
    }elseif(strcmp($_SESSION['role'], "student") != 0){
        echo "<script>
        alert('You are not authorized to access this page');
        window.location.href= '../../../index';
        </script>";
    }

    if(!isset($_GET['examid']) || !isset($_GET['submissionid'])){
        echo "<script>
        alert('Missing');
        window.location.href= '../../../index';
        </script>";
    }

    $examid = $_GET['examid'];
    $submissionid = $_GET['submissionid'];
    $userid = $_SESSION['userid'];

    $cheatid = md5(date('dmYHisu'));
    $insertquery = "INSERT INTO cheatlog(cheatid, userid, examid, submissionid) VALUES ('$cheatid', '$userid', '$examid', '$submissionid')";
    $insertqueryexec = mysqli_query($conn, $insertquery);
?>