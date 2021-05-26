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
    if(!isset($_GET['submissionid'])){
        echo "<script>
        alert('Not all Parameters found');
        window.location.href= '../../index';
        </script>";
    }

    $userid = $_SESSION['userid'];
    $examid = $_GET['examid'];
    $submissionid = $_GET['submissionid'];

    // profiler
    $extractprofile = "SELECT * FROM userbase WHERE userid='$userid'";
    $extractprofileexec = mysqli_query($conn, $extractprofile);
    $profile = mysqli_fetch_array($extractprofileexec);

    // submissionExtract
    $extractsubmission = "SELECT * FROM submissions WHERE examid='$examid' AND submissionid='$submissionid'";
    $extractsubmissionexec = mysqli_query($conn, $extractsubmission);
    $submission = mysqli_fetch_array($extractsubmissionexec);

    //examextract
    $extractexam = "SELECT * FROM exam WHERE examid='$examid'";
    $extractexamexec = mysqli_query($conn, $extractexam);
    $exam = mysqli_fetch_array($extractexamexec);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Exam Check Environment</title>

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
<body class="hold-transition layout-top-nav">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Right navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button" id="timer">
            Exam Paper : <b><?php echo $submissionid; ?></b>
        </a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <button type="button" class="btn btn-block btn-warning btn-sm nav-link" data-toggle="modal" data-target="#modal-default">Cancel</button>
      </li>
    </ul>
  </nav>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    
    <br>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
            <div class="col-5">
                <div class="row">
                    <h5 class="mb-2">Question Paper :</h5>
                </div>
                <div class="row">
                    <iframe src="<?php echo $exam['questionpaper']; ?>pub?embedded=true#toolbar=0&navpanes=0" width="100%" height="825px" frameborder=0></iframe>
                </div>
            </div>
            <div class="col-5">
                <div class="row">
                    <h5 class="mb-2">Answer Script :</h5>
                </div>
                <div class="row">
                    <iframe src="../../<?php echo $submission['answerscript']; ?>#toolbar=0&navpanes=0" width="100%" height="825px" frameborder=0></iframe>
                </div>
            </div>
            <div class="col-2">
                <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Marking Panel</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="uploadMarks?examid=<?php echo $examid; ?>&submissionid=<?php echo $submissionid; ?>" method="POST">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Marks as per Question Order</label>
                            <input id="nums" type="text" class="form-control" placeholder="Enter Marks separated by comma (,)" required name="resultDivision" onkeyup="sum()" value="<?php if(isset($submission['resultDivision'])){ echo $submission['resultDivision']; } ?>">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Sum of Marks</label>
                            <input type="text" class="form-control" id="result" placeholder="Sum" name="result" readonly value="<?php if(isset($submission['result'])){ echo $submission['result']; } ?>">
                        </div>
                        <div class="form-group">
                        <label>Comments :</label>
                            <textarea class="form-control" rows="6" placeholder="Enter detailed answer review" name="resulttext" required><?php if(isset($submission['resulttext'])){ echo $submission['resulttext']; } ?></textarea>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="exampleCheck1" onclick="confirmation(this)">
                            <label class="form-check-label" for="exampleCheck1">Confirm Upload Marks</label>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" id="submit_button" disabled>Upload Marks</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->


<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Submit</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <p>Are you sure, you want to cancel ? There maybe unsaved changes</p>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-warning" onclick="window.location.href='viewDetails?examid=<?php echo $examid; ?>'">Confirm Cancel</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="../../plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
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


<script type="text/javascript">
    function sum() {
      sumofnums = 0;
      nums = document.getElementById("nums").value.split(",");
      for (i = 0; i < nums.length; i++) {
        sumofnums += parseInt(nums[i]);
      }
      document.getElementById("result").value = sumofnums;
    }

    function confirmation(termsCheckBox){
        //If the checkbox has been checked
        if(termsCheckBox.checked){
            //Set the disabled property to FALSE and enable the button.
            document.getElementById("submit_button").disabled = false;
        } else{
            //Otherwise, disable the submit button.
            document.getElementById("submit_button").disabled = true;
        }
    }
</script>

</body>
</html>