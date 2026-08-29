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
	    <div class="row text-center ">
	        <div class="post-search-panel">
	            <input type="text" id="keywords" placeholder="Filter Nomor Pengajuan" onkeyup="searchFilter()" style="border:1px solid #ccc; padding:5px; width: 20%; border-radius: 5px;"/>
	                    
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
	            <input type="hidden" id="temp_page" value="">
	            <!--<input type="text" id="keywords" placeholder="Type keywords to filter posts"/>
	            <select id="sortBy" onchange="searchFilter()">
	                <option value="">Sort By</option>
	                <option value="asc">Ascending</option>
	                <option value="desc">Descending</option>
	            </select>
	            
			<a href="#panduan" class="label label-danger" style="font-size:14px"><strong>Panduan</strong></a>-->
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
				<button type="button" class="btn btn-warning close-modal-realisasi" data-dismiss="modal" aria-label="Close">Tutup</button>
			</div>
		</div>
	</div>
</div>

<script>
fetch_data()

function getDataPage(page){              
	var keywords = $('#keywords').val();
	var sortBy = $('#sortBy').val();
	var string_path = window.location.pathname;
	var last_string_path = string_path.slice(-1);
	if(last_string_path == '/'){
		$url = 'data/'+page;
	} else {
		$url = 'realisasi_ajax/data/'+page;
	}
	$.ajax({
		method: "POST",
		url: $url,
		data: { page:page, keywords:keywords, sortBy:sortBy },
		success: function(data){
			$('#postList').html(data);
            $('.loading-overlay').fadeOut("slow");
		}
	});
}

function searchFilter() {
	var keywords = $('#keywords').val();
	var sortBy = $('#sortBy').val();
	var page = 0; // reset to first page
	getDataPage(page);
}

function fetch_data()  
{  
    page_num = 0;
    var keywords = "";
    var sortBy = "DESC";
    var status = '0'; //$("#status_pengaduan").val();
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>realisasi_ajax/data/0',  //url: '<?php echo base_url(); ?>koordinator/ajax/'+page_num,
        data:'page='+page_num+'&keywords='+keywords+'&sortBy='+sortBy,
        beforeSend: function () {
            $('.loading-overlay').show();
        },
        success: function (html) {
            $('#postList').html(html);
            $('.loading-overlay').fadeOut("slow");
        }
    });
}

$(document).ready(function()
{
	$(document).on("click", ".buat-realisasi", function() {
		var id = $(this).data('id');
		var nomor_pengajuan = $(this).data('nomor_pengajuan');
		var nominal_pengajuan = $(this).data('nominal_pengajuan');		
		var keterangan = $(this).data('keterangan');

		// Load the form for creating a new realisasi
		$.ajax({
			url: '<?php echo base_url(); ?>realisasi/add',
			type: 'POST',
			data: {keterangan:keterangan, id:id, nomor_pengajuan: nomor_pengajuan, nominal_pengajuan: nominal_pengajuan},
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

	$(document).on("click", ".view-realisasi", function() {
		var id = $(this).data('id');
		var nomor_pengajuan = $(this).data('nomor_pengajuan');
		// Load the form for creating a new realisasi
		$.ajax({
			url: '<?php echo base_url(); ?>realisasi/view',
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

	$(document).on("click", ".edit-realisasi", function(e) {
		var id = $(this).data('id');
		var nomor_pengajuan = $(this).data('nomor_pengajuan');
		var nominal_pengajuan = $(this).data('nominal_pengajuan');

		// Load the form for creating a new realisasi
		$.ajax({
			url: '<?php echo base_url(); ?>realisasi/edit',
			type: 'POST',
			data: {id:id, nomor_pengajuan: nomor_pengajuan, nominal_pengajuan: nominal_pengajuan},
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

	$(document).on("click", ".edit-spj", function(e) {

		$('#data-realisasi').html('');
		var id = $(this).data('id');
		var nomor_pengajuan = $(this).data('nomor_pengajuan');
		var nominal_pengajuan = $(this).data('nominal_pengajuan');

		// Load the form for creating a new spj
		$.ajax({
			url: '<?php echo base_url(); ?>spj/edit_view',
			type: 'POST',
			data: {id:id, nomor_pengajuan: nomor_pengajuan, nominal_pengajuan: nominal_pengajuan},
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

	$(document).on("click", ".ajukan-realisasi", function(e) {

		var id_pengajuan_pemohon = $(this).data('id_pengajuan_pemohon');

		// Load the form for creating a new realisasi
		$.ajax({
			url: '<?php echo base_url(); ?>realisasi/ajukan_konfirmasi',
			type: 'POST',
			data: {id_pengajuan_pemohon:id_pengajuan_pemohon},
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

	$(document).on("click", "#ajukan", function(e) {

		var id_pengajuan_pemohon = $(this).data('id_pengajuan_pemohon');
		var tgl_diajukan = $("#user_tanggal").val();

		// Load the form for creating a new realisasi
		$.ajax({
			url: '<?php echo base_url(); ?>realisasi/ajukan',
			type: 'POST',
			data: {id_pengajuan_pemohon:id_pengajuan_pemohon},
			beforeSend: function() {
				$('.loading-overlay').show();
			},
			success: function(data) {
				$('#data-realisasi').html(data);
				$('.loading-overlay').fadeOut("slow");
				$('#status_'+id_pengajuan_pemohon).html("Menunggu Verifikasi Kasir");

				// kendali dokumen
                $.ajax({
                    url: '<?=base_url("Kendali_dokumen/pum")?>',
                    type: 'POST',
                    data: {
            			id_pengajuan_pemohon: id_pengajuan_pemohon, nama_form: 'D01', tgl_diajukan: tgl_diajukan
                    },
                    //dataType: 'json',
                    success: function(res) {
                        alert('Catatan berhasil disimpan.');
						$("#button-ajukan-"+id_pengajuan_pemohon).prop('disabled', true);
						$("#button-spj-"+id_pengajuan_pemohon).prop('disabled', true);
                        console.log(res);
                    },
                    error: function() {
                        alert('Terjadi kesalahan saat menyimpan catatan.');
                    }
                });
			},
			error: function() {
				alert('Error loading form for creating realisasi.');
				$('.loading-overlay').fadeOut("slow");
			},
		});
	});

	$(document).on("click", ".total-biaya-excel", function() {
		var id = $(this).data('id_pengajuan_pemohon');

		post_to_url("<?=base_url('Rekap_TotalBiaya')?>", {id:id}, 'post');
	});

	$(document).on("click", ".rincian-biaya-excel", function() {
		var id = $(this).data('id');
		var nomor_pengajuan = $(this).data('nomor_pengajuan');

		post_to_url("<?=base_url('Rekap_PerJenisBiaya')?>", {id:id, nomor_pengajuan:nomor_pengajuan}, 'post');
	});

	$(document).on("click", ".rekap-realisasi-excel", function() {
		var id = $(this).data('id_pengajuan_pemohon');
		var nomor_pengajuan = $(this).data('nomor_pengajuan');

		post_to_url("<?=base_url('Rekap_RealisasiUmko')?>", {id:id, nomor_pengajuan:nomor_pengajuan}, 'post');
	});
});

function post_to_url(path, params, method) {
	method = method || "post";

	var form = document.createElement("form");
	form.setAttribute("method", method);
	form.setAttribute("action", path);

	for(var key in params) {
		if(params.hasOwnProperty(key)) {
			var hiddenField = document.createElement("input");
			hiddenField.setAttribute("type", "hidden");
			hiddenField.setAttribute("name", key);
			hiddenField.setAttribute("value", params[key]);

			form.appendChild(hiddenField);
			}
	}

	document.body.appendChild(form);
	form.submit();
}
</script>


<style type="text/css">

/* Tables */
table#tabel tr td, th {
	border:1px solid gray;
}

#tabel {
    font-family: Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 90%;
    margin:auto;
}

#tabel td, #tabel th {
    border: 1px solid #ddd;
    padding-top: 5px;
    padding-bottom: 5px;
    padding-left: 5px;
    padding-right: 5px;
    font-size:12px;
}

#tabel th{text-align:center}
#tabel tr:nth-child(even){background-color: #f2f2f2;}
#tabel tr:nth-child(odd){background-color: #fff;}

#tabel tr:hover {background-color: #ddd;}

#tabel th {
    padding-top: 2px;
    padding-bottom: 2px;
    text-align: center;
    vertical-align: middle;
    background-color: #43beaa;
    color: white;
}

.kalban, .bimbingan {
	cursor:pointer;
	text-align:right;
}

table#tabel tr td.total {
	text-align:right !important;
	font-weight: bold !important;
	background: #BCFF00 !important;
}

.space {
	background-color:#fff;
}

/* hovertext */
.hovertext {
  position: relative;
  border-bottom: 1px dotted black;
}

.hovertext:before {
  content: attr(data-hover);
  visibility: hidden;
  opacity: 0;
  width: 100px;
  background-color: black;
  color: #fff;
  text-align: center;
  border-radius: 5px;
  padding: 5px 0;
  transition: opacity 1s ease-in-out;

  position: absolute;
  z-index: 1;
  left: -50px;
  top: 110%;
}

.hovertext:hover:before {
  opacity: 1;
  visibility: visible;
}

/* hovertext rekap realisasi */
.hovertext-realisasi {
  position: relative;
  border-bottom: 1px dotted black;
}

.hovertext-realisasi:before {
  content: attr(data-hover);
  visibility: hidden;
  opacity: 0;
  width: 100px;
  background-color: #C21E56;
  color: #fff;
  text-align: center;
  border-radius: 5px;
  padding: 5px 0;
  transition: opacity 1s ease-in-out;

  position: absolute;
  z-index: 1;
  left: -50px;
  top: 110%;
}

.hovertext-realisasi:hover:before {
  opacity: 1;
  visibility: visible;
}
</style>