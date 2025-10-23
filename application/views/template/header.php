<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= isset($title) ? $title : 'Sistem Pengajuan Anggaran' ?> | FISIP UI</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?=base_url();?>assets/AdminLTE/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?=base_url();?>assets/AdminLTE/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect.
  -->
  <link rel="stylesheet" href="<?=base_url();?>assets/AdminLTE/dist/css/skins/skin-blue.min.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
  
<!-- REQUIRED JS SCRIPTS -->
 
<!-- jQuery 2.2.3 -->
<script src="<?=base_url();?>assets/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?=base_url();?>assets/AdminLTE/bootstrap/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="<?=base_url();?>assets/AdminLTE/dist/js/app.min.js"></script>

<div class="wrapper">

  <!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="<?=base_url()?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">FISIP</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">
            <!--<font style="color:rgb(255,217,97)">PPF</font>&nbsp;FISIP&nbsp;-->
            <font style="color:rgb(255,217,97)">&nbsp;FISIP&nbsp;</font>
            <font style="color:yellow"></font>
            <img src="https://ppf.fisip.ui.ac.id/backend/assets/images/logo_UI-Horizontal_frameyelow.png" height="30%" width="30%" />
      </span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
            <!-- Messages: style can be found in dropdown.less
            <li class="dropdown messages-menu">
            </li>-->
            <!-- Notifications: style can be found in dropdown.less
            <li class="dropdown notifications-menu">
            </li> -->
            <!-- Tasks: style can be found in dropdown.less
            <li class="dropdown tasks-menu">
            </li> -->
            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <!--<img src="<?=base_url();?>assets/AdminLTE/dist/img/avatar2.png" class="user-image" alt="User Image">-->                    
                    <?php
                    //$photo = 'data:image/png;base64,'.$foto;
                    //echo '<img src = '.$photo.' class="user-image" alt="User Image"/>';
                    ?>
                    <span class="hidden-xs"><?=$nama?></span>                    
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                    <li class="user-header">
                      <?php
                      //$photo = 'data:image/png;base64,'.$foto;
                              //echo '<img src = '.$photo.' class="img-image" alt="User Image" style="width:80px"/>';
                      ?>
                      <p>
                          <?//=$nama?>
                          <small>FISIP UI</small>
                      </p>
                    </li>
                    <!-- Menu Body -->
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <div class="pull-left">
                            <!--<a href="#" class="btn btn-default btn-flat">Profile</a>-->
                        </div>
                        <div class="pull-right">
                            <a href="<?=base_url()?>index.php/login/logout" class="btn btn-default btn-flat">Logout</a>
                        </div>
                    </li>
                </ul>
            </li>
          <!-- Control Sidebar Toggle Button -->
                <li>
                    <!--<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>-->
                </li>
                <li>
                
                </li>
            </ul>
        </div>
    </nav>
  </header>
  <br><br>