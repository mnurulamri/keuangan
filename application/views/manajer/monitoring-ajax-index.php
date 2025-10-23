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

<!-- modal konfirmasi approval pengajuan -->
<div class="modal fade" id="modal-approval" tabindex="-1" role="dialog" aria-labelledby="viewApprovalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="approval-title">Persetujuan Pengajuan</h4>
            </div>
            <div class="modal-body" style="overflow:auto">
                <div id="data-approval">
                    <!-- Data approval akan dimuat di sini -->
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
fetch_data();
function fetch_data()  
{  
    page_num = 0;
    var keywords = "";
    var sortBy = "DESC";
    var status = $('#status').val(); //$("#status_pengaduan").val();
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>manajer/monitoring_ajax/data/0',
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
	var keywords = $('#keywords').val();
	var sortBy = $('#sortBy').val();

    $url = "<?=base_url().'manajer/monitoring_ajax/data/'?>"+page;

	$.ajax({
		method: "POST",
		url: $url,
		data: { page:page, keywords:keywords, sortBy:sortBy, status: $('#status').val() },
		success: function(data){
			$('#postList').html(data);
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
    // sebelum di approval, tampilkan data pengajuan melalui form konfirmasi
	$(document).on("click",".approval", function(){
        var id_monitoring= $(this).data("id_monitoring");
		var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
        var kode_dpsj = $(this).data("kode_dpsj");

        // set title modal approval
		$("#approval-title").html('<div class="text-info text-center"><strong>Proses Persetujuan Manajer Keuangan</strong></div>');

		// ambil data form edit pengajuan menggunakan AJAX
        $.ajax({
            url: "<?=base_url()?>index.php/manajer/monitoring/konfirmasiApproval",
            type: "POST",
            data: {id_monitoring:id_monitoring, id_pengajuan_pemohon: id_pengajuan_pemohon, kode_dpsj: kode_dpsj},
            success: function(response) {
                // Tampilkan pesan sukses atau lakukan tindakan lain
                //alert("Data berhasil disimpan!");
                $("#data-approval").html(response);
                console.log(response);
            },
            error: function(xhr, status, error) {
                // Tampilkan pesan kesalahan
                //alert("Terjadi kesalahan saat ...");
                //console.log(error);
            }
        });
	});

    // tampilkan data pengajuan yang di approval
	$(document).on("click",".detail", function(){
        var id_monitoring= $(this).data("id_monitoring");
		var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
        var kode_dpsj = $(this).data("kode_dpsj");

        // set title modal approval
		$("#approval-title").html('<div class="text-danger text-center"><strong>Data Pengajuan Sudah Disetujui Manajer Keuangan</strong></div>');

		// ambil data form edit pengajuan menggunakan AJAX
        $.ajax({
            url: "<?=base_url()?>index.php/korpum/monitoring/detailApproval",
            type: "POST",
            data: {id_monitoring:id_monitoring, id_pengajuan_pemohon: id_pengajuan_pemohon, kode_dpsj: kode_dpsj},
            success: function(response) {
                // Tampilkan pesan sukses atau lakukan tindakan lain
                //alert("Data berhasil disimpan!");
                $("#data-approval").html(response);
                console.log(response);
            },
            error: function(xhr, status, error) {
                // Tampilkan pesan kesalahan
                //alert("Terjadi kesalahan saat ...");
                //console.log(error);
            }
        });
	});

	$(document).on("click", ".view-catatan", function() {
		var id = $(this).data('id');
        console.log(id);
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