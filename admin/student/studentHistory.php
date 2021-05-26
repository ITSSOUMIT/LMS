<?php
    session_start();
    include("../../db/connection.php");
    include("myFunctions.php");
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
    if(!isset($_GET['studentid'])){
        echo "<script>
        alert('Missing Parameters');
        window.location.href= '../dashboard';
        </script>";
    }

    $userid = $_GET['studentid'];

    // profiler
    $extractprofile = "SELECT * FROM userbase WHERE userid='$userid'";
    $extractprofileexec = mysqli_query($conn, $extractprofile);
    $profile = mysqli_fetch_array($extractprofileexec);
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
            <h1 class="m-0">Students :</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="invoice p-3 mb-3">
              <!-- info row -->

              <div class="row invoice-info">
                <div class="col-sm-6 invoice-col">
                    <b>Student ID : </b><?php echo $profile['userid']; ?><br>
                </div>
                <div class="col-sm-6 invoice-col">
                    <b>Student Name : </b><?php echo $profile['name']; ?><br>
                </div>
              </div>
              <div class="row invoice-info">
                <div class="col-sm-6 invoice-col">
                    <b>Email : </b><?php echo $profile['email']; ?><br>
                </div>
                <div class="col-sm-6 invoice-col">
                    <b>Phone Number : </b><?php echo $profile['phone']; ?><br>
                </div>
              </div>
              <div class="row invoice-info">
                <div class="col-sm-6 invoice-col">
                    <b>Batch : </b><?php echo $profile['batch']; ?><br>
                </div>
                <div class="col-sm-6 invoice-col">
                    <b>Account Status : </b>
                    <?php
                        if($profile['status']!=0){
                    ?>
                    <font color="green">Active</font>
                    <?php }else{ ?>
                    <font color="red">In-active</font>
                    <?php } ?>
                    <br>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>Sl. No.</th>
                      <th>Exam Name</th>
                      <th>Time Consumed</th>
                      <th>Time taken to submit</th>
                      <th>Changed Tabs</th>
                      <th>Marks Obtained</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $submissionquery = "SELECT * FROM submissions WHERE userid='$userid'";
                      $submissionqueryexec = mysqli_query($conn, $submissionquery);
                      if(mysqli_num_rows($submissionqueryexec)==0){
                    ?>
                      <tr>
                        <td>No active records found.</td>
                      </tr>
                    <?php
                      }else{
                      $c = 1;
                      while($submission = mysqli_fetch_array($submissionqueryexec)){
                    ?>
                      <tr>
                        <td><?php echo $c; ?></td>
                        <td><?php echo extractExamInfo($submission['examid'], "name"); ?></td>
                        <td><?php echo extractExamInfo($submission['examid'], "examduration")-round($submission['timeleft']/60, 0, PHP_ROUND_HALF_UP); ?> minutes</td>
                        <td><?php echo extractExamInfo($submission['examid'], "submissionduration")-round($submission['submissiontimeleft']/60, 0, PHP_ROUND_HALF_UP); ?> minutes</td>
                        <?php
                            $submissionid = $submission['submissionid'];
                            $cheatcount = "SELECT * FROM cheatlog WHERE submissionid='$submissionid'";
                            $cheatcountexec = mysqli_query($conn, $cheatcount);
                        ?>
                        <td><?php echo mysqli_num_rows($cheatcountexec); ?> times</td>
                        <?php
                            if($submission['result']==NULL){
                        ?>
                            <td><font color="red">Unevaluated</font></td>
                            <td>
                                <button type="button" class="btn btn-block btn-info btn-xs" onclick="window.location.href='../exam/checkPaper?examid=<?php echo $submission['examid']; ?>&submissionid=<?php echo $submission['submissionid']; ?>'">Check Answer Sheet</button>
                            </td>
                        <?php
                            }else{
                        ?>
                            <td><?php echo $submission['result']; ?></td>
                            <td>
                                <?php if(($submission['status']==3) || ($submission['status']==5)){ ?>
                                  <button type="button" class="btn btn-block btn-warning btn-xs" onclick="window.location.href='../exam/checkPaper?examid=<?php echo $submission['examid']; ?>&submissionid=<?php echo $submission['submissionid']; ?>'" disabled>Didnot Appear / Answer Sheet Not Uploaded</button>
                                <?php }else{ ?>
                                  <button type="button" class="btn btn-block btn-info btn-xs" onclick="window.location.href='../exam/checkPaper?examid=<?php echo $submission['examid']; ?>&submissionid=<?php echo $submission['submissionid']; ?>'">Re-Check Answer Sheet</button>
                                <?php } ?>
                            </td>
                        <?php
                            }
                        ?>
                      </tr>
                    <?php
                      $c++;}}
                    ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
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

<script type="text/javascript">
      // The function below will start the confirmation dialog
      var toURL;
      function confirmAction(toURL) {
        let confirmAction = confirm("Are you sure to delete this student ?");
        if (confirmAction) {
          window.location.href=toURL;
        }
      }
</script>
</body>
</html>