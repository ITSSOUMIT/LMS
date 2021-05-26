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

    if(!isset($_POST['examname']) || !isset($_POST['examid']) || !isset($_POST['batchcode']) || !isset($_POST['examduration']) || !isset($_POST['submissionduration'])){
        echo "<script>
        alert('Required Parameters Not Found');
        window.location.href= 'newExam';
        </script>";
    }

    // $target_dir = "../../exampaper/";
    // $target_file = $target_dir.basename($_FILES["questionpaper"]["name"]);
    // $uploadOk = 1;
    // $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // if($fileType != "pdf") {
    //     $uploadOk = 0;
    //     echo "Not PDF";
    // }

    // if($uploadOk == 0) {
    //     echo "<script>
    //     alert('File Not Uploaded. Reason -  File is not an image');
    //     window.location.href= 'newExam';
    //     </script>";
    // // if everything is ok, try to upload file 
    // }else{
    //     $newfilename = $_POST['examid'];
    //     $target_file = $target_dir.$newfilename.".".$fileType;
    //     if(move_uploaded_file($_FILES["questionpaper"]["tmp_name"], $target_file)){
    //         $questionpaper = substr($target_file, 6);
    //         $examid = $_POST['examid'];
    //         $batchcode = $_POST['batchcode'];
    //         $examname = $_POST['examname'];
    //         $examduration = $_POST['examduration'];
    //         $submissionduration = $_POST['submissionduration'];
    //         $fullmarks = $_POST['fullmarks'];

    //         $newexam = "INSERT INTO exam (examid, batchcode, examname, questionpaper, fullmarks, examduration, submissionduration) VALUES ('$examid', '$batchcode', '$examname', '$questionpaper', '$fullmarks', '$examduration', '$submissionduration')";
    //         $newexamexec = mysqli_query($conn, $newexam);
    //         echo "<script>
    //         alert('New Exam Created Successfully');
    //         window.location.href= '../dashboard';
    //         </script>";
    //     }else{
    //         echo "<script>
    //         alert('File couldnot be uploaded');
    //         window.location.href= 'newExam';
    //         </script>";
    //     }
    // }

    $examid = $_POST['examid'];
    $batchcode = $_POST['batchcode'];
    $examname = $_POST['examname'];
    // $questionpaper = $_POST['questionpaper'];
    $questionpaper = substr($_POST['questionpaper'], 0, -16);
    $examduration = $_POST['examduration'];
    $submissionduration = $_POST['submissionduration'];
    $fullmarks = $_POST['fullmarks'];

    // $newexam = "INSERT INTO exam (examid, batchcode, examname, questionpaper, fullmarks, examduration, submissionduration) VALUES ('$examid', '$batchcode', '$examname', '$questionpaper', '$fullmarks', '$examduration', '$submissionduration')";
    $updateexam = "UPDATE exam SET batchcode='$batchcode', examname='$examname', questionpaper='$questionpaper', fullmarks='$fullmarks', examduration='$examduration', submissionduration='$submissionduration' WHERE examid='$examid'";
    $updateexamexec = mysqli_query($conn, $updateexam);
    echo "<script>
    alert('Exam Details Updated Successfully');
    window.location.href= '../dashboard';
    </script>";
?>