<?php
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