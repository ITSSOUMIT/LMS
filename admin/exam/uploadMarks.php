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
    if(!isset($_GET['examid'])){
        echo "<script>
        alert('Not all Parameters found');
        window.location.href= '../../index';
        </script>";
    }
    if(!isset($_GET['submissionid'])){
        echo "<script>
        alert('Not all Parameters found');
        window.location.href= '../../index';
        </script>";
    }

    $userid = $_SESSION['userid'];
    $examid = $_GET['examid'];
    $submissionid = $_GET['submissionid'];

    // profiler
    $extractprofile = "SELECT * FROM userbase WHERE userid='$userid'";
    $extractprofileexec = mysqli_query($conn, $extractprofile);
    $profile = mysqli_fetch_array($extractprofileexec);

    // submissionExtract
    $extractsubmission = "SELECT * FROM submissions WHERE examid='$examid' AND submissionid='$submissionid'";
    $extractsubmissionexec = mysqli_query($conn, $extractsubmission);
    $submission = mysqli_fetch_array($extractsubmissionexec);

    $resultDivision = $_POST['resultDivision'];
    $result = $_POST['result'];
    $resulttext = $_POST['resulttext'];

    $marks = "UPDATE submissions SET result='$result', resulttext='$resulttext', resultDivision='$resultDivision' WHERE submissionid='$submissionid' AND examid='$examid'";
    $marksexec = mysqli_query($conn, $marks);
    
    echo "<script>
        window.location.href= 'viewDetails?paperChecked=1&examid=".$examid."';
        </script>";
?>

