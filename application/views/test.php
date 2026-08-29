
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?= $title ?>
      <small>Deskripsi halaman</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active"><?= $title ?></li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Konten utama Anda di sini -->
    <div class="box">
      <div class="box-header with-border">
        <h3 class="box-title">U N D E R  C O N ST R U C  T I O N !</h3>
      </div>
      <div class="box-body">
        <i class="fa fa-wrench text-danger text-center" style="font-size:30px; font-weight:bold;"></i>
        <h3 class="text-center text-danger">Maaf, halaman ini sedang dalam tahap pengembangan.</h3>
        <p class="text-center">Silakan kunjungi halaman ini kembali nanti.</p>
        <hr>
        <select name="tahun" id="bulan">
          <option value="">-- Pilih Bulan --</option>
          <?= optBulan($bulan); ?>          
        </select>
        <select name="tahun" id="tahun">
          <option value="">-- Pilih Tahun --</option>
          <?= optTahun($tahun); ?>
        </select>
        <br><br>
        <?php 
        print_r($periode); 
        echo "<br>";
        echo $bulan;
        echo "<br>";
        echo $tahun;
        ?>

      </div>
      <!-- /.box-body -->
      <div class="box-footer">
        
      </div>
      <!-- /.box-footer-->
    </div>
    <!-- /.box -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->