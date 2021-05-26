<?php
    session_start();
    include("../db/connection.php");
    if(!isset($_SESSION['session']) || !isset($_SESSION['userid']) || !isset($_SESSION['role'])){
        echo "<script>
        alert('Session Not Authorized');
        window.location.href= '../index';
        </script>";
    }elseif(strcmp($_SESSION['role'], "student") != 0){
        echo "<script>
        alert('You are not authorized to access this page');
        window.location.href= '../index';
        </script>";
    }

    $userid = $_SESSION['userid'];

    // profiler
    $extractprofile = "SELECT * FROM userbase WHERE userid='$userid'";
    $extractprofileexec = mysqli_query($conn, $extractprofile);
    $profile = mysqli_fetch_array($extractprofileexec);

    if(mysqli_num_rows($extractprofileexec) == 0){
        echo "<script>
        alert('You are not authorized to access this page');
        window.location.href= '../index';
        </script>";
    }else{
        if(isset($_POST['intent'])){
            if(strcmp($_POST['intent'], "submit")==0){
                $password = md5($_POST['password']);
                $repeatpassword = md5($_POST['repeatpassword']);
                if($password == $repeatpassword){
                    $updateprofile = "UPDATE userbase SET password='$password', status=1 WHERE userid='$userid'";
                    $updateprofileexec = mysqli_query($conn, $updateprofile);
                    session_unset();
                    session_destroy();
                    echo "<script>
                    alert('Password Reset Successfull');
                    window.location.href= '../index';
                    </script>";
                }else{
                    echo "<script>
                    alert('Passwords donot match');
                    window.location.href= 'changePassword';
                    </script>";
                }
            }
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Join New Batch</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="../index" class="h1"><b>Arun</b> Kumar Das</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Set New Password</p>

      <form action="" method="post">
        <input type="hidden" class="form-control" name="intent" value="submit" required readonly>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Enter Password" name="password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-key"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Enter Password Again" name="repeatpassword" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-key"></span>
            </div>
          </div>
        </div>
        <div class="row mt-2 mb-3">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
          </div>
          <!-- /.col -->
        </div>
        <div class="row mt-2 mb-3">
          <div class="col-12">
            <button onclick="window.location='login'" class="btn btn-outline-primary btn-block">Cancel</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
</body>
</html>