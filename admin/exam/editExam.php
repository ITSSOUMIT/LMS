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

    if(!isset($_GET['examid'])){
        echo "<script>
        alert('No Exam Id mentioned');
        window.location.href= '../../index';
        </script>";
    }

    $userid = $_SESSION['userid'];
    $examid = $_GET['examid'];

    // profiler
    $extractprofile = "SELECT * FROM userbase WHERE userid='$userid'";
    $extractprofileexec = mysqli_query($conn, $extractprofile);
    $profile = mysqli_fetch_array($extractprofileexec);

    $extractexam = "SELECT * FROM exam WHERE examid='$examid'";
    $extractexamexec = mysqli_query($conn, $extractexam);
    $exam = mysqli_fetch_array($extractexamexec);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="../../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="../../plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../../plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="../../plugins/summernote/summernote-bs4.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <?php include("sidebar.php"); ?>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">


    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Edit Exam : <?php echo $exam['examname']; ?></h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Please fill in details</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form action="editExamLogic?examid=<?php echo $exam['examid']; ?>" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Exam Code</label>
                    <input type="text" class="form-control" placeholder="Enter exam name" name="examid" value="<?php echo $exam['examid']; ?>" readonly>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Exam Name</label>
                    <input type="text" class="form-control" placeholder="Enter exam name" name="examname" value="<?php echo $exam['examname']; ?>" required>
                  </div>
                  <div class="form-group">
                        <label>Batch Code :</label>
                        <select class="form-control" name="batchcode" required>
                          <option value="" selected>Select Batch Code</option>
                          <?php
                            $batchselector = "SELECT * FROM batch WHERE status=1";
                            $batchselectorexec = mysqli_query($conn, $batchselector);
                            while($batchcode = mysqli_fetch_array($batchselectorexec)){
                          ?>
                          <?php
                            if($exam['batchcode']==$batchcode['batchcode']){
                          ?>
                          <option value="<?php echo $batchcode['batchcode']; ?>" selected><?php echo $batchcode['batchcode']; ?></option>
                          <?php
                            }else{
                          ?>
                          <option value="<?php echo $batchcode['batchcode']; ?>"><?php echo $batchcode['batchcode']; ?></option>
                          <?php }} ?>
                        </select>
                  </div>
                  <!-- <div class="form-group">
                    <label for="exampleInputFile">Question Paper</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="exampleInputFile" name="questionpaper" required>
                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                      </div>
                    </div>
                  </div> -->
                  <div class="form-group">
                    <label for="exampleInputEmail1">Question Paper</label>
                    <input type="text" class="form-control" placeholder="Enter Question Paper Link" name="questionpaper" value="<?php echo $exam['questionpaper'].'edit?usp=sharing'; ?>" required>
                  </div>
                  <div class="row">
                    <div class="col-4">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Full Marks</label>
                        <input type="text" class="form-control" placeholder="Enter full marks for the exam" name="fullmarks" value="<?php echo $exam['fullmarks']; ?>" required>
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Exam Duration</label>
                        <input type="text" class="form-control" placeholder="Enter exam duration (in minutes)" name="examduration" value="<?php echo $exam['examduration']; ?>" required>
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Submission Duration</label>
                        <input type="text" class="form-control" placeholder="Enter submission duration (in minutes)" name="submissionduration" value="<?php echo $exam['submissionduration']; ?>" required>
                      </div>
                    </div>  
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-warning">Update Exam Details</button>
                </div>
              </form>
            </div>
            <!-- /.card -->
          </div>
          <!--/.col (left) -->
          <!--/.col (right) -->
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->



  <?php
      include("footer.php");
  ?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="../../plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="../../plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="../../plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="../../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="../../plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="../../plugins/moment/moment.min.js"></script>
<script src="../../plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="../../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="../../plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="../../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="../../dist/js/pages/dashboard.js"></script>

<script>
    document.querySelector('.custom-file-input').addEventListener('change',function(e){
        var fileName = document.getElementById("exampleInputFile").files[0].name;
        var nextSibling = e.target.nextElementSibling
        nextSibling.innerText = fileName
    })
</script>
</body>
</html>