    
<?php

$html = '<table border="1" cellpadding="5" cellspacing="0">';
$html .= '<tr><th>Nama Unit Kerja</th></tr>';
foreach($unit_kerja as $row){
    $html .= '<tr>';
    $html .= '<td><input type="radio" name="role_unit_kerja" value="'.$row['nama_unit'].'"/></td>';
    $html .= '</tr>';
}
$html .= '</table>';
//echo $html;


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
  <link rel="stylesheet" href="<?=base_url();?>assets/AdminLTE/dist/css/skins/skin-yellow.min.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body style="background-image: url('/backend/assets/images/orange-geometric.png'); ">

    <div class="container">

      <div class="starter-template">
    <div style="text-shadow: 2px 2px 2px lightgray; color:#000">
      <!--<div style="height:35vw; background-image: url('/backend/assets/images/UI_logo.png'); ">-->
        <div style="text-align:center">
          <hr style="border-color:#fa0">
          <h3>Aplikasi Pengajuan Anggaran</h3>
	        <div style="text-align:center">Fakultas Ilmu Sosial dan Ilmu Politik Universitas Indonesia</div>
        </div>
        <!--<hr style="border-color:#fa0">-->
        <div style="border-top:1px solid #fa0;"></div>
        <div class="row" style="text-align:center">
            <h3 class="box-title">Login Sebagai:</h3>

            <div class="col-sm-4"></div>
            <div class="col-sm-4">
              <?php
                $submit_form = '';
                foreach($unit_kerja as $row){
                    $submit_form .= '
                    <form action="'.base_url().'auth/set_role_bridge" method="POST" id="form">
                        <input type="hidden" id="'.$row['kode_bidang'].'" name="kode_bidang" value="'.$row['kode_bidang'].'"/>
                        <input type="submit" value="'.$row['nama_unit'].'" class="btn btn-default btn-smx btn-block">
                    </form>';
                }
                echo $submit_form;
              ?>
            </div>
            <div class="col-sm-4"></div>>
        </div>


        <!-- 
        <hr>  
        <div class="box box-info" style="overflow:auto">
          <div class="box-header with-border" style="text-align:center">
            <h3 class="box-title">Login Sebagai:</h3>
          </div>
          <div class="box-body">

            <div>
            </div> /. data-list-perbaikan   
          </div>
        </div> -->
      <!--</div>-->
    </div>
        <footer class="main-footer">
          <!-- To the right -->
          <!-- <div class="pull-rightx hidden-xs">
            Komite Kaji Etik
          </div>-->
          <!-- Default to the left -->
          <strong>Keuangan <a href="#">FISIP UI</a></strong>
        </footer>
      </div>

    </div><!-- /.container -->




    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="<?=base_url();?>assets/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
<style>
body{
	background-image:url('/backend/assets/images/orange-geometric.png');
    background-position: center center; /* Background image is centered vertically and horizontally at all times */
    background-repeat: no-repeat; /* Background image doesn’t tile */
    background-attachment: fixed;  /* Background image is fixed in the viewport so that it doesnt move when the content’s height is greater than the image’s height */
    background-size: cover; /* This is what makes the background image rescale based on the container’s size */
}
</style>
</html>