<?php
    session_start();
    include("../../db/connection.php");
    if(!isset($_SESSION['session']) || !isset($_SESSION['userid']) || !isset($_SESSION['role'])){
        echo "<script>
        alert('Session Not Authorized');
        window.location.href= '../../index';
        </script>";
    }elseif(strcmp($_SESSION['role'], "student") != 0){
        echo "<script>
        alert('You are not authorized to access this page');
        window.location.href= '../../index';
        </script>";
    }

    if(!isset($_GET['examid']) || !isset($_GET['submissionid'])){
        echo "<script>
        alert('Missing');
        window.location.href= '../../index';
        </script>";
    }

    $examid = $_GET['examid'];
    $submissionid = $_GET['submissionid'];

    $extractexam = "SELECT * FROM exam WHERE examid='$examid' AND status=2";
    $extractexamexec = mysqli_query($conn, $extractexam);
    if(mysqli_num_rows($extractexamexec)==1){
        $exam = mysqli_fetch_array($extractexamexec);
        $questionpaper = $exam['questionpaper'];
    }
    $submissionquery = "SELECT * FROM submissions WHERE submissionid='$submissionid'";
    $submissionqueryexec = mysqli_query($conn, $submissionquery);
    $submission = mysqli_fetch_array($submissionqueryexec);

    if($submission['status']==4){
      echo "<script>
      window.location.href='uploadAnswerScript?examid=<?php echo $examid; ?>&submissionid=<?php echo $submissionid; ?>';
        </script>";
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Exam Environment</title>

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
          
        </a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <button type="button" class="btn btn-block btn-danger btn-sm nav-link" data-toggle="modal" data-target="#modal-default">Final Submit</button>
      </li>
    </ul>
  </nav>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
            <iframe src="<?php echo $questionpaper; ?>pub?embedded=true" width="100%" height="825px" frameborder=0></iframe>
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
              <p>Are you sure, you want to submit ?</p>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-danger" onclick="window.location.href='uploadAnswerScript?examid=<?php echo $examid; ?>&submissionid=<?php echo $submissionid; ?>'">Confirm Submit</button>
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

<script>
    var sec         = <?php echo $submission['timeleft']; ?>,
    countDiv    = document.getElementById("timer"),
    secpass,
    countDown   = setInterval(function () {
        'use strict';
        
        secpass();
    }, 1000);

    function secpass() {
        'use strict';
        
        var min     = Math.floor(sec / 60),
            remSec  = sec % 60;
        
        if (remSec < 10) {
            
            remSec = '0' + remSec;
        
        }
        if (min < 10) {
            
            min = '0' + min;
        
        }
        countDiv.innerHTML = min + ":" + remSec;
        
        if (sec > 0) {
            
            sec = sec - 1;
            
        } else {
            
            clearInterval(countDown);
            
            window.location.href='uploadAnswerScript?examid=<?php echo $examid; ?>&submissionid=<?php echo $submissionid; ?>'
            
        }
    }
</script>

<script>
  var prevState = "visible";

  //timeLogger
  setInterval(function(){
    $.ajax({
        url : 'auxActions/timeLogger?examid=<?php echo $examid; ?>&submissionid=<?php echo $submissionid; ?>',
        type : 'POST',
        success : function (result) {
          console.log("Now");
        },
        error : function () {
        }
      });
  }, 15*1000);


  //cheatLog
  setInterval(function() {
    document.addEventListener("visibilitychange", function() {
      var currState = document.visibilityState;
      if(prevState != currState){
        if((prevState == "visible") && (currState == "hidden")){
          $.ajax({
            url : 'auxActions/cheatLog?examid=<?php echo $examid; ?>&submissionid=<?php echo $submissionid; ?>',
            type : 'POST',
            success : function (result) {
              console.log ("Tab changed");
            },
            error : function () {
              console.log ('error');
            }
          });
        }else if((prevState == "hidden") && (currState == "visible")){
          toastr.error('You left this tab ! Avoid doing so !');
        }
      }
      prevState = currState;
    });
  }, 1000);
</script>

</body>
</html>