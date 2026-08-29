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
         <input type="hidden" id="temp_page" value="0" style="display:nonex">
		<div class="box" style="padding:10px">
			<div class="box-body">
	            <div class="row text-center">
	                <div class="form-group form-inline col-sm-12">
	                    <label for="tahun" class=" control-label text-right">Periode</label>
						
						<select name="tgl" id="tgl" class="form-control" >
							<?php
								for($d=1; $d<=31; $d++){
									$day = str_pad($d, 2, "0", STR_PAD_LEFT);
									echo "<option value='$day'>$day</option>";
								}
							?>
						</select>
						<select name="bulan" id="bulan" class="form-control" >
	                        <?php echo optBulan($bulan); ?>
	                    </select>
	                    <select name="tahun" id="tahun" class="form-control" >
	                        <?php echo optTahun($tahun); ?>
	                    </select>
	                    
	                </div>                                   
	            </div>
	            <div class="row text-center">
					<div class="form-group form-inline col-sm-12">
	                    <label for="no_invoice_pp" class=" control-label text-right">No Invoice PP</label>
	                    <input type="text" name="no_invoice_pp" id="no_invoice_pp" class="form-control" >
	                        
	                    <label for="uraian" class=" control-label text-right">Uraian</label>
	                    <input type="text" name="uraian" id="uraian" class="form-control" >
	                </div>
	            </div>
	            <div class="row text-center">
	            </div>
	            <div class="row text-center">
					<button id="cari-pengajuan" data-toggle="modal" data-target="#modal-data">Pilih Data</button>                 
	            </div>
				<input type="text" id="no_tiket" value="" style="display:none">
			</div>
		</div>

	    <div class="row">
	        <div class="box col-md-12 col-lg-12">
	            <div class="box-body" style="overflow:auto">
	                <div id=data-procost>
						<table class="styled-table" width="100%">
							<thead>
								<tr>
									<th>NO</th>
									<th>NOMOR PENGAJUAN</th>
									<th>NO INVOICE PP</th>
									<th>URAIAN</th>
									<th>PROCOST</th>
									<th>AKUN</th>
									<th>SUMBER DANA</th>
									<th>SEGMEN</th>
									<th>BRUTO</th>
									<th>PAJAK</th>
									<th>NETTO</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
	            </div>
	        </div>
	    </div>
	
    </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- modal data pengajuan -->
<div class="modal fade" id="modal-data" tabindex="-1" role="dialog" aria-labelledby="viewDataModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="data-title">DATA PROJECT COASTING</h4>
			</div>
			<div class="modal-body" style="overflowx:autox">
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

<script>
$(document).ready(function()
{
	$(document).on("click","#cari-pengajuan", function(){
		var tahun = $("#tahun").val();
		var bulan = $("#bulan").val();

		// ambil data pengajuan menggunakan AJAX
        $.ajax({
            url: "<?=base_url()?>index.php/invoice/search_pengajuan",
            type: "POST",
            data: {tahun:tahun, bulan:bulan},
            success: function(response) {
                // Tampilkan pesan sukses atau lakukan tindakan lain
                //alert("Data berhasil disimpan!");
                $("#data-pengajuan").html(response);
                console.log(response);
            },
            error: function(xhr, status, error) {
                // Tampilkan pesan kesalahan
                alert("Terjadi kesalahan saat ...");
                //console.log(error);
            }
        });
	});

	$(document).on("click","#simpan-rekap-procost", function()
	{
		// jika no_invoice_pp dan uraian kosong maka tampilkan alert
		if ($("#no_invoice_pp").val() == '' || $("#uraian").val() == '') {
			alert("No Invoice PP dan Uraian harus diisi!");
			return false;
		}
		var no_tiket = <?php echo time(); ?>;
		$("#no_tiket").val(no_tiket);
		var no_invoice_pp = $("#no_invoice_pp").val();
		var uraian = $("#uraian").val();
		var tahun = $("#tahun").val();
		var bulan = $("#bulan").val();
		var tgl = $("#tgl").val();
		var id_pengajuan_pemohon = $("#id_pengajuan_pemohon").val();

		// ambil data pengajuan menggunakan AJAX
		$.ajax({
			url: "<?=base_url()?>index.php/invoice/simpan_rekap_procost",
			type: "POST",
			data: {id_pengajuan_pemohon:id_pengajuan_pemohon, no_invoice_pp:no_invoice_pp, uraian:uraian, tahun:tahun, bulan:bulan, tgl:tgl, no_tiket:$("#no_tiket").val()},
			success: function(response) {
				// Tampilkan pesan sukses atau lakukan tindakan lain
				alert("Data berhasil disimpan!");
				location.href = "../invoice";
				//$("#data-procost").html(response);
				console.log(response);
			},
			error: function(xhr, status, error) {
				// Tampilkan pesan kesalahan
				alert("Terjadi kesalahan saat ...");
				//console.log(error);
			}
		});
	});
});
</script>