<?php 
//echo '<pre>'; print_r($array_tahun); print_r($array_bulan); echo '</pre>'; exit();
// set opt tahun
$opt_tahun ='';
foreach($array_tahun as $row){
	$opt_tahun .= '<option value="'.$row.'">'.$row.'</option>';
}

// set opt bulan
$nama_bulan = array(
	'01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei', '06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
);
$opt_bulan ='';
$selected = '';
foreach($array_bulan as $row){
	$selected = ($row == $array_bulan_aktif[0]) ? 'selected' : '';
	$opt_bulan .= '<option value="'.$row.'" '.$selected.'>'.$nama_bulan[$row].'</option>';
}

//$array_status = array_status();
$array_status = array();
$array_status['Semua'] = 'Semua Status';
foreach($status_list as $row){
    $array_status[$row['kode_status']] = $row['nama_status'];
}
$array_status['Diretur'] = 'Semua Retur';

// tentukan nilai select berdasarkan kode_status dari get
if($kode_status == 'Diretur' ){
    $key = 'Diretur';
    $value = 'Semua Retur';
} else if($kode_status == 'Semua' ){
    $key = 'Semua';
    $value = 'Semua Status';
} else {
    $key = $kode_status;
    $value = $array_status[$kode_status];
}		

if($array_status[$kode_status]=='Semua Retur'){
	$head_status = 'Retur';
} else {
	$head_status = $array_status[$kode_status];
}			
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?= $title ?>
      <small><?= $head_status ?></small>
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
	        <div class="post-search-panel col-lg-12 col-md-12 col-sm-12" style="border: 1px solid gray; padding:10px; background-color: #fff; border-radius:3px">
	            
	            <div class="row text-center">
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
	            </div>
	            <div class="row text-center">
					<label for="keywords" class=" control-label text-right">Nomor Pengajuan</label>
	            	<input type="text" id="keywords" placeholder="cari Nomor Pengajuan.." onkeyup="searchFilter()"/>
				</div>

				
				 <?php
					// tentukan nilai select berdasarkan kode_status dari get
                    /*if($kode_status != '' or $kode_status != 0){
                        $key = $kode_status;
                        $value = $array_status[$kode_status];
                    } else {
                        $key = 'Semua';
                        $value = 'Semua Status';
                    }*/

                    // buat tag select dengan opsi dari $array_status
                    ?>
                    <select id="status" onchange="searchFilter()" style="display:none">
                        <?php 
						
						foreach($array_status as $k => $v){
							//echo $key.' '.$k.' '.$v.' ';
							if($key!='Semua' and $key!='Diretur'){
								$key = (int) $key;
								$k = (int) $k;
							}
							if($key === $k){
								$selected = 'selected';
							} else {
								$selected = '';
							}
							//echo $key.' '.$k.' '.$v.'<br>';
							echo '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
						}
						?>
                    </select>
	
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
			<div style="line-height:15px">&nbsp;</div>
            <div class="row text-right">
				<div class="col-lg-6 col-md-6 col-sm-6 text-right">
					<font style="font-weight: bold; font-size: 16px; color: #e33cb1; background-color: #fff;padding: 5px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- <?= $head_status ?> - &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6 text-right">
					<a href="<?=base_url('pengajuan/form')?>" class="btn btn-primary">Buat Pengajuan D01</a>
					<a href="<?=base_url('Pengajuan_D02/form')?>" class="btn btn-success">Buat Pengajuan D02</a>
				</div>
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

<!-- modal catatan -->
<div class="modal fade" id="modal-realisasi" tabindex="-1" role="dialog" aria-labelledby="viewRealisasiModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title text-center" id="realisasi-title">RINCIAN BIAYA</h4>
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
	var tahun = $("#tahun").val();
	var bulan = $("#bulan").val();
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>pengajuan_ajax/data/0',  //url: '<?php echo base_url(); ?>koordinator/ajax/'+page_num,
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
		data: { page:page, keywords:keywords, sortBy:sortBy, status: $('#status').val(), tahun: $('#tahun').val(), bulan: $('#bulan').val() },
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
	var tahun = $("#tahun").val();
	var bulan = $("#bulan").val();
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
		var nama_form = $(this).data("nama_form");
		var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
		var kode_dpsj = $("#kode_dpsj").text();
		var untuk = $("#untuk").val();
		var tgl_diajukan = $("#tanggal").val();
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
				nominal_pengajuan: nominal_pengajuan,
				nama_form: nama_form,
				tgl_diajukan: tgl_diajukan
			},
			success: function(response) {
				alert("Pengajuan berhasil diajukan!");
				getDataPage(page); // refresh data
				$(".status_"+id_pengajuan_pemohon).text('Menunggu Verifikasi Anggaran');
				/*$(".button_"+id_pengajuan_pemohon).html(''+
                        '<button class="btn btn-primary btn-xs" disabled >ajukan</button>'+
                        '<button class="btn btn-success btn-xs" disabled >edit</button>'+
                        '<button class="btn btn-danger btn-xs" disabled >delete</button>'+
                        '<button class="btn btn-warning btn-xs cetak" data-id_pengajuan_pemohon="'+id_pengajuan_pemohon+'">cetak</button>');*/
				$("#modal-ajukan").modal('hide');	

				// proses kendali dokumen		
				kendali_dokumen(id_pengajuan_pemohon, nama_form, tgl_diajukan);

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

	$(document).on("click", ".fetch-logs", function() {
		var nomor_pengajuan = $(this).data('nomor_pengajuan');
		var unit_pemohon = $(this).parent().parent().find('td').eq(2).text();
        
		$('#data-catatan').html("loading...");

		// Load the form for viewing catatan
		$.ajax({
			url: '<?php echo base_url(); ?>kendali_dokumen/fetch_logs_user_request',
			type: 'POST',
			data: {nomor_pengajuan:nomor_pengajuan, unit_pemohon:unit_pemohon},
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

	$(document).on("click", ".cetak", function() {
		var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
		var nama_form = $(this).data("nama_form");

		post_to_url("<?=base_url('template/pengajuan_cetak.php')?>", {id_pengajuan_pemohon:id_pengajuan_pemohon, nama_form:nama_form}, 'post');

		//post_to_url("<?=base_url('Test_word_with_table')?>", {id_pengajuan_pemohon:id_pengajuan_pemohon}, 'post');
		//post_to_url("<?=base_url('Pengajuan_cetak')?>", {id_pengajuan_pemohon:id_pengajuan_pemohon}, 'post');		
		// Redirect to the URL to generate and download the document
		//window.location.href = "<?=base_url()?>Test_word/data/"+id_pengajuan_pemohon;
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

	$(document).on("click", ".rincian-biaya-excel", function() {
		var id = $(this).data('id');
		var nomor_pengajuan = $(this).data('nomor_pengajuan');

		post_to_url("<?=base_url('Rekap_PerJenisBiaya')?>", {id:id, nomor_pengajuan:nomor_pengajuan}, 'post');
	});

	$(document).on("click", ".total-biaya-excel", function() {
		var id = $(this).data('id_pengajuan_pemohon');

		post_to_url("<?=base_url('Rekap_TotalBiaya')?>", {id:id}, 'post');
	});

	$(document).on("click", ".rekap-realisasi-excel", function() {
		var id = $(this).data('id_pengajuan_pemohon');
		var nomor_pengajuan = $(this).data('nomor_pengajuan');

		post_to_url("<?=base_url('Rekap_RealisasiUmko')?>", {id:id, nomor_pengajuan:nomor_pengajuan}, 'post');
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

function kendali_dokumen(id_pengajuan_pemohon, nama_form, tgl_diajukan){

    //console.log("Simpan catatan untuk pengajuan: " + kd_pengajuan + " dengan keterangan: " + pum_keterangan+ ' dan kode_status: '+kode_status);
    $.ajax({
        url: '<?=base_url("Kendali_dokumen/pum")?>',
        type: 'POST',
        data: {
			id_pengajuan_pemohon: id_pengajuan_pemohon, nama_form: nama_form, tgl_diajukan: tgl_diajukan
        },
        //dataType: 'json',
        success: function(res) {
            alert('Catatan berhasil disimpan.');
            console.log(res);
        },
        error: function() {
            alert('Terjadi kesalahan saat menyimpan catatan.');
        }
    });
}
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

<?php include(APPPATH.'views/style_table.php') ?>