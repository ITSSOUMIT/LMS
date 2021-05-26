<!-- if status is ongoing / unevaluated, then include submissions and show status regarding people who didnot submit / submitting -->
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
    if(!isset($_GET['examid'])){
        echo "<script>
        alert('Not all Parameters found');
        window.location.href= '../../index';
        </script>";
    }

    $userid = $_SESSION['userid'];
    $examid = $_GET['examid'];

    // profiler
    $extractprofile = "SELECT * FROM userbase WHERE userid='$userid'";
    $extractprofileexec = mysqli_query($conn, $extractprofile);
    $profile = mysqli_fetch_array($extractprofileexec);

    $extractexam = "SELECT * FROM exam WHERE examid='$examid' AND status=2";
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
  <!--Toastr-->
  <link rel="stylesheet" href="../../plugins/toastr/toastr.min.css">
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


<?php
    $extractexam = "SELECT * FROM exam WHERE examid='$examid'";
    $extractexamexec = mysqli_query($conn, $extractexam);
    if(mysqli_num_rows($extractexamexec)==0){
        echo "<script>
        alert('No such exam found');
        window.location.href= '../../index';
        </script>";
    }
    $exam = mysqli_fetch_array($extractexamexec);
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Exam Detail :</h1>
          </div><!-- /.col -->
          <?php if($exam['status']==0){ ?>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item" active><a href="downloadResult?examid=<?php echo $examid; ?>">Download Results</a></li>
            </ol>
          </div>
          <?php }elseif($exam['status']==3){ ?>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item" active><a href="changeExamState?examid=<?php echo $examid; ?>&from=3&to=0">Mark as Checked and Publish Results</a></li>
            </ol>
          </div>
          <?php } ?>
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
                    <b>Exam ID : </b><?php echo $exam['examid']; ?><br>
                </div>
                <div class="col-sm-6 invoice-col">
                    <b>Question Paper : </b><a href="<?php echo $exam['questionpaper']; ?>" target="_blank"><?php echo substr($exam['questionpaper'], 0, 32)."..."; ?></a><br>
                </div>
              </div>
              <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                    <b>Exam Name : </b><?php echo $exam['examname']; ?><br>
                    <b>Batch Code : </b><?php echo $exam['batchcode']; ?><br>
                </div>
                <div class="col-sm-4 invoice-col">
                    <b>Exam Duration : </b><?php echo $exam['examduration']; ?> minutes<br>
                    <b>Submission Duration : </b><?php echo $exam['submissionduration']; ?> minutes<br>
                </div>
                <div class="col-sm-4 invoice-col">
                    <b>Full Marks : </b><?php echo $exam['fullmarks']; ?><br>
                    <b>Created On : </b><?php echo $exam['created_on']; ?><br>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Submitted :</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <?php
                            $submissionquery = "SELECT * FROM submissions WHERE examid='$examid' AND status=1";
                            // $submissionquery = "SELECT * FROM submissions WHERE examid='$examid'";
                            $submissionqueryexec = mysqli_query($conn, $submissionquery);
                        ?>
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                <th>Sl. No.</th>
                                <th>Student Name</th>
                                <th>Time Consumed</th>
                                <th>Time taken to submit</th>
                                <th>Changed Tabs</th>
                                <th>Marks Obtained</th>
                                <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $c = 1;
                                while($submission = mysqli_fetch_array($submissionqueryexec)){
                                ?>
                                <tr>
                                    <td><?php echo $c; ?></td>
                                    <td><?php echo extractStudentInfo($submission['userid'], "name"); ?></td>
                                    <td><?php echo $exam['examduration']-round($submission['timeleft']/60, 0, PHP_ROUND_HALF_UP); ?> minutes</td>
                                    <td><?php echo $exam['submissionduration']-round($submission['submissiontimeleft']/60, 0, PHP_ROUND_HALF_UP); ?> minutes</td>
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
                                        <button type="button" class="btn btn-block btn-info btn-xs" onclick="window.location.href='checkPaper?examid=<?php echo $examid; ?>&submissionid=<?php echo $submission['submissionid']; ?>'">Check Answer Sheet</button>
                                    </td>
                                    <?php
                                        }else{
                                    ?>
                                    <td><?php echo $submission['result']; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-block btn-info btn-xs" onclick="window.location.href='checkPaper?examid=<?php echo $examid; ?>&submissionid=<?php echo $submission['submissionid']; ?>'">Re-Check Answer Sheet</button>
                                    </td>
                                    <?php
                                        }
                                    ?>
                                </tr>
                                <?php
                                $c++;}
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Attempted & Not Uploaded :</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <?php
                            $submissionquery = "SELECT * FROM submissions WHERE examid='$examid' AND status=5";
                            // $submissionquery = "SELECT * FROM submissions WHERE examid='$examid'";
                            $submissionqueryexec = mysqli_query($conn, $submissionquery);
                        ?>
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                <th>Sl. No.</th>
                                <th>Student Name</th>
                                <th>Time Consumed</th>
                                <th>Changed Tabs</th>
                                <!-- <th>Time taken to submit</th>
                                <th>Evaluated / Unevaluated</th>
                                <th>Action</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $c = 1;
                                while($submission = mysqli_fetch_array($submissionqueryexec)){
                                ?>
                                <tr>
                                    <td><?php echo $c; ?></td>
                                    <td><?php echo extractStudentInfo($submission['userid'], "name"); ?></td>
                                    <td><?php echo $exam['examduration']-round($submission['timeleft']/60, 0, PHP_ROUND_HALF_UP); ?> minutes</td>
                                    <!-- <td><?php echo $exam['submissionduration']-round($submission['submissiontimeleft']/60, 0, PHP_ROUND_HALF_UP); ?> minutes</td> -->
                                    <?php
                                        $submissionid = $submission['submissionid'];
                                        $cheatcount = "SELECT * FROM cheatlog WHERE submissionid='$submissionid'";
                                        $cheatcountexec = mysqli_query($conn, $cheatcount);
                                    ?>
                                    <td><?php echo mysqli_num_rows($cheatcountexec); ?> times</td>
                                    <!--
                                    <?php
                                        if($submission['result']==NULL){
                                    ?>
                                    <td><font color="red">Unevaluated</font></td>
                                    <td>
                                        <button type="button" class="btn btn-block btn-info btn-xs" onclick="window.location.href='checkPaper?examid=<?php echo $examid; ?>&submissionid=<?php echo $submission['submissionid']; ?>'">Check Answer Sheet</button>
                                    </td>
                                    <?php
                                        }else{
                                    ?>
                                    <td><font color="#3ded97">Evaluated</font></td>
                                    <td>
                                        <button type="button" class="btn btn-block btn-info btn-xs" onclick="window.location.href='checkPaper?examid=<?php echo $examid; ?>&submissionid=<?php echo $submission['submissionid']; ?>'">Re-Check Answer Sheet</button>
                                    </td>
                                    <?php
                                        }
                                    ?> -->
                                </tr>
                                <?php
                                $c++;}
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Not Appearing :</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <?php
                            $submissionquery = "SELECT * FROM submissions WHERE examid='$examid' AND status=3";
                            // $submissionquery = "SELECT * FROM submissions WHERE examid='$examid'";
                            $submissionqueryexec = mysqli_query($conn, $submissionquery);
                        ?>
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                <th>Sl. No.</th>
                                <th>Student Name</th>
                                <!-- <th>Time Consumed</th> -->
                                <!-- <th>Time taken to submit</th>
                                <th>Evaluated / Unevaluated</th>
                                <th>Action</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $c = 1;
                                while($submission = mysqli_fetch_array($submissionqueryexec)){
                                ?>
                                <tr>
                                    <td><?php echo $c; ?></td>
                                    <td><?php echo extractStudentInfo($submission['userid'], "name"); ?></td>
                                    <!-- <td><?php echo $exam['examduration']-round($submission['timeleft']/60, 0, PHP_ROUND_HALF_UP); ?> minutes</td> -->
                                    <!-- <td><?php echo $exam['submissionduration']-round($submission['submissiontimeleft']/60, 0, PHP_ROUND_HALF_UP); ?> minutes</td>
                                    <?php
                                        if($submission['result']==NULL){
                                    ?>
                                    <td><font color="red">Unevaluated</font></td>
                                    <td>
                                        <button type="button" class="btn btn-block btn-info btn-xs" onclick="window.location.href='checkPaper?examid=<?php echo $examid; ?>&submissionid=<?php echo $submission['submissionid']; ?>'">Check Answer Sheet</button>
                                    </td>
                                    <?php
                                        }else{
                                    ?>
                                    <td><font color="#3ded97">Evaluated</font></td>
                                    <td>
                                        <button type="button" class="btn btn-block btn-info btn-xs" onclick="window.location.href='checkPaper?examid=<?php echo $examid; ?>&submissionid=<?php echo $submission['submissionid']; ?>'">Re-Check Answer Sheet</button>
                                    </td>
                                    <?php
                                        }
                                    ?> -->
                                </tr>
                                <?php
                                $c++;}
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
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
<!-- Toastr -->
<script src="../../plugins/toastr/toastr.min.js"></script>

<?php
    if(isset($_GET['paperChecked'])){
?>
    <script type="text/javascript">
        $(window).on('load', function() {
            toastr.success('Marks Uploaded')
        });
    </script>
<?php
    }
?>
</body>
</html>