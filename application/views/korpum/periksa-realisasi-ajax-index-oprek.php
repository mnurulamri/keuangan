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
	            <input type="text" id="keywords" placeholder="cari deskripsi.." onkeyup="searchFilter()"/>
	                    
				<?php //$select_status?>
	
				<!-- 
	            <select id="status_pengaduan" onchange="searchFilter()">              
	                <option value="Semua" selected >Semua Status</option>     
	                <option value="Menunggu Konfirmasi">Menunggu Konfirmasi</option>               
	                <option value="Diterima">Diterima</option>              
	                <option value="Dalam Proses">Dalam Proses</option>                       
	                <option value="Ditunda">Ditunda</option>                            
	                <option value="Ditolak">Ditolak</option>
	                <option value="Selesai">Selesai</option>
	            </select>
				-->
	            <input type="text" id="temp_page" value="">
	            <!--<input type="text" id="keywords" placeholder="Type keywords to filter posts"/>
	            <select id="sortBy" onchange="searchFilter()">
	                <option value="">Sort By</option>
	                <option value="asc">Ascending</option>
	                <option value="desc">Descending</option>
	            </select>
	            -->
			<a href="#panduan" class="label label-danger" style="font-size:14px"><strong>Panduan</strong></a>
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
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="catatan-title">Catatan</h4>
			</div>
			<div class="modal-body" style="overflow:auto;">
				<div id="data-rincian" style="font-size:12px; font-family:Arial; text-align:center;"></div>
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
        url: '<?php echo base_url(); ?>korpum/periksa_realisasi_ajax/data/0',  //url: '<?php echo base_url(); ?>koordinator/ajax/'+page_num,
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
			url: '<?php echo base_url(); ?>verifikator/monitoring/view',
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
			url: '<?php echo base_url(); ?>korpum/periksa_realisasi/periksa',
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
			url: '<?php echo base_url(); ?>verifikator/monitoring/viewCatatan',
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

	$(document).on("click", ".fetch-logs", function() {
		var nomor_pengajuan = $(this).data('nomor_pengajuan');
        var tgl_terima = $(this).parent().parent().find('td').eq(0).text();
        var unit_pemohon = $(this).parent().parent().find('td').eq(2).text();
        var uraian = $(this).parent().parent().find('td').eq(4).text();
        var nominal_pengajuan = $(this).parent().parent().find('td').eq(5).text();
        var no_pp = $(this).data('no_pp');
        
		$('#data-catatan').html("loading...");

		// Load the form for viewing catatan
		$.ajax({
			url: '<?php echo base_url(); ?>kendali_dokumen/fetch_logs',
			type: 'POST',
			data: {nomor_pengajuan:nomor_pengajuan, tgl_terima:tgl_terima, unit_pemohon:unit_pemohon, uraian:uraian, nominal_pengajuan:nominal_pengajuan, no_pp:no_pp},
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

	$(document).on("click", ".lanjut-proses", function() {
		//set variabel
        var id_monitoring = $(this).data('id_monitoring');
        var id_pengajuan_pemohon = $(this).data('id_pengajuan_pemohon');
		var i = 0;

		// ambil data realisasi dari tabel
		var id = $(this).closest('tr').next('tr').find('td.realisasi').map(function() {
			return $(this).data("id");
		}).get().join(', ');
		var realisasi = $(this).closest('tr').next('tr').find('td.realisasi').map(function() {
			return $(this).text();
		}).get().join(', ');

		// ambil data rincian dari tabel
		var data_rincian_asli = $(this).closest('tr').next('tr').find('.detail-row-rincian');
		var data_rincian = data_rincian_asli.clone();  // kloning elemen untuk dimanipulasi agar tidak merubah data aslinya
		// bersihkan data rincian dari tombol periksa dan view
		data_rincian.find('tr').each(function() {
        	// Hapus dua sel terakhir dari setiap baris
			$(this).find('td:last-child, th:last-child').remove();
			$(this).find('td:last-child, th:last-child').remove();
    	});

		var $tableContent = data_rincian_asli.clone();

		// Hapus kolom Rincian Biaya
		$tableContent.find('tr').each(function() {
			var $row = $(this);
			
			// Header (baris pertama)
			if ($row.find('th[colspan="2"]').length) {
				$row.find('th[colspan="2"]').remove(); // Hapus header "Rincian Biaya"
			}
			
			// Baris data
			$row.find('td:last-child').remove(); // Hapus tombol view
			$row.find('td:last-child').remove(); // Hapus tombol Periksa
			// Perbaiki colspan pada baris TOTAL
        	$tableContent.find('tr:last-child td[colspan="2"]').attr('colspan', '0').empty();
		});

		var $table = data_rincian_asli.clone();
		$table.find('#tabel tr').each(function(index) {
    	var $row = $(this);
    
			// Baris pertama (header)
			if (index === 0) {
				//$row.find('th').eq(-1).remove(); // Hapus kolom terakhir
				$row.find('th').eq(-1).remove(); // Hapus kolom kedua dari terakhir
			}
			// Baris data
			else if ($row.hasClass('data-row-realisasi')) {
				$row.find('td').eq(-1).remove(); // Hapus tombol view
				$row.find('td').eq(-1).remove(); // Hapus tombol Periksa
			}
			// Baris TOTAL (terakhir)
			else if (index === $table.find('#tabel tr').length - 1) {
				//$row.find('td').eq(-1).remove();
				//$row.find('td').eq(-1).remove();
			}
		});

		//data_rincian = $tableContent.html();
		$("#data-rincian").html($table);
		console.log($table); return false;
		/*
		var cleanedHTML = data_rincian
		.replace(/<th[^>]*colspan="2"[^>]*>Rincian Biaya<\/th>/gi, '') // Hapus header
		.replace(/<td[^>]*>.*?periksa-realisasi.*?<\/td>/gi, '') // Hapus tombol Periksa
		.replace(/<td[^>]*>.*?view-realisasi.*?<\/td>/gi, ''); // Hapus tombol view

		// Update DOM
		var result_rincian = $(this).closest('tr').next('tr').find('.detail-row-rincian').html(cleanedHTML);
		//return false;
		*/
		//$("#data-rincian").html(data_rincian);
		//$("<div id='data-rincian'></div>").appendTo("#data-rincian");
		//console.log(cleanedHTML); 
		/*var realisasi = '';
		var testing = []; var test = '[';
		$(this).closest('tr').next('tr').find('td.realisasi').each(function(index, value) {
			realisasi += "["+$(this).data("id") + "] = " + $(this).text() + ", ";
		});*/
		//$(this).closest('tr').next('tr').find('tr.data-row-realisasi').each(function(index, value) {
			//realisasi.push($(this).text());
			//$(this).find('td.kode_kegiatan').each(function(idx, val) {
				//console.log("  Column " + idx + ": " + $(this).text());
			//});
			//$(this).find('td.realisasi').each(function(idx, val) {
				//console.log("  Column " + idx + ": " + $(this).text());
			//});
			//masukkan ke dalam array dengan key kode_kegiatan dan nilai realisasi
				//realisasi[$(this).find('td.kode_kegiatan').text()] = $(this).find('td.realisasi').text();
				//testing[i] = "{" + $(this).find('td.kode_kegiatan').text() + ": " + $(this).find('td.realisasi').text() + "}";
				//testing[i] = {'id': $(this).find('td.realisasi').data("id"), 'realisasi': $(this).find('td.realisasi').text()};
				//test+= "["+'id'+"=>"+$(this).find('td.realisasi').data("id")+", 'realisasi'=>"+$(this).find('td.realisasi').text()+"],";
				//testing[$(this).find('td.realisasi').data("id")] = $(this).find('td.realisasi').text();
			//i++;
			
			//console.log(realisasi);
		//});
		//test+= "['id'=>0, 'realisasi'=>0]]";
		// masukkan ke dalam array
		//var arrayRealisasi = realisasi.split(', ');
		//var arrayRealisasi = realisasi.split(', ').filter(function(item) {
		//	return item.trim() !== '';
		//});

		console.log("id: " + id + "Realisasi: " + realisasi);
		// Load the form for viewing catatan
		$('#data-catatan').html("loading...");
		$.ajax({
			url: '<?php echo base_url(); ?>korpum/periksa_realisasi/konfirmasiLanjutProses',
			type: 'POST',
			data: {id_monitoring:id_monitoring,id_pengajuan_pemohon:id_pengajuan_pemohon, realisasi:realisasi, id:id},
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
});
</script>

<?php include(APPPATH.'views/style_table.php') ?>