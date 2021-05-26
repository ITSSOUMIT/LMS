<?php
    include("../db/connection.php");
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $repeatpassword = md5($_POST['repeatpassword']);
    $phone = $_POST['phone'];
    $batch = $_POST['batch'];

    //check password match
    if($password != $repeatpassword){
        echo "<script>
        alert('Passwords donot match');
        window.location.href= 'signup.php';
        </script>";
    }else{
        //check batch
        $checkquery = "SELECT * FROM batch WHERE batchcode='$batch' AND status=1";
        $checkqueryresult = mysqli_query($conn, $checkquery);
        if(mysqli_num_rows($checkqueryresult) == 0){
            echo "<script>
            alert('Batch Code not found. Please enter the correct batchcode or contact me.');
            window.location.href= 'register';
            </script>";
        }else{
            //check if user is already registered or now
            $checkquery = "SELECT * FROM userbase WHERE email='$email' AND status!=0";
            $checkqueryresult = mysqli_query($conn, $checkquery);
            if(mysqli_num_rows($checkqueryresult) != 0){
                echo "<script>
                alert('An account with the same email was found !');
                window.location.href= 'signup.php';
                </script>";
            }else{
                //register user to the system
                $userid = md5(date('dmYHisu'));
                $insertquery = "INSERT INTO userbase (userid, name, email, password, phone, batch) VALUES ('$userid', '$name', '$email', '$password', '$phone', '$batch')";
                $insertqueryresult = mysqli_query($conn, $insertquery);

                //increase student count in the batch
                $increasequery = "UPDATE batch SET students = students + 1 WHERE batchcode='$batch'";
                $increasequeryresult = mysqli_query($conn, $increasequery);

                echo "<script>
                alert('You are successfully registered into the system');
                window.location.href= 'login';
                </script>";
            }
        }
    }
?>