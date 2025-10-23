<?php
if (!$this->session->userdata('kode_bidang')) {
    redirect('auth/unit_kerja');
}
?>

<!-- DataTables -->
<link rel="stylesheet" href="<?=base_url();?>assets/AdminLTE/plugins/datatables/dataTables.bootstrap.css">

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?= $title ?>
      <small>Daftar Pengajuan</small>
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
        <h3 class="box-title">U N D E R  C O N S T R U C T I O N !</h3>
      </div>
      <div class="box-body" style="overflow:auto;">
        <?php
       echo '<pre>';print_r($sql);echo '</pre>';
        foreach($rincian as $row){
          $array[$row['nomor_pengajuan']][] = $row;
          $array_rincian[$row['nomor_pengajuan']][$row['kode_kegiatan']][] = $row;
        }
        
        
        if (empty($rincian)) {
            echo '<div class="alert alert-info">Tidak ada data pengajuan.</div>';
            return;
        }

        $html= '<table class="table table-bordered table-stripedx" id="examplex" border="1">';

        $html.= '<tbody>';
          
        foreach($array as $key => $value) {
            $html.= '<tr>'; // No Pengajuan
            $html.= '
              <td colspan="3" class="text-info" style="border-top:1px solid #fff;border-left:1px solid #fff;font-size:15px;font-weight:bold;"><strong>'.$key.' '.$array_uraian[$key].'</strong></td>
              <td colspan="5" class="text-danger text-center"><strong>Belum Diajukan</strong></td>
              <td colspan="2" class="text-center">
                
			</td>'; // No Pengajuan
            $html.='</tr>';
          	$html.= '<tr style="background-color:#f7f7f7;color:#777"><th class="subhead">Kode Procost</th><th>Nama Procost</th><th>Kode Akun</th><th>Deskripsi Akun</th><th>Jumlah UMKO</th><th>Realisasi</th><th>Sisa UMKO</th><th></th></tr>';
			$n=1;
            foreach($value as $row) {
                
                $html.= '<tr>';
                $html.= '<td>'.$row['kode_kegiatan'].'</td>';
                $html.= '<td>'.$row['nama_kegiatan'].'</td>';
                $html.= '<td>'.$row['kode_akun'].'</td>';
                $html.= '<td>'.$row['deskripsi_akun'].'</td>';
                $html.= '<td style="border-right:1px solid #ddd;">'.number_format($row['komitmen']).'</td>';
                $html.= '<td style="border-right:1px solid #ddd;">0</td>';
				$html.= '<td style="border-right:1px solid #ddd;">0</td>';

				
				if($n > 1){
					$html.='';
				} else {
					$html.= '
					<td rowspan="'.count($value).'" style="border-right:1px solid #ddd;">
						<button class="btn btn-primary btn-xs buat-realisasi" data-nomor_pengajuan="'.$key.'" data-toggle="modal" data-target="#modal-buat">buat realisasi</button>
						<button class="btn btn-warning btn-xs view-realisasi" data-nomor_pengajuan="'.$key.'" data-toggle="modal" data-target="#modal-view">view realisasi</button>
					</td>';	
				}

				
                $html.= '</tr>';
				$n++;
            }
            
            $html.= '<tr>'; // Separator row
            $html.= '<td colspan="8" style="border-left:1px solid #fff; border-right:1px solid #fff;border-top:1px solid #ddd; "></td>'; // Empty row for spacing
            $html.= '</tr>';
        }
        
        $html.= '</tbody></table>';
        echo $html;

        ?>

        <!--
        <hr style="border:1px solid red">
        <table class="table table-bordered table-striped" id="example1">
          <thead>
            <tr>
              <th>No Pengajuan</th>
                <th>Nama Kegiatan</th>
                <th>Deskripsi Akun</th>
                <th>Keterangan</th>
                <th>Komitmen</th>
            </tr>
          </thead>
          <tbody>
          <?php
          foreach($rincian as $row){
          ?>
            <tr>
              <td><?= $row['nomor_pengajuan'] ?></td>
              <td><?= $row['nama_kegiatan'] ?></td>
              <td><?= $row['deskripsi_akun'] ?></td>
              <td><?= $row['keterangan'] ?></td>
              <td><?= number_format($row['komitmen']) ?></td>
            </tr>
          <?php
          }
          ?>
            </tr>
          </tbody>
        </table>
        -->
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


<!-- modal edit form pengajuan -->
<div class="modal fade" id="modal-edit-pengajuan" tabindex="-1" role="dialog" aria-labelledby="viewDokumenModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="pengajuan-title">Edit Data Pengajuan</h4>
			</div>
			<div class="modal-body" style="overflow:auto">
				<div id="data-pengajuan">
					<?php //include(APPPATH.'views/unit_kerja/form_edit_pengajuan.php') ?>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal" aria-label="Close">Tutup</button>
			</div>
		</div>
	</div>
</div>

<style>
table > tr > th {
    vertical-align:middle;
}
</style>


<!-- DataTables -->
<script src="<?=base_url();?>assets/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=base_url();?>assets/AdminLTE/plugins/datatables/dataTables.bootstrap.min.js"></script>

<?php include(APPPATH.'views/unit_kerja/daftar_pengajuan_script.php');?>
