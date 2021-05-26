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

    if(!isset($_GET['batch'])){
        echo "<script>
        alert('Missing Parameters');
        window.location.href= '../../index';
        </script>";
    }

    $userid = $_SESSION['userid'];
    $batch = $_GET['batch'];

    // profiler
    $extractprofile = "UPDATE userbase SET batch=NULL WHERE batch='$batch'";
    $extractprofileexec = mysqli_query($conn, $extractprofile);

    $updatebatch = "UPDATE batch SET students = 0, status = 0 WHERE batchcode='$batch'";
    $updatebatchexec = mysqli_query($conn, $updatebatch);
    
    echo "<script>
    window.location.href= 'viewBatch';
    </script>";
?>