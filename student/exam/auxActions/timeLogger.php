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

    $updatequery = "UPDATE submissions SET timeleft=timeleft-15 WHERE submissionid='$submissionid'";
    $updatequeryexec = mysqli_query($conn, $updatequery);
?>