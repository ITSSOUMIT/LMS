<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../../index" class="brand-link">
      <img src="../../dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light"><?php echo $profile['name']; ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-header">EXAM RELATED :</li>
          <!-- <li class="nav-item">
            <a href="pages/calendar.html" class="nav-link active">
              <i class="nav-icon far fa-calendar-alt"></i>
              <p>
                Calendar
                <span class="badge badge-info right">2</span>
              </p>
            </a>
          </li> -->
          <li class="nav-item">
            <a href="../dashboard" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Open Exams
                <span class="badge badge-danger right">
                    <?php
                        $tempquery = "SELECT * FROM submissions WHERE userid='$userid' AND status=0";
                        $tempqueryexec = mysqli_query($conn, $tempquery);
                        echo mysqli_num_rows($tempqueryexec);
                    ?>
                </span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="attemptedExam" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Exam History
                <span class="badge badge-success right">
                    <?php
                        $tempquery = "SELECT * FROM submissions WHERE userid='$userid'";
                        $tempqueryexec = mysqli_query($conn, $tempquery);
                        echo mysqli_num_rows($tempqueryexec);
                    ?>
                </span>
              </p>
            </a>
          </li>

          <li class="nav-header">ACCOUNT RELATED :</li>
          <li class="nav-item">
            <a href="../../auth/changePassword" class="nav-link">
              <i class="nav-icon fas fa-key"></i>
              <p>
                Change Password
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../../auth/logout" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>
                Logout
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>