<?php
    function extractExamInfo($examid, $requirement){
        include("../../db/connection.php");
        $examquery = "SELECT * FROM exam WHERE examid='$examid'";
        $examqueryexec = mysqli_query($conn, $examquery);
        $exam = mysqli_fetch_array($examqueryexec);
        if(strcmp($requirement, "name")==0){
            return $exam['examname'];
        }elseif(strcmp($requirement, "examduration")==0){
            return $exam['examduration'];
        }elseif(strcmp($requirement, "submissionduration")==0){
            return $exam['submissionduration'];
        }
    }
    function extractStudentInfo($userid, $requirement){
        include("../../db/connection.php");
        $studentquery = "SELECT * FROM userbase WHERE userid='$userid' AND role='student'";
        $studentqueryexec = mysqli_query($conn, $studentquery);
        $student = mysqli_fetch_array($studentqueryexec);
        if(strcmp($requirement, "name")==0){
            echo $student['name'];
        }
    }
?>