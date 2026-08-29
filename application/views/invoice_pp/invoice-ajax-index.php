<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?= $title ?>
      <small>Daftar Invice PP</small>
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

	            <div class="row text-center">
	                <div class="form-group form-inline col-sm-12">
	                    <label for="tahun" class=" control-label text-right">Periode</label>
						<select name="bulan" id="bulan" class="form-control" >
	                        <?php echo optBulan($bulan); ?>
	                    </select>
	                    <select name="tahun" id="tahun" class="form-control" >
	                        <?php echo optTahun($tahun); ?>
	                    </select>
						<select name="tgl" id="tgl" class="form-control" >
							<option value='00' selected>All</option>
							<?php
							for($d=1; $d<=31; $d++){
								$day = str_pad($d, 2, "0", STR_PAD_LEFT);
								// jika tanggal hari ini sama dengan $day maka beri atribut selected
								if($day == date('d')){
									echo "<option value='$day' >$day</option>";
								} else {
									echo "<option value='$day'>$day</option>";
								}
							}
							?>
						</select>	                    
	                </div>                                   
	            </div>
	
	    <!--<div class="row">
	        <div class="post-search-panel">
				
	            <input type="hidden" id="keywords" placeholder="cari Nomor Pengajuan.." onkeyup="searchFilter()"/>
	                 
				<select id="status" onchange="searchFilter()"> 			 
	                <option value="Semua" selected >Semua Status</option>     
	                <?php 
					//$array_status_mutasi = array_status_mutasi();
					//foreach($array_status_mutasi as $key => $value) {
						//echo '<option value="'.$key.'">'.$value.'</option>';
					//}
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
	
				
	            <select id="status_pengaduan" onchange="searchFilter()">              
	                <option value="Semua" selected >Semua Status</option>     
	                <option value="Menunggu Konfirmasi">Menunggu Konfirmasi</option>               
	                <option value="Diterima">Diterima</option>              
	                <option value="Dalam Proses">Dalam Proses</option>                       
	                <option value="Ditunda">Ditunda</option>                            
	                <option value="Ditolak">Ditolak</option>
	                <option value="Selesai">Selesai</option>
	            </select>
				
	            <input type="text" id="keywords" placeholder="Type keywords to filter posts"/>
	            <select id="sortBy" onchange="searchFilter()">
	                <option value="">Sort By</option>
	                <option value="asc">Ascending</option>
	                <option value="desc">Descending</option>
	            </select>
	            
			<a href="#panduan" class="label label-danger" style="font-size:14px"><strong>Panduan</strong></a>
	        </div>
	
	    </div>-->
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
    var tahun = $('#tahun').val();
    var bulan = $('#bulan').val();
    var tgl = $('#tgl').val();
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>invoice_pp/data',
        data:'tahun='+tahun+'&bulan='+bulan+'&tgl='+tgl,
        beforeSend: function () {
            $('.loading-overlay').show();
        },
        success: function (html) {
            $('#postList').html(html);
            $('.loading-overlay').fadeOut("slow");
        }
    });
}
/*
function getDataPage(page){              
	var keywords = $('#keywords').val();
	var sortBy = $('#sortBy').val();
	var string_path = window.location.pathname;
	var last_string_path = string_path.slice(-1);
	if(last_string_path == '/'){
		$url = 'data/'+page;
	} else {
		$url = 'invoice/data/'+page;
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
*/

$(document).ready(function()
{
	$(document).on("change", "#tahun, #bulan, #tgl", function(){
		fetch_data();
	});

	// jika tombol Edit_PP di klik, tampilkan input text pada kolom no_pp dan tanggal pp
	$(document).on("click",".edit_pp", function(){
		var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
		var no_tiket = $(this).data("no_tiket");
		// tampilkan input text pada kolom no_pp dan tanggal pp
		var current_no_pp = $("#input_no_pp_"+no_tiket).text();
		var current_tgl_pp = $("#input_tgl_pp_"+no_tiket).text();
		$("#input_no_pp_"+no_tiket).html('<input type="text" id="no_pp_input_'+no_tiket+'" value="'+current_no_pp+'">');
		$("#input_tgl_pp_"+no_tiket).html('<input type="date" id="tgl_pp_input_'+no_tiket+'" value="'+current_tgl_pp+'">');
		$(this).removeClass("edit_pp").addClass("save_pp").text("Simpan PP");
		// tambahkan tombol batal
		$(this).after('<button class="btn btn-danger btn-xs batal_pp" data-no_tiket="'+no_tiket+'" data-id_pengajuan_pemohon="'+id_pengajuan_pemohon+'">Batal</button>');

	});

	// jika tombol Batal di klik, kembalikan tampilan kolom no_pp dan tanggal pp ke semula
	$(document).on("click",".batal_pp", function(){
		var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
		var no_tiket = $(this).data("no_tiket");
		// kembalikan tampilan kolom no_pp dan tanggal pp ke semula
		var current_no_pp = $("#no_pp_input_"+no_tiket).val();
		var current_tgl_pp = $("#tgl_pp_input_"+no_tiket).val();
		$("#input_no_pp_"+no_tiket).html(current_no_pp);
		$("#input_tgl_pp_"+no_tiket).html(current_tgl_pp);
		// ubah tombol batal menjadi tombol edit
		$(this).prev(".save_pp").removeClass("save_pp").addClass("edit_pp").text("Edit PP");
		$(this).remove();
	});

	// jika tombol Save_PP di klik, simpan data no_pp dan tanggal pp
	$(document).on("click",".save_pp", function(){
		var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
		var no_tiket = $(this).data("no_tiket");
		var new_no_pp = $("#no_pp_input_"+no_tiket).val();
		var new_tgl_pp = $("#tgl_pp_input_"+no_tiket).val();
		var no_invoice_pp = $(this).data("no_invoice_pp");
	    var tgl_invoice_pp = $(this).data("tgl_invoice_pp");
		var aksi_pp = $(this).data("aksi_pp");
		
		// simpan data no_pp dan tanggal pp ke database
		$.ajax({
			url: "<?=base_url()?>invoice_pp/update_pp",
			type: "POST",
			data: {
				id_pengajuan_pemohon:id_pengajuan_pemohon,
				no_tiket:no_tiket,
				no_pp:new_no_pp,
				tgl_pp:new_tgl_pp
			},
			success: function(data)
			{
				// tampilkan data no_pp dan tanggal pp yang baru
				$("#input_no_pp_"+no_tiket).html(new_no_pp);
				$("#input_tgl_pp_"+no_tiket).html(new_tgl_pp);
				simpanKendaliDokumen(no_invoice_pp, tgl_invoice_pp, aksi_pp, id_pengajuan_pemohon);
				fetch_data(); // refresh data
				console.log(data);
			}
		});
	});

	// jika tombol Edit_MDK di klik, tampilkan input text pada kolom no_mdk dan tanggal mdk
	$(document).on("click",".edit_mdk", function(){
		var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
		var no_tiket = $(this).data("no_tiket");
		// tampilkan input text pada kolom no_mdk dan tanggal mdk
		var current_no_mdk = $("#input_no_mdk_"+no_tiket).text();
		var current_tgl_mdk = $("#input_tgl_mdk_"+no_tiket).text();
		$("#input_no_mdk_"+no_tiket).html('<input type="text" id="no_mdk_input_'+no_tiket+'" value="'+current_no_mdk+'">');
		$("#input_tgl_mdk_"+no_tiket).html('<input type="date" id="tgl_mdk_input_'+no_tiket+'" value="'+current_tgl_mdk+'">');
		$(this).removeClass("edit_mdk").addClass("save_mdk").text("Simpan MDK");
		// tambahkan tombol batal
		$(this).after('<button class="btn btn-danger btn-xs batal_mdk" data-no_tiket="'+no_tiket+'" data-id_pengajuan_pemohon="'+id_pengajuan_pemohon+'">Batal</button>');
	});

	// jika tombol Batal di klik, kembalikan tampilan kolom no_mdk dan tanggal mdk ke semula
	$(document).on("click",".batal_mdk", function(){
		var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
		var no_tiket = $(this).data("no_tiket");
		// kembalikan tampilan kolom no_mdk dan tanggal mdk ke semula
		var current_no_mdk = $("#no_mdk_input_"+no_tiket).val();
		var current_tgl_mdk = $("#tgl_mdk_input_"+no_tiket).val();
		$("#input_no_mdk_"+no_tiket).html(current_no_mdk);
		$("#input_tgl_mdk_"+no_tiket).html(current_tgl_mdk);
		// ubah tombol batal menjadi tombol edit
		$(this).prev(".save_mdk").removeClass("save_mdk").addClass("edit_mdk").text("Edit MDK");
		$(this).remove();
	});

	// jika tombol Save_MDK di klik, simpan data no_mdk dan tanggal mdk
	$(document).on("click",".save_mdk", function(){
		var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
		var no_tiket = $(this).data("no_tiket");
		var new_no_mdk = $("#no_mdk_input_"+no_tiket).val();
		var new_tgl_mdk = $("#tgl_mdk_input_"+no_tiket).val();
		var no_invoice_pp = $(this).data("no_invoice_pp");
	    var tgl_invoice_pp = $(this).data("tgl_invoice_pp");
		var aksi_pp = $(this).data("aksi_pp");
		
		// simpan data no_mdk dan tanggal mdk ke database
		$.ajax({
			url: "<?=base_url()?>invoice_pp/update_mdk",
			type: "POST",
			data: {
				id_pengajuan_pemohon:id_pengajuan_pemohon,
				no_tiket:no_tiket,
				no_mdk:new_no_mdk,
				tgl_mdk:new_tgl_mdk
			},
			success: function(data)
			{
				// tampilkan data no_mdk dan tanggal mdk yang baru
				$("#input_no_mdk_"+no_tiket).html(new_no_mdk);
				$("#input_tgl_mdk_"+no_tiket).html(new_tgl_mdk);
				simpanKendaliDokumen(no_invoice_pp, tgl_invoice_pp, aksi_pp, id_pengajuan_pemohon);
				fetch_data(); // refresh data
				console.log(data);
			}
		});
	});

	// ajukan invoice pp
	$(document).on("click",".konfirmasi", function(){
		var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
		var no_tiket = $(this).data("no_tiket");
		// tampilkan modal konfirmasi ajukan invoice pp
		$.ajax({
			url: "<?=base_url()?>invoice_pp/status_pengajuan_konfirmasi",
			type: "POST",
			data: {
				id_pengajuan_pemohon:id_pengajuan_pemohon,
				no_tiket:no_tiket
			},
			success: function(data)
			{
				$("#data-ajukan").html(data);
				$("#modal-ajukan").modal("show");
			}
		});
		
	});
	
	// kirim ke akuntan
	$(document).on("click",".send_to_akuntan_konfirmasi", function(){
		
		var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
	    var no_tiket = $(this).data("no_tiket");
	    
			$.ajax({
				url: "<?=base_url()?>invoice/send_to_akuntan_konfirmasi",
				type: "POST",
				data: {id_pengajuan_pemohon:id_pengajuan_pemohon, no_tiket:no_tiket},
				success: function(data)
				{
				    $("#data-ajukan").html(data);
					//fetch_data(); // refresh data
					console.log(data);
				}
			});
	});	

	// kirim ke akuntan
	$(document).on("click",".send_to_akuntan", function(){
		
		var id_pengajuan_pemohon = $("#id_pengajuan_pemohon").val();
		var no_tiket = $(this).data("no_tiket");
		
		if (!confirm("Apakah Anda yakin ingin mengirim data invoice ini ke Akuntan?")) {
			return false;
		} else {
			$.ajax({
				url: "<?=base_url()?>invoice/send_to_akuntan",
				type: "POST",
				data: {id_pengajuan_pemohon:id_pengajuan_pemohon, no_tiket:no_tiket},
				success: function(data)
				{
				    // --- PANGGIL FUNGSI simpan kendali dokumen ---
                    simpanKendaliDokumenManajer();
                    $("#data-ajukan").html(data);
					fetch_data(); // refresh data
					console.log(data);
				}
			});
		}
	});	

	// konfirmasi batal kirim ke akuntan
	$(document).on("click",".send_to_akuntan_konfirmasi_batalkan", function(){
		
		var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
	    var no_tiket = $(this).data("no_tiket");
	    
			$.ajax({
				url: "<?=base_url()?>invoice/send_to_akuntan_konfirmasi_batalkan",
				type: "POST",
				data: {id_pengajuan_pemohon:id_pengajuan_pemohon, no_tiket:no_tiket},
				success: function(data)
				{
				    $("#data-ajukan").html(data);
					//fetch_data(); // refresh data
					console.log(data);
				}
			});
	});

	// batalkan pengajuan invoice pp
	$(document).on("click",".send_to_akuntan_batalkan", function(){
		
		var id_pengajuan_pemohon = $("#id_pengajuan_pemohon").val();
		var no_tiket = $(this).data("no_tiket");
		
		if (!confirm("Apakah Anda yakin ingin membatalkan pengajuan data invoice ini?")) {
			return false;
		} else {
			$.ajax({
				url: "<?=base_url()?>invoice/send_to_akuntan_batalkan",
				type: "POST",
				data: {id_pengajuan_pemohon:id_pengajuan_pemohon, no_tiket:no_tiket},
				success: function(data)
				{
				    // --- PANGGIL FUNGSI simpan kendali dokumen ---
					simpanKendaliDokumenManajer();
					$("#data-ajukan").html(data);
					fetch_data(); // refresh data
					console.log(data);
				}
			});
		}
	});
});


// Fungsi baru untuk simpan kendali dokumen

function simpanKendaliDokumen(no_invoice_pp, tgl_invoice_pp, aksi_pp, id_pengajuan_pemohon) {

    $.ajax({
        url: "<?=base_url()?>index.php/Kendali_dokumen/invoice_pp",
        type: "POST",
        data: {
            id_pengajuan_pemohon: id_pengajuan_pemohon, 
            no_invoice_pp: no_invoice_pp, 
            tgl_invoice_pp: tgl_invoice_pp,
            no_tiket: $("#no_tiket").val(), 
            aksi_pp: aksi_pp
        },
        success: function(response) {
            $("#test_script").html(response);
            console.log(response);
        },
        error: function(xhr, status, error) {
            $("#test_script").html('Terjadi kesalahan saat ...');
        }
    });
}

function simpanKendaliDokumenManajer() {
    var no_invoice_pp = $("#no_invoice_pp").val();
    var uraian = $("#uraian").val();
    var tahun = $("#tahun").val();
    var bulan = $("#bulan").val();
    var tgl = $("#tgl").val();
    var id_pengajuan_pemohon = $("#id_pengajuan_pemohon").val();
    var keterangan = $("#keterangan").val();

    $.ajax({
        url: "<?=base_url()?>index.php/Kendali_dokumen/invoice",
        type: "POST",
        data: {
            id_pengajuan_pemohon: id_pengajuan_pemohon, 
            no_invoice_pp: no_invoice_pp, 
            uraian: uraian, 
            tahun: tahun, 
            bulan: bulan, 
            tgl: tgl, 
            no_tiket: $("#no_tiket").val(), 
            keterangan: keterangan
        },
        success: function(response) {
            $("#data-ajukan").html(response);
            console.log(response);
        },
        error: function(xhr, status, error) {
            $("#data-ajukan").html('Terjadi kesalahan saat ...');
        }
    });
}

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

<?php //include(APPPATH.'views/mutasi/mutasi_form_edit_script.php');?>

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

<?php include(APPPATH.'views/style_table.php') ?>