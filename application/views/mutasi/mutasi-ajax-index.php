<?php
// set opt tahun
$opt_tahun ='';
foreach($array_tahun as $row){
	$opt_tahun .= '<option value="'.$row.'">'.$row.'</option>';
}

// set opt bulan
$nama_bulan = array(
	'01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei', '06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
);
$opt_bulan ='<option value="All">All</option>';
$selected = '';
foreach($array_bulan as $row){
	$selected = ($row == $array_bulan_aktif[0]) ? 'selected' : '';
	$opt_bulan .= '<option value="'.$row.'" '.$selected.'>'.$nama_bulan[$row].'</option>';
}

?>

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
	        <div class="post-search-panel text-center">
				<div class="form-group form-inline col-sm-12">
					<label for="tahun" class=" control-label text-right">Tahun</label>
					<select name="tahun" id="tahun" class="form-control" >
						<?php echo $opt_tahun; ?>
					</select>
					<label for="bulan" class=" control-label text-right">Bulan</label>
					<select name="bulan" id="bulan" class="form-control" >
						<?php echo $opt_bulan; ?>
					</select>
				</div>   
	            <div class="row text-center">               
                	<input type="text" id="nomor_pengajuan-search" onkeyup="searchFilter()" placeholder="Filter Nomor Pengajuan" class="table-search-filters">
					<?php if($this->session->userdata['logged_anggaran']['role'] != 'pum') { ?>
						<input type="text" id="dpsj_pengaju-search" onkeyup="searchFilter()" placeholder="Filter DPSJ Pengaju" class="table-search-filters" style="display:none;">
					<?php } ?>
				</div>
	        </div>	
	    </div>
	    <div class="row" style="overflow:auto">
			<div class="post-list" id="postList"></div>
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
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="catatan-title">FORMULIR KENDALI DOKUMEN</h4>
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
<?php include(APPPATH.'views/custom_modal.php') ?>
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
    var keywords = $('#nomor_pengajuan-search').val();
    var sortBy = "DESC";
    var status = $('#status').val(); //$("#status_pengaduan").val();
	var tahun = $("#tahun").val();
	var bulan = $("#bulan").val();
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>mutasi/data/0',  //url: '<?php echo base_url(); ?>koordinator/ajax/'+page_num,
        data:'page='+page_num+'&keywords='+keywords+'&sortBy='+sortBy+'&status='+status+'&tahun='+tahun+'&bulan='+bulan,
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
	var keywords = $('#nomor_pengajuan-search').val();
	var sortBy = $('#sortBy').val();
	var string_path = window.location.pathname;
	var last_string_path = string_path.slice(-1);
	if(last_string_path == '/'){
		$url = 'data/'+page;
	} else {
		$url = 'mutasi/data/'+page;
	}
	$.ajax({
		method: "POST",
		url: $url,
		data: { page:page, keywords:keywords, sortBy:sortBy, status: $('#status').val(), tahun: $('#tahun').val(), bulan: $('#bulan').val() },
		success: function(data){
			$('#postList').html(data);
            $('.loading-overlay').fadeOut("slow");
		}
	});
}

function searchFilter() {
	var keywords = $('#nomor_pengajuan-search').val();
	var status = $('#status').val();
	var tahun = $('#tahun').val();
	var bulan = $('#bulan').val();
	var sortBy = $('#sortBy').val();
	var page = 0; // reset to first page
	getDataPage(page);
}

$(document).ready(function()
{
	// sebelum diajukan, tampilkan data pengajuan melalui form konfirmasi
	$(document).on("click",".ajukan", function(){
		var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
		var kode_grup = $(this).data("kode_grup");
		var kode_dpsj = $(this).data("kode_dpsj");
		var tahun = $(this).data("tahun");
		var bulan = $(this).data("bulan");
		$("#ajukan-title").text("Anda akan mengajukan data mutasi sebagai berikut:");

		// ambil data form edit pengajuan menggunakan AJAX
        $.ajax({
            url: "<?=base_url()?>mutasi/konfirmasi_pengajuan",
            type: "POST",
            data: {kode_grup: kode_grup, kode_dpsj: kode_dpsj, id_pengajuan_pemohon:id_pengajuan_pemohon, tahun:tahun, bulan:bulan},
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

	// edit data mutasi
	$(document).on("click",".edit", function(){
		var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
		var kode_grup = $(this).data("kode_grup");
		var kode_dpsj = $(this).data("kode_dpsj");
		//var deskripsi_dpsj = $(this).data("deskripsi_dpsj");
		$("#ajukan-title").text("Edit Data Mutasi");
		
		// ambil data form edit pengajuan menggunakan AJAX
        $.ajax({
            url: "<?=base_url()?>mutasi_edit",
            type: "POST",
            data: {kode_grup: kode_grup, kode_dpsj: kode_dpsj, id_pengajuan_pemohon:id_pengajuan_pemohon},
            success: function(response) {
                // Tampilkan pesan sukses atau lakukan tindakan lain
                //alert("Data berhasil disimpan!");
                $("#data-modal").html(response);
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

		var kode_grup = $(this).data("kode_grup");
		var page = $("#temp_page").val(); // ambil halaman saat ini
		
        if (!confirm("Apakah Anda yakin ingin menghapus data pengajuan ini?")) {
            return false;
        } else {
            $.ajax({
                url: "<?=base_url()?>mutasi_edit/deletePengajuan",
                type: "POST",
                data: {kode_grup:kode_grup},
                success: function(data)
                {
                    getDataPage(page); // refresh data
                }
            });
        }
	});

	// setelah melihat form konfirmasi, klik tombol ajukan untuk mengajukan pengajuan
	$(document).on("click", "#ajukan", function() 
	{
		var kode_grup = $(this).data("kode_grup");
		var tahun = $(this).data("tahun");
		var bulan = $(this).data("bulan");
		var kode_unit = $(this).data("kode_unit");
		var page = $("#temp_page").val(); // ambil halaman saat ini

		if (!confirm("Apakah Anda yakin ingin mengajukan data mutasi ini?")) {
            return false;
        } else {
			// kirim data ke server untuk mengajukan pengajuan
			$.ajax({
				url: "<?=base_url()?>index.php/mutasi/ajukan",
				type: "POST",
				datatype: "json",
				data: {
					kode_grup: kode_grup, tahun:tahun, bulan:bulan, kode_unit:kode_unit
				},
				success: function(response) {
					alert("Pengajuan data mutasi berhasil diajukan!");
					getDataPage(page); // refresh data
					$("#modal-ajukan").modal('hide');
					console.log(response);
				},
				error: function(xhr, status, error) {
					alert("Terjadi kesalahan saat mengajukan pengajuan.");
				}
			});
		}
	});

	// sebelum disetujui, tampilkan data pengajuan melalui form konfirmasi
	$(document).on("click",".approval", function(){
		var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
		var kode_grup = $(this).data("kode_grup");
		var kode_dpsj = $(this).data("kode_dpsj");
		var tahun = $(this).data("tahun");
		var bulan = $(this).data("bulan");
		$("#ajukan-title").text("Anda akan menyetujui data mutasi sebagai berikut:");

		// ambil data form edit pengajuan menggunakan AJAX
        $.ajax({
            url: "<?=base_url()?>mutasi/konfirmasi_pengajuan",
            type: "POST",
            data: {kode_grup: kode_grup, kode_dpsj: kode_dpsj, id_pengajuan_pemohon:id_pengajuan_pemohon, tahun:tahun, bulan:bulan},
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
	$(document).on("click", "#approve", function() 
	{
		var kode_grup = $(this).data("kode_grup");
		var page = $("#temp_page").val(); // ambil halaman saat ini

		if (!confirm("Apakah Anda yakin ingin menyetujui data mutasi ini?")) {
            return false;
        } else {
			// kirim data ke server untuk mengajukan pengajuan
			$.ajax({
				url: "<?=base_url()?>index.php/mutasi/approve",
				type: "POST",
				datatype: "json",
				data: {
					kode_grup: kode_grup
				},
				success: function(response) {
					alert("Pengajuan data mutasi berhasil disetujui!");
					getDataPage(page); // refresh data
					$("#modal-ajukan").modal('hide');
					console.log(response);
				},
				error: function(xhr, status, error) {
					alert("Terjadi kesalahan saat mengajukan pengajuan.");
				}
			});
		}
	});

	// setelah melihat form konfirmasi, klik tombol dikembalikan untuk mengembalikan pengajuan
	$(document).on("click", "#dikembalikan", function() 
	{
		var kode_grup = $(this).data("kode_grup");
		var page = $("#temp_page").val(); // ambil halaman saat ini

		if (!confirm("Apakah Anda yakin akan mengembalikan data mutasi ini?")) {
			return false;
		} else {
			// kirim data ke server untuk mengajukan pengajuan
			$.ajax({
				url: "<?=base_url()?>index.php/mutasi/dikembalikan",
				type: "POST",
				datatype: "json",
				data: {
					kode_grup: kode_grup
				},
				success: function(response) {
					alert("Pengajuan data mutasi berhasil dikembalikan!");
					getDataPage(page); // refresh data
					$("#modal-ajukan").modal('hide');
					console.log(response);
				},
				error: function(xhr, status, error) {
					alert("Terjadi kesalahan saat mengajukan pengajuan.");
				}
			});
		}
	});

	// setelah melihat form konfirmasi, klik tombol dibatalkan untuk membatalkan pengajuan
	$(document).on("click", "#dibatalkan", function() 
	{
		var kode_grup = $(this).data("kode_grup");
		var page = $("#temp_page").val(); // ambil halaman saat ini

		if (!confirm("Apakah Anda yakin akan membatalkan data mutasi ini?")) {
			return false;
		} else {
			// kirim data ke server untuk mengajukan pengajuan
			$.ajax({
				url: "<?=base_url()?>index.php/mutasi/dibatalkan",
				type: "POST",
				datatype: "json",
				data: {
					kode_grup: kode_grup
				},
				success: function(response) {
					alert("Pengajuan data mutasi berhasil dibatalkan!");
					getDataPage(page); // refresh data
					$("#modal-ajukan").modal('hide');
					console.log(response);
				},
				error: function(xhr, status, error) {
					alert("Terjadi kesalahan saat mengajukan pengajuan.");
				}
			});
		}
	});

	$(document).on("click", ".cetak", function() {
		var kode_grup = $(this).data("kode_grup");
		var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
		post_to_url("<?=base_url('Mutasi_excel')?>", {id_pengajuan_pemohon:id_pengajuan_pemohon, kode_grup:kode_grup}, 'post');

		
		// Redirect to the URL to generate and download the document
		//window.location.href = "<?=base_url()?>Test_word/data/"+id_pengajuan_pemohon;
	});

	$(document).on("change", "#tahun, #bulan", function() {
		searchFilter();
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

<?php include(APPPATH.'views/mutasi/mutasi_form_edit_script.php');?>

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
	background-color:#fff;
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

.table-search-filters {
    margin: 5px;
	width: 25%;
	height: 30px;
	margin-bottom: 5px;
	padding: 5px;
	border: 1px solid #ddd;
	border-radius: 4px;
	background-color: #f9f9f9;

}

		/* ===== SOLUSI STICKY HEADER ===== */
		/* Container dengan scroll horizontal DAN vertikal */
		.table-container {
			position: relative;
			border: 1px solid #e2e8f0;
			/*border-radius: 14px;*/
			overflow: auto;
			max-height: 70vh;
			background: white;
		}

		/* STICKY HEADER - ini yang benar */
		.styled-table thead tr {
			position: sticky;
			top: 0;
			z-index: 20;
		}
</style>

<?php include(APPPATH.'views/style_table.php') ?>