<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//$role = $this->session->userdata('logged_anggaran')['role'];

//if ($role !== 'admin') {
  //redirect('auth/login');
  //exit;
//}
    // Daftar peran utama
    $roles = [
      'pum' => 'Unit Kerja',
      'anggaran' => 'Unit Anggaran',
      'korpum' => 'Korpum',
      'manajer' => 'Manajer Anggaran dan Keuangan',
      'kasir' => 'Kasir',
      'verifikator' => 'Verifikator',
      'yunior_akuntan' => 'Yunior Akuntan'
    ];

// set nama unit
foreach($array_unit as $row){
	$nama_unit[$row['kode_bidang']] = $row['nama_unit'];
  $deskripsi_dpsj[$row['kode_dpsj']] = $row['deskripsi_dpsj'];
}
//echo '<pre>';print_r($deskripsi_dpsj); print_r($array_user);echo '</pre>'; exit();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <link rel="canonical" href="https://getbootstrap.com/docs/3.4/examples/starter-template/">

    <title>Keuangan FISIP UI</title>

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

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="hold-transition skin-blue layout-top-nav sidebar-mini">
    <div class="wrapper">
      <header class="main-header">
        <a href="<?=base_url();?>" class="logo">
          <span class="logo-mini"><b>FISIP</b></span>
          <span class="logo-lg"><b>Keuangan FISIP UI</b></span>
        </a>
        <nav class="navbar navbar-static-top" role="navigation">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
            </div>
          </div>
        </nav>
      </header>

      <!-- Main content -->
      <section class="content">
        <div class="container">
          <div class="row">
            <div class="col-md-6 col-md-offset-3">
              <h2 class="text-warning"></h2>
              <?php if (isset($error)) { ?>
                <div class="alert alert-danger"><?= $error; ?></div>
              <?php } ?>
            </div>
          </div>

          <!-- Form untuk memilih peran -->
          <div class="row">
            <div class="col-md-6 col-md-offset-3">
              <div class="box box-default">
				<div class="box-header box-border text-center">
					<h4 class="" style="color:#fa0" ><b>Silakan pilih peran di bawah ini untuk masuk ke dalam sistem.</b></h4>
				</div>
                <div class="box-body">
                  <h4 class="text-center text-info">- Login sebagai -</h4>
                  <div class="form-group"> 

                    <?php 
                    // jika sebagai user non admin
                    
                      //echo '<pre>';print_r($nama_unit);echo '</pre>';
                      foreach ($array_user as $row) {
                        //echo '<pre>';print_r($row);echo '</pre>';
                        // Tampilkan tombol untuk peran lain
                      
                        // jika sebagai pum
                        if($row['role'] == 'pum'){
                          echo '
                          <form action="'.base_url().'auth/set_role_pum" method="POST" style="margin-bottom:10px;">
                            <input type="hidden" name="role" value="'.$row['role'].'"/>
                            <input type="hidden" name="kode_bidang" value="'.$row['kode_bidang'].'"/>
                            <input type="hidden" name="kode_dpsj" value="'.$row['kode_dpsj'].'"/>
                            <button type="submit" class="btn btn-info btn-block btn-flat">'.$row['role'].' '.$deskripsi_dpsj[$row['kode_dpsj']].'</button>
                          </form>';
                      
                        } else {
                          echo '
                          <form action="'.base_url().'auth/set_role_admin" method="POST" style="margin-bottom:10px;">
                            <input type="hidden" name="role" value="'.$row['role'].'"/>
                            <input type="hidden" name="kode_bidang" value="'.$row['kode_bidang'].'"/>
                            <button type="submit" class="btn btn-info btn-block btn-flat">Login sebagai '.$row['role'].'</button>
                          </form>';
                      
                        }
                                                
                      }
                    ?>


                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
      <!-- /.content-wrapper -->
      <footer class="main-footer">
        <div class="container">
          <div class="pull-right hidden-xs">
            <b>Version</b> 1.0.0
          </div>
          <strong>Copyright &copy; 2023 <a href="https://fisip.ui.ac.id">FISIP UI</a>.</strong> All rights reserved.
        </div>
      </footer>      

     <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="<?=base_url();?>assets/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
                    
    </body>
</html>