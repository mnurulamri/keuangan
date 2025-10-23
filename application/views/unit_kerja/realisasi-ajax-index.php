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
        url: '<?php echo base_url(); ?>realisasi_ajax/data/0',  //url: '<?php echo base_url(); ?>koordinator/ajax/'+page_num,
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

$(document).ready(function()
{
	$(document).on("click", ".buat-realisasi", function() {
		var id = $(this).data('id');
		var nomor_pengajuan = $(this).data('nomor_pengajuan');
		var nominal_pengajuan = $(this).data('nominal_pengajuan');

		// Load the form for creating a new realisasi
		$.ajax({
			url: '<?php echo base_url(); ?>realisasi/add',
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
});
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
    background-color: #4CAF50;
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

</style>

<style>
.styled-table {
   border-collapse: collapse;
   margin: 25px 0;
   font-size:12px;
   font-family: Arial, Helvetica, sans-serif;
   min-width: 400px;
   box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
}
.styled-table thead tr {
   background-color: #009879;
   color: #ffffff;
   text-align: left;
   font-size:1em;
}
.styled-table th {
   padding: 7px 15px;
}
.styled-table td {
   padding: 7px 15px;
}
.styled-table tbody tr {
   border-bottom: 1px solid #dddddd;
}
.styled-table tbody tr:nth-of-type(even) {
   background-color: #f3f3f3;
}
.styled-table tbody tr:last-of-type {
   border-bottom: 2px solid #009879;
}
.styled-table tbody tr.active-row {
   font-weight: bold;
   color: #009879;
}
</style>