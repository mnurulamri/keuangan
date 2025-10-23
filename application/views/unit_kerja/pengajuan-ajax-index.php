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
    <!-- Konten utama Anda di sini
	<input type="text" id="temp_page" value="0" style="display:nonex"> -->
	<input type="hidden" id="temp_page" value="">
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
				<?php 
				//print_r(array_status());
				?>
	
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

<!-- modal edit dan konfirmasi form pengajuan -->
<div class="modal fade" id="modal-ajukan" tabindex="-1" role="dialog" aria-labelledby="viewAjukanLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="ajukan-title"></h4>
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
<!-- modal edit form pengajuan
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
 -->
<!-- modal edit form pengajuan
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
 -->

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
        url: '<?php echo base_url(); ?>pengajuan_ajax/data/0',  //url: '<?php echo base_url(); ?>koordinator/ajax/'+page_num,
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

function getDataPage(page){              
	var keywords = $('#keywords').val();
	var sortBy = $('#sortBy').val();
	var string_path = window.location.pathname;
	var last_string_path = string_path.slice(-1);
	if(last_string_path == '/'){
		$url = 'data/'+page;
	} else {
		$url = 'pengajuan_ajax/data/'+page;
	}
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
	// sebelum diajukan, tampilkan data pengajuan melalui form konfirmasi
	$(document).on("click",".ajukan", function(){
		var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
		var kode_dpsj = $(this).data("kode_dpsj");
		var deskripsi_dpsj = $(this).data("deskripsi_dpsj");
		$("#ajukan-title").text("Anda akan mengajukan data pengajuan sebagai berikut:");

		// ambil data form edit pengajuan menggunakan AJAX
        $.ajax({
            url: "<?=base_url()?>index.php/pengajuan_daftar/formKonfirmasiAjukan",
            type: "POST",
            data: {id_pengajuan_pemohon: id_pengajuan_pemohon, kode_dpsj: kode_dpsj, deskripsi_dpsj: deskripsi_dpsj},
            success: function(response) {
                // Tampilkan pesan sukses atau lakukan tindakan lain
                //alert("Data berhasil disimpan!");
                $("#data-ajukan").html(response);
                console.log(response);
            },
            error: function(xhr, status, error) {
                // Tampilkan pesan kesalahan
                //alert("Terjadi kesalahan saat ...");
                //console.log(error);
            }
        });
	});

	// setelah melihat form konfirmasi, klik tombol ajukan untuk mengajukan pengajuan
	$(document).on("click", "#ajukan", function() {
		var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
		var kode_dpsj = $("#kode_dpsj").text();
		var untuk = $("#untuk").val();
		var nominal_pengajuan = $(this).data("nominal_pengajuan");
		var page = $("#temp_page").val(); // ambil halaman saat ini

		// kirim data ke server untuk mengajukan pengajuan
		$.ajax({
			url: "<?=base_url()?>index.php/pengajuan_daftar/ajukan",
			type: "POST",
			datatype: "json",
			data: {
				id_pengajuan_pemohon: id_pengajuan_pemohon,
				kode_dpsj: kode_dpsj,
				untuk: untuk,
				nominal_pengajuan: nominal_pengajuan
			},
			success: function(response) {
				alert("Pengajuan berhasil diajukan!");
				getDataPage(page); // refresh data
				$("#modal-ajukan").modal('hide');
				console.log(response);
			},
			error: function(xhr, status, error) {
				alert("Terjadi kesalahan saat mengajukan pengajuan.");
			}
		});
	});

	// edit pengajuan
	$(document).on("click",".edit", function(){
		var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
		var kode_dpsj = $(this).data("kode_dpsj");
		var deskripsi_dpsj = $(this).data("deskripsi_dpsj");
		$("#ajukan-title").text("Edit Data Pengajuan");
		
		// ambil data form edit pengajuan menggunakan AJAX
        $.ajax({
            url: "<?=base_url()?>index.php/pengajuan_edit",
            type: "POST",
            data: {id_pengajuan_pemohon: id_pengajuan_pemohon, kode_dpsj: kode_dpsj, deskripsi_dpsj: deskripsi_dpsj},
            success: function(response) {
                // Tampilkan pesan sukses atau lakukan tindakan lain
                //alert("Data berhasil disimpan!");
                $("#data-ajukan").html(response);
                console.log(response);
            },
            error: function(xhr, status, error) {
                // Tampilkan pesan kesalahan
                //alert("Terjadi kesalahan saat ...");
                //console.log(error);
            }
        });
	});

    // hapus data pengajuan
	$(document).on("click",".delete", function(){

		var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
		var page = $("#temp_page").val(); // ambil halaman saat ini
		
        if (!confirm("Apakah Anda yakin ingin menghapus data pengajuan ini?")) {
            return false;
        } else {
            $.ajax({
                url: "<?=base_url()?>index.php/pengajuan_edit/deletePengajuan",
                type: "POST",
                data: {id_pengajuan_pemohon:id_pengajuan_pemohon},
                success: function(data)
                {
                    getDataPage(page); // refresh data
                }
            });
        }
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

<?php include(APPPATH.'views/unit_kerja/pengajuan_form_edit_script.php');?>


<style type="text/css">

/* Tables */
table#tabel tr td, th {
	border:1px solid gray;
}

#tabel {
    font-family: Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 99%;
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
    background-color: #009879;
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

<?php $this->load->view('style_table'); ?>