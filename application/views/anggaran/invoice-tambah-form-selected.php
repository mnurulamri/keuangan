
	            <div class="row text-center">
	               
                    <input type="hidden" id="tgl_select2" value="<?=$tgl?>" >
                    <input type="hidden" id="bulan_select2" value="<?=$bulan?>" >
                    <input type="hidden" id="tahun_select2" value="<?=$tahun?>" >
                    <input type="hidden" id="no_invoice_pp_select2" value="<?=$no_invoice_pp?>" >
                    <input type="hidden" id="uraian_select2" value="<?=$uraian?>" >
                    <input type="hidden" id="no_tiket_select2" value="<?=$no_tiket?>" >
                    
                    <table class="styled-table invoice-table" width="100%" style="display:nonex;">
                        
                            <tr style="background-color:#fff; color:#43A5BE">
                                <th>PERIODE</th> 
                                <th>NO INVOICE PP</th>
                                <th>URAIAN</th>
                            </tr>
                            <tr style="color:#696969">
                                <th><?=$head_invoice?></th>
                                <th><?=$no_invoice_pp?></th>
                                <th><?=$uraian?></th>
                            </tr>
                       
                    </table>
	            </div>
	            
	    <div class="row">
	        <div class="box col-md-12 col-lg-12">
	            <div class="box-body" style="overflow:auto">
	                <div id=data-procost>
                        
        				<div id="test_script2" style="display:none">cek data</div>
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
								if($row['form'] == 'D02'){
                                    echo '<td class="text-right">' . number_format($row['aktual']) . '</td>';    
									echo '<td class="text-right"><input type="text" class="form-control input-sm text-right tax-input" data-id="' . $row['id'] . '" data-bruto="' . $row['aktual'] . '" value="' . number_format($row['pph'], 0, ',', ',') . '" min="0">' . $row['id'] . '</td>';      
									echo '<td class="text-right"><input type="text" class="form-control input-sm text-right net-input" data-id="' . $row['id'] . '" value="' . number_format($row['aktual'], 0, ',', ',') . '" min="0" ></td>';   
								} else {
                                    echo '<td class="text-right">' . number_format($row['aktual']) . '</td>';    
									echo '<td class="text-right">' . number_format($row['pph'], 0, ',', ',') . '</td>';      
									echo '<td class="text-right">' . number_format($row['netto'], 0, ',', ',') . '</td>';   
								}    
                                echo '</tr>';   
                                $no++; 
                            }
                            ?>
                            </tbody>
                        </table>

                        <input type="hidden" value="<?= $id_pengajuan_pemohon ?>" id="id_pengajuan_pemohon_select" style="display:block">
                        <div class="text-center" style="margin-top:10px">
                            <button class="btn btn-primary" id="simpan-tambah-procost">Simpan Rekap Procost</button>
                            <button class="btn btn-primary" id="simpan-kendali-dokumen-procost" style="display:none">Simpan Kendali Dokumen</button>
                        </div>

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


	$(document).on("click","#simpan-tambah-procost", function()
	{
		// jika no_invoice_pp dan uraian kosong maka tampilkan alert
		//alert($("#no_tiket_select2").val()); return false;
		var no_tiket = $("#no_tiket_select2").val();
		var no_invoice_pp = $("#no_invoice_pp_select2").val();
		var uraian = $("#uraian_select2").val();
		var tahun = $("#tahun_select2").val();
		var bulan = $("#bulan_select2").val();
		var tgl = $("#tgl_select2").val();
		var id_pengajuan_pemohon = $("#id_pengajuan_pemohon_select").val();
		
        //alert('no_tiket'+no_tiket+' '+no_invoice_pp+' '+uraian+' '+tahun+' '+no_tiket+' '+bulan+' '+tgl+' '+id_pengajuan_pemohon); return false;
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
			url: "<?=base_url()?>index.php/invoice_update/simpan_procost",
			type: "POST",
			data: {id_pengajuan_pemohon:id_pengajuan_pemohon, no_invoice_pp:no_invoice_pp, uraian:uraian, tahun:tahun, bulan:bulan, tgl:tgl, no_tiket:$("#no_tiket_select2").val(),
				tax_data:taxData,
				net_data:netData},
			success: function(response) {
				// Tampilkan pesan sukses atau lakukan tindakan lain
				alert("Data berhasil disimpan!");
				location.href = "<?=base_url('invoice')?>";
				//$("#test_script2").html(response);
				//simpanKendaliDokumenProcost();
				console.log(response);
			},
			error: function(xhr, status, error) {
				// Tampilkan pesan kesalahan
				alert("Terjadi kesalahan saat ...");
				//console.log(error);
			}
		});
	});
	
	// untuk testing aja
	$(document).on("click","#simpan-kendali-dokumen-procost", function()
	{
	    simpanKendaliDokumenProcost();
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
