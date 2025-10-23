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
	                
	                <div class="loading-overlayx" style="display:none"><div class="overlay-content">Loading.....</div></div>
	                <div class="post-list" id="postList"></div>
	                <div class="loading-overlay" style="display: none;"><div class="content"><i class="fa fa-spinner fa-spin"></i></div></div>
	            
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

<script>
fetch_data()
function fetch_data()  
{  
    page_num = 0;
    var keywords = "";
    var sortBy = "DESC";
    var status = $('#status').val(); //$("#status_pengaduan").val();
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>verifikator/monitoring_ajax/data/0',  //url: '<?php echo base_url(); ?>koordinator/ajax/'+page_num,
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

    $url = "<?=base_url().'verifikator/monitoring_ajax/data/'?>"+page;

	$.ajax({
		method: "POST",
		url: $url,
		data: { page:page, keywords:keywords, sortBy:sortBy, status: $('#status').val() },
        beforeSend: function () {
            $('.loading-overlay').fadeIn();
        },
		success: function(data){
			$('.loading-overlay').fadeOut();
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
			url: '<?php echo base_url(); ?>verifikator/monitoring/periksa',
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
});
</script>