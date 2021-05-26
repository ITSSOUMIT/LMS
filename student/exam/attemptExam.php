<?php
    session_start();
    include("../../db/connection.php");
    if(!isset($_SESSION['session']) || !isset($_SESSION['userid']) || !isset($_SESSION['role'])){
        echo "<script>
        alert('Session Not Authorized');
        window.location.href= '../../index';
        </script>";
    }elseif(strcmp($_SESSION['role'], "student") != 0){
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

    if(!isset($_GET['intent']) || !isset($_GET['examid'])){
        echo "<script>
        alert('Missing Parameters');
        window.location.href= '../../index';
        </script>";
    }

    $userid = $_SESSION['userid'];
    $examid = $_GET['examid'];

    // profiler
    $extractprofile = "SELECT * FROM userbase WHERE userid='$userid'";
    $extractprofileexec = mysqli_query($conn, $extractprofile);
    $profile = mysqli_fetch_array($extractprofileexec);

    if(strcmp($_GET['intent'], "notappear")==0){
        $updatesubmission = "UPDATE submissions SET status=3 WHERE userid='$userid' AND examid='$examid'";
        $updatesubmissionexec = mysqli_query($conn, $updatesubmission);
        echo "<script>
        window.location.href= '../dashboard?action=notappear';
        </script>";
    }elseif(strcmp($_GET['intent'], "attempt")==0){
        $examactivecheck = "SELECT * FROM exam WHERE examid='$examid' AND status=2";
        $examactivecheckexec = mysqli_query($conn, $examactivecheck);
        if(mysqli_num_rows($examactivecheckexec)==1){
            $studentcheck = "SELECT * FROM submissions WHERE examid='$examid' AND userid='$userid' AND (status=0 OR status=2)";
            $studentcheckexec = mysqli_query($conn, $studentcheck);
            if(mysqli_num_rows($studentcheckexec)==1){
                $extractfield = mysqli_fetch_array($studentcheckexec);
                $submissionid = $extractfield['submissionid'];
                $url = "examEnvironment?examid=".$examid."&submissionid=".$submissionid;
                $exam = mysqli_fetch_array($examactivecheckexec);
                $timeleft = $exam['examduration']*60;
                $submissiontimeleft = $exam['submissionduration']*60;
                if($extractfield['timeleft']==NULL){
                    $update = "UPDATE submissions SET status=2, timeleft='$timeleft', submissiontimeleft='$submissiontimeleft' WHERE examid='$examid' AND userid='$userid'";
                    $updatequery = mysqli_query($conn, $update);    
                }
                echo "<script>
                window.open('".$url."');
                // window.location.href='".$url."';
                // window.open('".$url."', 'examEnvironment','scrollbars=1,width=1920,height=1080');
                window.location.href= '../dashboard';
                </script>";
            }else{
                //student has already appeared
                echo "<script>
                window.location.href= '../dashboard?action=alreadyappeared';
                </script>";
            }
        }else{
            //exam not active / not found
            echo "<script>
            window.location.href= '../dashboard?action=examerror';
            </script>";
        }
    }
?>