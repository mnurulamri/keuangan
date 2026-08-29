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
								// jika tanggal hari ini sama dengan $day maka beri atribut selected
								if($day == date('d')){
									echo "<option value='$day' selected>$day</option>";
								} else {
									echo "<option value='$day'>$day</option>";
								}
							}
							?>
						</select>
						<select name="bulan" id="bulan" class="form-control" >
						<?php
						for($m=1; $m<=12; $m++){
							$month = str_pad($m, 2, "0", STR_PAD_LEFT);
							// jika bulan hari ini sama dengan $month maka beri atribut selected
							$array_nama_bulan = array(
								"01" => "Januari",
								"02" => "Februari",
								"03" => "Maret",
								"04" => "April",
								"05" => "Mei",
								"06" => "Juni",
								"07" => "Juli",
								"08" => "Agustus",
								"09" => "September",
								"10" => "Oktober",
								"11" => "November",
								"12" => "Desember"
							);
							if($month == date('m')){
								echo "<option value='$month' selected>" . $array_nama_bulan[$month] . "</option>";
							} else {
								echo "<option value='$month'>" . $array_nama_bulan[$month] . "</option>";
							}
						}
						?>
						</select>
						<!--<input type="text" name="bulan" id="bulan" class="form-control" value="<?= $bulan ?>" >-->
	                    <input type="text" name="tahun" id="tahun" class="form-control" value="<?= $tahun ?>" readonly>
	                    
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
				<input type="text" id="no_tiket" value="" style="display:none">
			</div>
		</div>

	    <div class="row">
	        <div class="box col-md-12 col-lg-12">
	            <div class="box-body" style="overflow:auto">
	                <div id=data-procost>
                        
                        <table class="table" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NOMOR PENGAJUAN</th>
                                    <!--<th>NO INVOICE PP</th>-->
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
                            <?php
                            $no = 1;
                            foreach ($result as $row) {
                                echo '<tr>';
                                echo '<td>' . $no . '</td>';
                                echo '<td>' . $row['nomor_pengajuan'] . '</td>';
                                //echo '<td>' . $no_invoice_pp . '</td>';
                                echo '<td>' . $row['untuk'] . '</td>';
                                echo '<td>' . $row['kode_kegiatan'] . '</td>';
                                echo '<td>' . $row['kode_akun'] . '</td>';
                                echo '<td>' . $row['kode_dana'] . '</td>';
                                echo '<td>' . $row['deskripsi_dpsj'] . '</td>';
                                echo '<td class="text-right">' . number_format($row['aktual']) . '</td>';    
								if($row['form'] == 'D02'){
									echo '<td class="text-right"><input type="text" class="form-control input-sm text-right tax-input" data-id="' . $row['id'] . '" data-bruto="' . $row['aktual'] . '" value="' . number_format($row['pph'], 0, ',', ',') . '" min="0"></td>';      
									echo '<td class="text-right"><input type="text" class="form-control input-sm text-right net-input" data-id="' . $row['id'] . '" value="' . number_format($row['aktual'], 0, ',', ',') . '" min="0" ></td>';   
								} else {
									echo '<td class="text-right">' . number_format($row['pph'], 0, ',', ',') . '</td>';      
									echo '<td class="text-right">' . number_format($row['netto'], 0, ',', ',') . '</td>';   
								}    
                                echo '</tr>';   
                                $no++; 
                            }
                            ?>
                            </tbody>
                        </table>

                        <input type="text" value="<?= $id_pengajuan_pemohon ?>" id="id_pengajuan_pemohon" style="display:none">
                        <div class="text-center" style="margin-top:10px">
                            <button class="btn btn-primary" id="simpan-rekap-procost">Simpan Rekap Procost</button>
                        </div>

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
	// Currency formatting functions
	function formatCurrency(value) {
		return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}
	
	function parseCurrency(value) {
		return parseFloat(value.replace(/,/g, '')) || 0;
	}

	// Format initial values on page load
	$(".tax-input").each(function(){
		var value = $(this).val();
		if (value) {
			$(this).val(formatCurrency(value));
		}
	});
	
	$(".net-input").each(function(){
		var value = $(this).val();
		if (value) {
			$(this).val(formatCurrency(value));
		}
	});

	// Handle tax input with currency formatting
	$(document).on("focus", ".tax-input", function(){
		var value = $(this).val();
		var parsed = parseCurrency(value);
		$(this).val(parsed);
	});

	$(document).on("click", ".tax-input", function(){
		$(this).select();
	});

	$(document).on("blur", ".tax-input", function(){
		var value = $(this).val();
		var parsed = parseCurrency(value);
		$(this).val(formatCurrency(parsed));
		
		// Trigger calculation
		$(this).trigger('change');
	});

	// Calculate net = bruto - tax when tax input changes
	$(document).on("input change", ".tax-input", function(){
		var taxInput = $(this);
		var bruto = parseFloat(taxInput.data('bruto')) || 0;
		var tax = parseCurrency(taxInput.val()) || 0;
		var net = bruto - tax;
		
		// Update corresponding net input with formatted value
		var netInput = $(".net-input[data-id='" + taxInput.data('id') + "']");
		netInput.val(formatCurrency(net));
	});

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

		// Collect tax and net data (parse currency format before sending)
		var taxData = {};
		var netData = {};
		$(".tax-input").each(function(){
			var id = $(this).data('id');
			var tax = parseCurrency($(this).val());
			taxData[id] = tax;
		});
		$(".net-input").each(function(){
			var id = $(this).data('id');
			var net = parseCurrency($(this).val());
			netData[id] = net;
		});

		// ambil data pengajuan menggunakan AJAX
		$.ajax({
			url: "<?=base_url()?>index.php/invoice/simpan_rekap_procost",
			type: "POST",
			data: {id_pengajuan_pemohon:id_pengajuan_pemohon, no_invoice_pp:no_invoice_pp, uraian:uraian, tahun:tahun, bulan:bulan, tgl:tgl, no_tiket:$("#no_tiket").val(),
				tax_data:taxData,
				net_data:netData},
			success: function(response) {
				// Tampilkan pesan sukses atau lakukan tindakan lain
				alert("Data berhasil disimpan!");
				location.href = "../invoice";
				//$("#data-procost").html(response);
				simpanKendaliDokumenProcost()
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

function simpanKendaliDokumenProcost(){
	    //alert('test'); return false;
		var no_invoice_pp = $("#no_invoice_pp").val();
		var uraian = $("#uraian").val();
		var tahun = $("#tahun").val();
		var bulan = $("#bulan").val();
		var tgl = $("#tgl").val();
		var id_pengajuan_pemohon = $("#id_pengajuan_pemohon").val();
		
		// Collect tax and net data (parse currency format before sending)
		var taxData = {};
		var netData = {};
		$(".tax-input").each(function(){
			var id = $(this).data('id');
			var tax = parseCurrency($(this).val());
			taxData[id] = tax;
		});
		$(".net-input").each(function(){
			var id = $(this).data('id');
			var net = parseCurrency($(this).val());
			netData[id] = net;
		});

		// ambil data pengajuan menggunakan AJAX
		$.ajax({
			url: "<?=base_url()?>index.php/Kendali_dokumen/procost",
			type: "POST",
			data: {id_pengajuan_pemohon:id_pengajuan_pemohon, no_invoice_pp:no_invoice_pp, uraian:uraian, tahun:tahun, bulan:bulan, tgl:tgl, no_tiket:$("#no_tiket").val(),
				tax_data:taxData,
				net_data:netData},
			success: function(response) {
				// Tampilkan pesan sukses atau lakukan tindakan lain
				//$("#data-pengajuan").html(response);
				//alert("Data berhasil disimpan!");
				//location.href = "../invoice";
				//$("#data-procost").html(response);
				$("#test_script").html(response);
				console.log(response);
			},
			error: function(xhr, status, error) {
				// Tampilkan pesan kesalahan
				alert("Terjadi kesalahan saat ...");
				//console.log(error);
			}
		});
}
</script>