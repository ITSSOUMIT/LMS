<?php
    session_start();
    if(!isset($_SESSION['session']) || !isset($_SESSION['role']) || !isset($_SESSION['userid'])){
        header("Location: auth/login");
    }else{
        if(time()-$_SESSION['start'] > 7200){
            //timeout set to 7200 seconds = 2 hours
            session_unset();
            session_destroy();
            header("Location: auth/login");
        }else{
            if(strcmp($_SESSION['role'], "admin")==0){
                header("Location: admin/dashboard");
            }elseif(strcmp($_SESSION['role'], "student")==0){
                header("Location: student/dashboard");
            }
        }
    }
?>