<?php
//$role_name = $this->session->userdata['role_name'];
//$kajietik_role_id = $this->session->userdata['logged_in']['kajietik_role_id'];
?>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
		<!-- Sidebar user panel -->
        <div class="user-panel">
        </div>
      <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <li class="header">MENU</li>
        <?php //echo getMenu(0); ?>
            <li>
                <a class="page" href="<?=base_url()?>index.php/kehadiran/beranda"><i class="fa fa-home text-light"></i> <span> Beranda </span></a>
            </li>
            <li>
              <a class="page" href="<?=base_url()?>index.php/kehadiran/rekam_kehadiran"><i class="fa fa-save text-light"></i> <span> Rekam Kehadiran </span></a>
            </li>
            <li>
              <a class="page" href="<?=base_url()?>index.php/kehadiran/data/detail"><i class="fa fa-circle text-light"></i> <span> Data Kehadiran </span></a>
            </li>

            <?php 
            if($this->session->userdata['logged_in_presensi']['hak_akses_presensi'] == 1)
            { 
              echo '
              <li>
                <a class="page" href="'.base_url('index.php/kehadiran/data/listpegawai').'"><i class="fa fa-list text-light"></i> <span> Daftar Pegawai </span></a>
              </li>';
            }           
            ?>

            <li>
              <a class="page" href="<?=base_url()?>index.php/kehadiran/login/logout"><i class="fa fa-sign-out text-light"></i> <span> Logout </span></a>
            </li>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header)
    <section class="content-header">
      <h1>
        Page Header
        <small>Optional description</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>
    </section> -->

    <!-- Main content -->
    <section class="content">

      <!-- Your Page Content Here -->