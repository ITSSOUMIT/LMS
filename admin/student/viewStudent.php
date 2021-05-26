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
    $batchneed = $_GET['batch'];

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

    <section class="content">
      <div class="container-fluid">
        <div class="row">
            <?php
                if(strcmp($batchneed, "ALL")==0){
            ?>
            <div class="col-2"><button type="button" class="btn btn-block btn-primary btn-sm" onclick="window.location.href='viewStudent?batch=ALL'">ALL</button></div>
            <?php }else{ ?>
            <div class="col-2"><button type="button" class="btn btn-block btn-outline-primary btn-sm" onclick="window.location.href='viewStudent?batch=ALL'">ALL</button></div>
            <?php } ?>
            <?php
                $batchquery = "SELECT * FROM batch WHERE status=1";
                $batchqueryexec = mysqli_query($conn, $batchquery);
                while($batch = mysqli_fetch_array($batchqueryexec)){
                    if(strcmp($batchneed, $batch['batchcode'])==0){
            ?>
            <div class="col-2"><button type="button" class="btn btn-block btn-primary btn-sm" onclick="window.location.href='viewStudent?batch=<?php echo $batch['batchcode']; ?>'"><?php echo $batch['batchcode']; ?></button></div>
            <?php }else{ ?>
            <div class="col-2"><button type="button" class="btn btn-block btn-outline-primary btn-sm" onclick="window.location.href='viewStudent?batch=<?php echo $batch['batchcode']; ?>'"><?php echo $batch['batchcode']; ?></button></div>
            <?php }} ?>
        </div>
      </div>
    </section>
    <br>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>Sl. No.</th>
                      <th>Name</th>
                      <th>Batch Code</th>
                      <th>Email</th>
                      <th>Phone Number</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      if(strcmp($batchneed, "ALL")==0){
                        $studentquery = "SELECT * FROM userbase WHERE role='student' AND status=1";
                      }else{
                        $studentquery = "SELECT * FROM userbase WHERE role='student' AND status=1 AND batch='$batchneed'";
                      }
                      $studentqueryexec = mysqli_query($conn, $studentquery);
                      if(mysqli_num_rows($studentqueryexec)==0){
                    ?>
                      <tr>
                        <td>No active students found.</td>
                      </tr>
                    <?php
                      }else{
                      $c = 1;
                      while($student = mysqli_fetch_array($studentqueryexec)){
                    ?>
                      <tr>
                        <td><?php echo $c; ?></td>
                        <td><?php echo $student['name']; ?></td>
                        <td><?php echo $student['batch']; ?></td>
                        <td><?php echo $student['email']; ?></td>
                        <td><?php echo $student['phone']; ?></td>
                        <td>
                          <button type="button" class="btn btn-block btn-primary btn-xs" onclick="window.location.href='studentHistory?studentid=<?php echo $student['userid']; ?>'">View Student History</button>
                          <!-- <button type="button" class="btn btn-block btn-danger btn-xs" onclick="window.location.href='deleteStudent?userid=<?php echo $student['userid']; ?>'">Remove Student</button> -->
                          <button type="button" class="btn btn-block btn-danger btn-xs" onclick="confirmAction('deleteStudent?userid=<?php echo $student['userid']; ?>')">Remove Student</button>
                        </td>
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