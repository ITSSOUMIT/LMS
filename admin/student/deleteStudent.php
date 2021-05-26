<?php
    session_start();
    include("../../db/connection.php");
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
    $student = $_GET['userid'];

    // profiler
    $extractprofile = "SELECT * FROM userbase WHERE userid='$student'";
    $extractprofileexec = mysqli_query($conn, $extractprofile);
    $profile = mysqli_fetch_array($extractprofileexec);

    $batch = $profile['batch'];

    $markinactive = "UPDATE userbase SET status=0 WHERE userid='$student'";
    $markinactiveexec = mysqli_query($conn, $markinactive);
    $updatebatch = "UPDATE batch SET students = students-1 WHERE batchcode='$batch'";
    $updatebatchexec = mysqli_query($conn, $updatebatch);
    
    echo "<script>
    window.location.href= 'viewStudent?batch=ALL';
    </script>";
?>