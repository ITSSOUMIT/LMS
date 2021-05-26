<?php
    session_start();
    session_unset();
    session_destroy();
    include("../db/connection.php");

    if(isset($_POST['intent'])){
        if(strcmp($_POST['intent'], "submit")==0){
            $email = $_POST['email'];
            // profiler
            $extractprofile = "SELECT * FROM userbase WHERE email='$email'";
            $extractprofileexec = mysqli_query($conn, $extractprofile);
            $profile = mysqli_fetch_array($extractprofileexec);
            if(mysqli_num_rows($extractprofileexec)!=0){
                if($profile['status'] == 0){
                    echo "<script>
                    alert('No account found with the email provided');
                    window.location.href= 'register';
                    </script>";
                }elseif($profile['status'] == 1){
                    $student = $profile['userid'];
                    $initialcheck = "SELECT * FROM passwordresetreq WHERE userid='$student' AND status=0";
                    $initialcheckexec = mysqli_query($conn, $initialcheck);
                    if(mysqli_num_rows($initialcheckexec)!=0){
                      echo "<script>
                      alert('You have already requested for a Password Reset. Please wait for 24 hours, for the password to be reset');
                      window.location.href= 'login';
                      </script>";
                    }else{
                      $request = "INSERT INTO passwordresetreq (userid) VALUES ('$student')";
                      $requestexec = mysqli_query($conn, $request);
                      echo "<script>
                      alert('Password Reset Request successfully generated. Password will be reset within 24 hours');
                      window.location.href= 'login';
                      </script>";
                    }
                }elseif($profile['status'] == 2){
                    echo "<script>
                    alert('Password was reset successfully. Please re-login with the new password that was provided to you');
                    window.location.href= 'login';
                    </script>";
                }
            }else{
                echo "<script>
                alert('No account found with the email provided');
                window.location.href= 'register';
                </script>";
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
      <p class="login-box-msg">Request a Password Reset</p>

      <form action="" method="post">
        <input type="hidden" class="form-control" name="intent" value="submit" required readonly>
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Enter your Email" name="email" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-users"></span>
            </div>
          </div>
        </div>
        <div class="row mt-2 mb-3">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Request Password Reset</button>
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