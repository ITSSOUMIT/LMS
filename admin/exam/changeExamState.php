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

    if(!isset($_GET['examid'])){
        echo "<script>
        alert('Exam ID not specified');
        window.location.href= '../dashboard';
        </script>";
    }

    if(!isset($_GET['from']) || !isset($_GET['to'])){
        echo "<script>
        alert('Exam change states not specified');
        window.location.href= '../dashboard';
        </script>";
    }

    $examid = $_GET['examid'];
    $from = $_GET['from'];
    $to = $_GET['to'];

    $checkquery = "SELECT * FROM exam WHERE examid='$examid' AND status='$from'";
    $checkqueryexec = mysqli_query($conn, $checkquery);
    $exam = mysqli_fetch_array($checkqueryexec);

    if(mysqli_num_rows($checkqueryexec) == 1){
        $updatequery = "UPDATE exam SET status='$to' WHERE examid='$examid'";
        $updatequeryexec = mysqli_query($conn, $updatequery);

        //make default entries for each student in submission table
        if($from==1 && $to==2){
            $batchcode = $exam['batchcode'];
            $examid = $exam['examid'];
            $examname = $exam['examname'];
            $fullmarks = $exam['fullmarks'];
            $stuselect = "SELECT * FROM userbase WHERE batch='$batchcode' AND role='student'";
            $stuselectexec = mysqli_query($conn, $stuselect);
            while($stu = mysqli_fetch_array($stuselectexec)){
                $stuid = $stu['userid'];
                $submissionid = md5(date('dmYHisu').$stuid.$examid);
                $insertquery = "INSERT INTO submissions (submissionid, userid, examname, examid, fullmarks) VALUES ('$submissionid', '$stuid', '$examname', '$examid', '$fullmarks')";
                $insertqueryexec = mysqli_query($conn, $insertquery);
            }
        }elseif($from==2 && $to==3){
            $batchcode = $exam['batchcode'];
            $examid = $exam['examid'];
            $examname = $exam['examname'];
            $fullmarks = $exam['fullmarks'];
            $stuselect = "SELECT * FROM userbase WHERE batch='$batchcode' AND role='student'";
            $stuselectexec = mysqli_query($conn, $stuselect);
            while($stu = mysqli_fetch_array($stuselectexec)){
                $stuid = $stu['userid'];
                $entrycheck = "SELECT * FROM submissions WHERE userid='$stuid' AND examid='$examid'";
                $entrycheckquery = mysqli_query($conn, $entrycheck);
                $entryrow = mysqli_fetch_array($entrycheckquery);
                if(($entryrow['status'] == 0) || ($entryrow['status'] == 3)){
                    $updatestatusquery = "UPDATE submissions SET status=3, resulttext='Didnot Appear', result='0', resultDivision='0' WHERE userid='$stuid' AND examid='$examid'";
                    $updatestatusqueryexec = mysqli_query($conn, $updatestatusquery);
                }elseif(($entryrow['status'] == 2) || ($entryrow['status'] == 4)){
                    $updatestatusquery = "UPDATE submissions SET status=5, resulttext='Didnot Appear', result='0', resultDivision='0' WHERE userid='$stuid' AND examid='$examid'";
                    $updatestatusqueryexec = mysqli_query($conn, $updatestatusquery);
                }
            }
        }elseif($from==3 && $to==0){
            $batchcode = $exam['batchcode'];
            $examid = $exam['examid'];
            $examname = $exam['examname'];
            $fullmarks = $exam['fullmarks'];
            $stuselect = "SELECT * FROM userbase WHERE batch='$batchcode' AND role='student'";
            $stuselectexec = mysqli_query($conn, $stuselect);
            while($stu = mysqli_fetch_array($stuselectexec)){
                $stuid = $stu['userid'];
                $entrycheck = "SELECT * FROM submissions WHERE userid='$stuid' AND examid='$examid'";
                $entrycheckquery = mysqli_query($conn, $entrycheck);
                $entryroww = mysqli_fetch_array($entrycheckquery);
                if($entryroww['result']==NULL){
                    $updatequery = "UPDATE exam SET status='$from' WHERE examid='$examid'";
                    $updatequeryexec = mysqli_query($conn, $updatequery);
                    echo "<script>
                    window.location.href= 'unevaluatedExam?error=unevaluatedAnswerSheets';
                    </script>";
                }
                $updatestatusquery = "UPDATE submissions SET resultviewallowed=1 WHERE userid='$stuid' AND examid='$examid'";
                $updatestatusqueryexec = mysqli_query($conn, $updatestatusquery);
            }
        }
        echo "<script>
        window.location.href= '../dashboard';
        </script>";
    }else{
        echo "<script>
        alert('Exam Not Found');
        window.location.href= '../dashboard';
        </script>";
    }

    $userid = $_SESSION['userid'];

    // profiler
    $extractprofile = "SELECT * FROM userbase WHERE userid='$userid'";
    $extractprofileexec = mysqli_query($conn, $extractprofile);
    $profile = mysqli_fetch_array($extractprofileexec);
?>