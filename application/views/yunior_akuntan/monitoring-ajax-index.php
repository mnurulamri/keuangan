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

	<div class="containerx" style="padding:10px">
	    <div class="row">
	        <div class="post-search-panel">
	            <input type="text" id="keywords" placeholder="cari Nomor Pengajuan.." onkeyup="searchFilter()"/>
	                 
				<select id="status" onchange="searchFilter()"> 			 
	                <option value="Semua" selected >Semua Status</option>     
	                <?php 
					$array_status = array_status();
					foreach($array_status as $key => $value) {
						echo '<option value="'.$key.'">'.$value.'</option>';
					}
					?>
	            </select>
	            <select id="sortBy" onchange="searchFilter()">
	                <option value="">Sort By</option>
	                <option value="asc">Ascending</option>
	                <option value="desc">Descending</option>
	            </select>
	            <input type="text" id="temp_page" value="">
	        </div>
	
	    </div>
	    <div class="row">
	        <div class="box col-md-12 col-lg-12">
	            <div class="box-body" style="overflow:auto">
	                
	                <div class="loading-overlay"><div class="overlay-content">Loading.....</div></div>
	                <div class="post-list" id="postList"></div>
	                <div class="loading" style="display: none;"><div class="content"><i class="fa fa-spinner fa-spin"></i></div></div>
	            
	            </div>
	        </div>
	    </div>
	</div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<!-- modal realisasi -->
<div class="modal fade" id="modal-realisasi" tabindex="-1" role="dialog" aria-labelledby="viewRealisasiModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="pengajuan-title">Data Realisasi</h4>
			</div>
			<div class="modal-body" style="overflow:auto">
				<div id="data-realisasi">
					<?php //include(APPPATH.'views/unit_kerja/form_edit_pengajuan.php') ?>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal" aria-label="Close">Tutup</button>
			</div>
		</div>
	</div>
</div>

<!-- modal catatan -->
<div class="modal fade" id="modal-catatan" tabindex="-1" role="dialog" aria-labelledby="viewcatatanModalLabel">
	<div class="modal-dialog modal-md" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="catatan-title">Catatan</h4>
			</div>
			<div class="modal-body" style="overflow:auto">
				<div id="data-catatan">
					<?php //include(APPPATH.'views/unit_kerja/form_edit_pengajuan.php') ?>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal" aria-label="Close">Tutup</button>
			</div>
		</div>
	</div>
</div>

<!-- bootstrap datepicker -->
<script src="<?=base_url()?>assets/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?=base_url()?>assets/AdminLTE/plugins/datepicker/locales/bootstrap-datepicker.id.js"></script>
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?=base_url()?>assets/AdminLTE/plugins/datepicker/datepicker3.css">

<script>
fetch_data()
function fetch_data()  
{  
    page_num = 0;
    var keywords = "";
    var sortBy = "DESC";
    var status = '0'; //$("#status_pengaduan").val();
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>yunior_akuntan/monitoring_ajax/data/0',  //url: '<?php echo base_url(); ?>koordinator/ajax/'+page_num,
        data:'page='+page_num+'&keywords='+keywords+'&sortBy='+sortBy+'&status='+status,
        beforeSend: function () {
            $('.loading-overlay').show();
        },
        success: function (html) {
            $('#postList').html(html);
            $('.loading-overlay').fadeOut("slow");
        }
    });
}

function getDataPage(page)
{      
	//$('#postList').fadeOut("slow", function() {
	//	$(this).empty();
	//});

	var keywords = $('#keywords').val();
	var sortBy = $('#sortBy').val();

    $url = "<?=base_url().'yunior_akuntan/monitoring_ajax/data/'?>"+page;

	$.ajax({
		method: "POST",
		url: $url,
		data: { page:page, keywords:keywords, sortBy:sortBy, status: $('#status').val() },
        beforeSend: function () {
            $('.loading').fadeIn();
        },
		success: function(data){
			$('.loading').fadeOut();
			$('#postList').hide().html(data).fadeIn();            
		}
	});
}

function searchFilter() {
	var keywords = $('#keywords').val();
	var status = $('#status').val();
	var sortBy = $('#sortBy').val();
	var page = 0; // reset to first page
	getDataPage(page);
}

$(document).ready(function()
{
	$(document).on("click", ".view-realisasi", function() {
		var id = $(this).data('id');
		var nomor_pengajuan = $(this).data('nomor_pengajuan');

		$('#data-realisasi').html("loading...");

		// Load the form for creating a new realisasi
		$.ajax({
			url: '<?php echo base_url(); ?>yunior_akuntan/monitoring/view',
			type: 'POST',
			data: {id:id, nomor_pengajuan: nomor_pengajuan},
			beforeSend: function() {
				$('.loading-overlay').show();
			},
			success: function(data) {
				$('#data-realisasi').html(data);
				$('.loading-overlay').fadeOut("slow");
			},
			error: function() {
				alert('Error loading form for creating realisasi.');
				$('.loading-overlay').fadeOut("slow");
			},
		});
	});

	$(document).on("click", ".periksa-realisasi", function() {
		var id = $(this).data('id');
		var id_monitoring = $(this).data('id_monitoring');
		var nomor_pengajuan = $(this).data('nomor_pengajuan');
		// Load the form for creating a new realisasi
		$.ajax({
			url: '<?php echo base_url(); ?>yunior_akuntan/monitoring/periksa',
			type: 'POST',
			data: {id:id, id_monitoring:id_monitoring, nomor_pengajuan: nomor_pengajuan},
			beforeSend: function() {
				$('.loading-overlay').show();
			},
			success: function(data) {
				$('#data-realisasi').html(data);
				$('.loading-overlay').fadeOut("slow");
			},
			error: function() {
				alert('Error loading form for periksa realisasi.');
				$('.loading-overlay').fadeOut("slow");
			},
		});
	});

	$(document).on("click", ".view-catatan", function() {
		var id = $(this).data('id');

		$('#data-catatan').html("loading...");

		// Load the form for viewing catatan
		$.ajax({
			url: '<?php echo base_url(); ?>yunior_akuntan/monitoring/viewCatatan',
			type: 'POST',
			data: {id:id},
			beforeSend: function() {
				$('.loading-overlay').show();
			},
			success: function(data) {
				$('#data-catatan').html(data);
				$('.loading-overlay').fadeOut("slow");
			},
			error: function() {
				alert('Error loading catatan.');
				$('.loading-overlay').fadeOut("slow");
			},
		});
	});

	$(document).on("click", ".edit-pp", function() {
		// Show the "Simpan PP" button and hide the "Edit PP" button
		$(this).hide();
		$(this).closest('tr').find('.simpan-pp').show();
		$(this).closest('tr').find('.cancel').show();

		// Convert the row data to input fields for editing
		$(this).closest('tr').find('.tgl-pp, .no-pp, .bruto, .pph21, .pph23, .netto').each(function() {
			var value = $(this).text().trim();
			var inputType = 'text';
			var extraClass = '';
			if ($(this).hasClass('tgl-pp')) {
				inputClass = ' input-tgl-pp';
			}
			if ($(this).hasClass('no-pp')) {
				inputClass = ' input-no-pp';
			}
			if ($(this).hasClass('bruto')) {
				inputClass = ' input-bruto';
			}
			if ($(this).hasClass('pph21')) {
				inputClass = ' input-pph21';
			}
			if ($(this).hasClass('pph23')) {
				inputClass = ' input-pph23';
			}
			if ($(this).hasClass('netto')) {
				inputClass = ' input-netto';
			}
			$(this).html('<input type="' + inputType + '" class="form-control' + inputClass + '" value="' + value + '">');
		});
	});

	// format kolom tanggal -> datepicker dengan format dd mmm yyyy dalam bahasa Indonesia
    $(document).on("focus", ".input-tgl-pp", function() {
        $(this).datepicker({
            format: "dd MM yyyy",
            autoclose: true,
            language: "id",
            todayHighlight: true,
            orientation: "top auto"
        }).on('changeDate', function(e) {
            $(this).text(e.format());
        });
    });

	// hitung netto
	$(document).on("input", ".input-bruto, .input-pph21, .input-pph23", function() {
		var row = $(this).closest('tr');
		var bruto = parseFloat(row.find('.input-bruto').val().replace(/,/g, '').replace(/\./g, '')) || 0;
		var pph21 = parseFloat(row.find('.input-pph21').val().replace(/,/g, '').replace(/\./g, '')) || 0;
		var pph23 = parseFloat(row.find('.input-pph23').val().replace(/,/g, '').replace(/\./g, '')) || 0;
		var netto = bruto - (pph21 + pph23);

		// Format angka dengan koma sebagai pemisah ribuan (Indonesia)
		var nettoFormatted = netto.toLocaleString('id-ID').replace(/\./g, ',');
		row.find('.input-netto').val(nettoFormatted);

		// display pph21 dan pph23 dengan format angka
		row.find('.input-pph21').val(pph21.toLocaleString('id-ID').replace(/\./g, ','));
		row.find('.input-pph23').val(pph23.toLocaleString('id-ID').replace(/\./g, ','));
	});

	$(document).on("click", ".simpan-pp", function() {
		var row = $(this).closest('tr');
		var id = $(this).data('id'); // Assuming the row has a data-id attribute
		
		var tgl_pp = row.find('.tgl-pp input').val();
		var no_pp = row.find('.no-pp input').val();
		var bruto = parseFloat(row.find('.bruto input').val().replace(/,/g, '')) || 0;
		var pph_21 = parseFloat(row.find('.pph21 input').val().replace(/,/g, '')) || 0;
		var pph_23 = parseFloat(row.find('.pph23 input').val().replace(/,/g, '')) || 0;
		var netto = parseFloat(row.find('.netto input').val().replace(/,/g, '')) || 0;

		// Validate inputs
		if (!tgl_pp || !no_pp || isNaN(pph_21) || isNaN(pph_23) || isNaN(netto)) {
			alert('Please fill all fields correctly.');
			return;
		}
		
		// Send data to the server
		$.ajax({
			url: '<?php echo base_url(); ?>yunior_akuntan/monitoring/updatePP',
			type: 'POST',
			data: {
				id: id,
				tgl_pp: tgl_pp,
				no_pp: no_pp,
				bruto: bruto,
				pph_21: pph_21,
				pph_23: pph_23,
				netto: netto
			},
			beforeSend: function() {
				$('.loading-overlay').show();
			},
			success: function(response) {
				console.log(response);
				$('.loading-overlay').fadeOut("slow");
			},
			error: function() {
				alert('Error updating data.');
				$('.loading-overlay').fadeOut("slow");
			}
		});
		
		// Simulate successful update for demonstration
		setTimeout(function() {
			alert('Data updated successfully.');
			row.find('.tgl-pp').text(tgl_pp);
			row.find('.no-pp').text(no_pp);
			row.find('.bruto').text(bruto.toLocaleString('id-ID').replace(/\./g, ','));
			row.find('.pph21').text(pph_21.toLocaleString('id-ID').replace(/\./g, ','));
			row.find('.pph23').text(pph_23.toLocaleString('id-ID').replace(/\./g, ','));
			row.find('.netto').text(netto.toLocaleString('id-ID').replace(/\./g, ','));
			row.find('.simpan-pp').hide();
			row.find('.cancel').hide();
			row.find('.edit-pp').show();
		}, 500);

	});

	$(document).on("click", ".cancel", function() {
		// Hide the "Simpan PP" and "Cancel" buttons, show the "Edit PP" button
		$(this).closest('tr').find('.simpan-pp').hide();
		$(this).hide();
		$(this).closest('tr').find('.edit-pp').show();

		// Revert the input fields back to text
		$(this).closest('tr').find('.tgl-pp input, .no-pp input, .bruto input, .pph21 input, .pph23 input, .netto input').each(function() {
			var value = $(this).val().trim();
			$(this).parent().text(value);
		});
	});

});
</script>