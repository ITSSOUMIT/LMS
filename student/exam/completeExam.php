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

    if(!isset($_GET['examid']) || !isset($_GET['submissionid'])){
        echo "<script>
        alert('Missing');
        window.location.href= '../../index';
        </script>";
    }
    
    $examid = $_GET['examid'];
    $submissionid = $_GET['submissionid'];

    $extractexam = "SELECT * FROM exam WHERE examid='$examid' AND status=2";
    $extractexamexec = mysqli_query($conn, $extractexam);
    if(mysqli_num_rows($extractexamexec)==1){
        $exam = mysqli_fetch_array($extractexamexec);
    }

    if(!isset($_FILES)){
        $updatequery = "UPDATE submissions SET status=5 WHERE submissionid='$submissionid' AND examid='$examid'";
        $updatequeryexec = mysqli_query($conn, $updatequery);
    }else{
        $target_dir = "../../answerscripts/";
        $target_file = $target_dir.basename($_FILES["answerscript"]["name"]);
        $uploadOk = 1;
        $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        if($fileType != "pdf") {
            $uploadOk = 0;
        }

        if($uploadOk == 0) {
            echo "<script>
            window.location.href= '../dashboard';
            </script>";
        // if everything is ok, try to upload file 
        }else{
            $newfilename = $submissionid;
            $target_file = $target_dir.$newfilename.".".$fileType;
            if(move_uploaded_file($_FILES["answerscript"]["tmp_name"], $target_file)){
                $answerscript = substr($target_file, 6);

                $updatequery = "UPDATE submissions SET status=1, answerscript='$answerscript', submitted_on=now() WHERE submissionid='$submissionid' AND examid='$examid'";
                $updatequeryexec = mysqli_query($conn, $updatequery);
                echo "<script>
                alert('Answer Script Uploaded Successfully');
                window.location.href= '../dashboard';
                </script>";
            }else{
                echo "<script>
                alert('File couldnot be uploaded');
                window.location.href= '../dashboard';
                </script>";
            }
        }
    }
?>