<?php
if (!isset($this->session->userdata['logged_in_presensi'])) {
	redirect('index.php/kehadiran/login/logout');  
}
?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SDM FISIP UI</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="https://komisietika.fisip.ui.ac.id/backend/assets/AdminLTE/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="https://komisietika.fisip.ui.ac.id/backend/assets/AdminLTE/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect.-->
  
  <link rel="stylesheet" href="https://komisietika.fisip.ui.ac.id/backend/assets/AdminLTE/dist/css/skins/skin-blue.css">
   <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load.
  <link rel="stylesheet" href="https://komisietika.fisip.ui.ac.id/backend/assets/AdminLTE/dist/css/skins/_all-skins.min.css"> -->
  <!-- iCheck -->
  <link rel="stylesheet" href="https://komisietika.fisip.ui.ac.id/backend/assets/AdminLTE/plugins/iCheck/flat/blue.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

<!-- REQUIRED JS SCRIPTS -->
<!-- jQuery 2.2.3 -->
<script src="https://komisietika.fisip.ui.ac.id/backend/assets/AdminLTE/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="https://komisietika.fisip.ui.ac.id/backend/assets/AdminLTE/bootstrap/js/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="https://komisietika.fisip.ui.ac.id/backend/assets/AdminLTE/dist/js/app.min.js"></script>

<!-- Optionally, you can add Slimscroll and FastClick plugins.
     Both of these plugins are recommended to enhance the
     user experience. Slimscroll is required when using the
     fixed layout. -->

     <!-- Bootstrap 4 -->
<script src="https://komisietika.fisip.ui.ac.id/backend/assets/AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- bs-custom-file-input -->
<script src="https://komisietika.fisip.ui.ac.id/backend/assets/AdminLTE/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>

<script src="https://komisietika.fisip.ui.ac.id/backend/assets/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="https://komisietika.fisip.ui.ac.id/backend/assets/AdminLTE/plugins/datepicker/locales/bootstrap-datepicker.id.js"></script>

</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<?php
ini_set('allow_url_include', true);
?>
<body class="hold-transition skin-blue sidebar-mini fixed">
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
  <!-- Left side column. contains the logo and sidebar --><?php print_r($this->session->userdata['logged_in_presensi']);?>