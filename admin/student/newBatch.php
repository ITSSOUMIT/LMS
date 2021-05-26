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

    $userid = $_SESSION['userid'];

    if(isset($_POST['intent'])){
        if(strcmp($_POST['intent'], "submit")==0){
            $batchcode = $_POST['batchcode'];
            $class = $_POST['class'];
            $school = $_POST['school'];
            $newbatch = "INSERT INTO batch (batchcode, class, school) VALUES ('$batchcode', '$class', '$school')";
            $newbatchexec = mysqli_query($conn, $newbatch);
            echo "<script>
            alert('New Batch Successfully Generated. Batch Code is : ".$batchcode."');
            window.location.href= '../dashboard';
            </script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add New Batch</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="../index" class="h1"><b>Arun</b> Kumar Das</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Add New Batch</p>

      <form action="" method="post">
        <input type="hidden" class="form-control" name="intent" value="submit" required readonly>
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Batch Code" name="batchcode" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-users"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Class" name="class" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-users"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="School" name="school" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-school"></span>
            </div>
          </div>
        </div>
        <div class="row mt-2 mb-3">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Add New Batch</button>
          </div>
          <!-- /.col -->
        </div>
        <div class="row mt-2 mb-3">
          <div class="col-12">
            <button onclick="window.location='../dashboard'" class="btn btn-outline-primary btn-block">Cancel</button>
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
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
</body>
</html>