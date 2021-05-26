<?php
    session_start();
    include("../../db/connection.php");
    include("myFunctions.php");
    if(!isset($_SESSION['session']) || !isset($_SESSION['userid']) || !isset($_SESSION['role'])){
        echo "<script>
        alert('Session Not Authorized');
        window.location.href= '../../index';
        </script>";
    }elseif(strcmp($_SESSION['role'], "admin") != 0){
        echo "<script>
        alert('You are not authorized to access this page');
        window.location.href= '../../index';
        </script>";
    }

    if(time()-$_SESSION['start'] > 7200){
        echo "<script>
        window.location.href= '../../index';
        </script>";
    }

    if(!isset($_GET['userid'])){
        echo "<script>
        alert('Missing Parameters');
        window.location.href= '../../index';
        </script>";
    }

    $userid = $_SESSION['userid'];
    $studentid = $_GET['userid'];

    // profiler
    $extractprofile = "SELECT * FROM userbase WHERE userid='$userid'";
    $extractprofileexec = mysqli_query($conn, $extractprofile);
    $profile = mysqli_fetch_array($extractprofileexec);

    $updatequery = "UPDATE passwordresetreq SET status=2, accepted_on=now() WHERE userid='$studentid'";
    $updatequeryexec = mysqli_query($conn, $updatequery);

    echo "<script>
        window.location.href= 'passwordResetReq?action=dropped';
        </script>";
?>