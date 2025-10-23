<?php
if (!$this->session->userdata('kode_bidang')) {
    redirect('auth/unit_kerja');
}
$array_nama_status = array(0=>'Belum Diajulan', 1=>'Menunggu Verifikasi Anggaran', 2=>'disetujui', 3=>'ditolak', 4=>'dibatalkan', 5=>'diterima', 6=>'selesai');
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
       
        foreach($rincian as $row){
          //$array[$row['nomor_pengajuan']][] = $row;  // script lama
          $array[$row['id_pengajuan_pemohon']][] = $row;
          $array_rincian[$row['nomor_pengajuan']][$row['kode_kegiatan']][] = $row;
        }
        echo '<pre>';print_r($array_untuk);echo '</pre>';
        
        if (empty($rincian)) {
            echo '<div class="alert alert-info">Tidak ada data pengajuan.</div>';
            return;
        }

        $html= '<table class="table table-bordered table-stripedx" id="example">';
        //$html.= '<thead>';
        //$html.= '<tr>';
        //$html.= '<th>No Pengajuan</th>';
        //$html.= '<th>Nama Kegiatan</th>';
        //$html.= '<th>Deskripsi Akun</th>';
        //$html.= '<th>Keterangan</th>';
        //$html.= '<th>Komitmen</th>';
        //$html.= '</tr>';
        //$html.= '</thead>';
        $html.= '<tbody>';

        /*
        foreach($array_rincian as $key => $value){
          echo '<tr><td colspan="5"><strong>'.$key.'</strong></td></tr>'; // No Pengajuan
          foreach($value as $k => $v){
            echo '<tr><td colspan="5"><strong>'.$k.'</strong></td></tr>'; // Kode Kegiatan
            foreach($v as $row){
              $html.= '<tr>';
              if ($k == array_key_first($value)) {
                //$html.= '<td rowspan="'.count($v).'">'.$key.'</td>'; // No Pengajuan
                $html.= '<td rowspan="'.count($v).'"></td>';
              } else {
                $html.= '<td></td>'; // Kosongkan untuk baris berikutnya
              }
              $html.= '<td>'.$row['nama_kegiatan'].'</td>';
              $html.= '<td>'.$row['deskripsi_akun'].'</td>';
              $html.= '<td>'.$row['keterangan'].'</td>';
              $html.= '<td>'.$row['komitmen'].'</td>';
              $html.= '</tr>';
            }
          }
        }
          */
          
        foreach($array as $key => $value) {

            $nomor_pengajuan_val = isset($value[0]['nomor_pengajuan']) ? $value[0]['nomor_pengajuan'] : null;
            $kode_status_val = isset($array_kode_status[$nomor_pengajuan_val]) ? $array_kode_status[$nomor_pengajuan_val] : null;

            if(empty($array_kode_status[$nomor_pengajuan_val])) {
                $disabled = '';
                //continue; // Skip if no status code
            } else {
              if($array_kode_status[$nomor_pengajuan_val] == 0) {
                  $disabled = '';
              } else {
                  $disabled = 'disabled';
              }
            }

            if($nomor_pengajuan_val) {
                $nomor_pengajuan = $nomor_pengajuan_val;;
            } else {
                $nomor_pengajuan = '<font class="text-danger">Not Set</font>';
            }

            if(empty($array_untuk[$key])) {
                $untuk = '<font class="text-danger">Not Set</font>';
            } else {
              if($array_untuk[$key]) {
                  $untuk = ' - '.$array_untuk[$key];
              } else {
                  $untuk = '<font class="text-danger">Not Set</font>';
              }
            }
            // Check if the status code exists in the array
            // If it does not exist, set status to 'Belum Diajukan'
            // If it exists, use the corresponding status name
            // This avoids the error when trying to access an undefined index in the array
            $status = 'Belum Diajukan'; // Default status
            if(isset($array_kode_status[$nomor_pengajuan_val]) && isset($array_nama_status[$array_kode_status[$nomor_pengajuan_val]])) {
                $status = $array_nama_status[$array_kode_status[$nomor_pengajuan_val]];
            } else {
                $status = 'Belum Diajukan'; // Default if not set
            }
/*            
            if(empty($array_nama_status[$array_kode_status[$nomor_pengajuan_val]])) {
                $status = 'Belum Diajukan';
            } else {
                // Use the status name from the array
                $status = $array_nama_status[$array_kode_status[$nomor_pengajuan_val]];
            }

            if(empty($array_nama_status[$array_kode_status[$nomor_pengajuan_val]])) {
                $status = 'Belum Diajukan';
            } else {
                // Use the status name from the array
                if(empty($array_kode_status[$nomor_pengajuan_val])) {
                    $status = 'Belum Diajukan';
                } else {
                    $status = $array_nama_status[$array_kode_status[$nomor_pengajuan_val]];
                }
                //$status = $array_nama_status[$array_kode_status[$value[0]['nomor_pengajuan']]];
            }
*/
            $html.= '<tr style="background-color:#f7f7f7;">'; // No Pengajuan
            $html.= '
              <td class="text-info" style="border-top:1px solid #ddd;border-left:1px solid #ddd;" colspan="3"><strong>'.$nomor_pengajuan.' '.$untuk.'</strong></td>
              <td class="text-danger text-center"><i><strong>'.$status.'</strong></i></td>
              <td colspan="4" class="text-center">
                <button class="btn btn-primary btn-xs ajukan" data-nomor_pengajuan="'.$key.'" data-toggle="modal" data-target="#modal-ajukan" '.$disabled.' >ajukan</button>
                <button class="btn btn-success btn-xs edit" data-nomor_pengajuan="'.$key.'" data-toggle="modal" data-target="#modal-edit-pengajuan" '.$disabled.'>edit</button>
                <button class="btn btn-danger btn-xs delete" data-nomor_pengajuan="'.$key.'" '.$disabled.'>delete</button>
				      </td>'; // No Pengajuan
            $html.='</tr>';
          
            foreach($value as $row) {
                //$html.= '<tr><th></th><th>Nama Kegiatan</th><th>Deskripsi Akun</th><th>Keterangan</th><th>Komitmen</th></tr>';
                $html.= '<tr>';
                $html.= '<td style="color:#fff; border-left:1px solid #ddd;">'.$key.'</td>'; // No Pengajuan, already displayed
                $html.= '<td>'.$row['kode_kegiatan'].'</td>';
                $html.= '<td>'.$row['nama_kegiatan'].'</td>';
                $html.= '<td>'.$row['kode_akun'].'</td>';
                $html.= '<td>'.$row['deskripsi_akun'].'</td>';
                $html.= '<td>'.$row['keterangan'].'</td>';
                $html.= '<td style="border-right:1px solid #ddd;">'.number_format($row['komitmen']).'</td>';
                $html.= '</tr>';
            }
            
                $html.= '<tr>'; // Separator row
                $html.= '<td colspan="5" style="border-left:1px solid #fff; border-right:1px solid #fff;border-top:1px solid #ddd; "></td>'; // Empty row for spacing
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
<div class="modal fade" id="modal-ajukan" tabindex="-1" role="dialog" aria-labelledby="viewAjukanLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="ajukan-title">Anda akan mengajukan data pengajuan sebagai berikut:</h4>
			</div>
			<div class="modal-body" style="overflow:auto">
				<div id="data-ajukan">
					
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal" aria-label="Close">Tutup</button>
			</div>
		</div>
	</div>
</div>

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
