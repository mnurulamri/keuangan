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

				<div class="row text-center">
					<div class="filter-wrapper">
						<input type="text" id="filterNoInvoice" placeholder="Cari No Invoice PP...">
						<button class="btn btn-info" onclick="clearFilter('filterNoInvoice')">Clear</button>
					</div>

					<div class="filter-wrapper">
						<input type="text" id="filterNoPengajuan" placeholder="Cari Nomor Pengajuan...">
						<button class="btn btn-info" onclick="clearFilter('filterNoPengajuan')">Clear</button>
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
	    <!--<div class="row">
	        <div class="box col-md-12 col-lg-12">
	            <div class="box-body" style="overflow:auto">	                
	                <div class="loading-overlay"><div class="overlay-content">Loading.....</div></div>
	                <div class="post-list" id="postList"></div>
	                <div class="loading" style="display: none;">
						<div class="content"><i class="fa fa-spinner fa-spin"></i></div>
					</div>	            
	            </div>
	        </div>
	    </div>-->
		<div class="post-list table-container" id="postList" style="background-color: #fff;"></div>
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

<!-- modal edit form pengajuan -->
<div class="modal fade" id="modal-invoice" tabindex="-1" role="dialog" aria-labelledby="viewAjukanLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="ajukan-title">Anda akan mengupdate data invoice sebagai berikut:</h4>
			</div>
			<div class="modal-body" style="overflow:auto">
				<div id="data-invoice">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal" aria-label="Close">Tutup</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div id="myModal" class="custom-modal">
    <div class="custom-modal-content">
        <span class="close-modal" onclick="document.getElementById('myModal').style.display='none'">&times;</span>
        <h3>Judul Modal</h3>
        <div id="data-custom-modal">Isi konten modal Anda di sini...</div>
    </div>
</div>

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
    $('#postList').html('loading...');
    page_num = 0;
    var tahun = $('#tahun').val();
    var bulan = $('#bulan').val();
    var tgl = $('#tgl').val();
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>korpum/Invoice_pp/data',
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
	
    // hapus data pengajuan
	$(document).on("click",".delete", function(){
		
		var no_tiket = $(this).data("no_tiket");
		//var page = $("#temp_page").val(); // ambil halaman saat ini
		
        if (!confirm("Apakah Anda yakin ingin menghapus data invoice ini?")) {
            return false;
        } else {
            $.ajax({
                url: "<?=base_url()?>invoice/delete",
                type: "POST",
                data: {no_tiket:no_tiket},
                success: function(data)
                {
                    fetch_data(); // refresh data
					console.log(data);
                }
            });
        }
	});
	
	// tambah data invoice
	$(document).on("click",".tambah", function(){
		
		var no_tiket = $(this).data("no_tiket");
		
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url(); ?>invoice/tambah_form',
            data: {no_tiket:no_tiket},
            beforeSend: function () {
                $('#data-invoice').html('loading...');
            },
            success: function (html) {
                $('#data-invoice').html(html);
            }
        });
	});
	
	// edit data invoice
	$(document).on("click",".edit", function(){
		
		var no_tiket = $(this).data("no_tiket");
		
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url(); ?>invoice/edit_form',
            data: {no_tiket:no_tiket},
            beforeSend: function () {
                $('#data-invoice').html('loading...');
            },
            success: function (html) {
                $('#data-invoice').html(html);
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
				    $("#data-invoice").html(data);
					//fetch_data(); // refresh data
					console.log(data);
				}
			});
	});	
	
	// kirim ke akuntan
	$(document).on("click",".send_to_akuntan", function(){
		
		var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
		var no_tiket = $(this).data("no_tiket");
		//var page = $("#temp_page").val(); // ambil halaman saat ini
		
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
                    simpanKendaliDokumen();
                    
					fetch_data(); // refresh data
					console.log(data);
				}
			});
		}
	});	
	
    // Saat tombol tambah di modal diklik (setelah memuat form)
    $(document).on("click", "#btn-tambah-pilih", function() {
        var id_pengajuan = [];
        $('.check-pengajuan:checked').each(function(){ id_pengajuan.push($(this).val()); });
        var no_tiket = $('#no_tiket_hidden').val();
    
        $.ajax({
            url: "<?=base_url()?>invoice/tambah_konfirmasi",
            type: "POST",
            data: {id_pengajuan: id_pengajuan, no_tiket: no_tiket},
            success: function(html) {
                $('#data-invoice').html(html);
            }
        });
    });
    
    // Proses simpan akhir
    $(document).on("click", "#btn-simpan-final", function() {
        var id_pengajuan = $(this).data('ids');
        var no_tiket = $(this).data('no_tiket');
        $.ajax({
            url: "<?=base_url()?>invoice/tambah_simpan_final",
            type: "POST",
            data: {id_pengajuan: id_pengajuan, no_tiket: no_tiket},
            success: function() {
                $('#modal-invoice').modal('hide');
                fetch_data(); // Reload tabel utama
            }
        });
    });
    
    $(document).on('click', '.approval-data-invoice', function() {
        $("#data-invoice").html('loading...');

        // set variabel data	
		var no_tiket = $(this).data('no_tiket');	
        var id_pengajuan_pemohon = [];
        $('.pilih_'+no_tiket+':checked').each(function(){ id_pengajuan_pemohon.push($(this).val()); });

		// distinct id_pengajuan_pemohon
		id_pengajuan_pemohon = [...new Set(id_pengajuan_pemohon)];

		// jika tidak ada id_pengajuan_pemohon yang dipilih, tampilkan alert dan hentikan proses
		if (id_pengajuan_pemohon.length === 0) {
			// tutup modal jika tidak ada id_pengajuan_pemohon yang dipilih
			$('#modal-invoice').modal('hide');
			alert('Anda harus memilih setidaknya 1 nomor pengajuan untuk diproses.');
			return false;
		}
		
		// jika terdapat lebih dari 1 id_pengajuan_pemohon yang dipilih, tampilkan alert dan hentikan proses
		if (id_pengajuan_pemohon.length > 1) {
			// tutup modal jika ada lebih dari 1 id_pengajuan_pemohon yang dipilih
			$('#modal-invoice').modal('hide');
			alert('Anda hanya dapat memilih 1 nomor pengajuan untuk diproses. Silakan pilih hanya 1 nomor pengajuan.');
			// nonaktifkan checkbox yang dipilih			
			$('.pilih_'+no_tiket).prop('checked', false);
			return false;	
		}

        var data = {
            tgl: $(this).data('tgl'),
            bulan: $(this).data('bulan'),
            tahun: $(this).data('tahun'),
            no_tiket: $(this).data('no_tiket'),
            no_invoice_pp: $(this).data('no_invoice_pp'),
            uraian: $(this).data('uraian'),
			id_pengajuan_pemohon: id_pengajuan_pemohon,
        };
      	//alert('Data yang akan dikirim ke akuntan: ' + JSON.stringify(data)); // Debugging alert
		//return false; // Hentikan eksekusi lebih lanjut untuk debugging
      	$.ajax({
            url: "<?=base_url()?>korpum/invoice_pp/invoice_approval_form",
            type: "POST",
            data: data,
            success: function(html) {
                //$('#modal-invoice').modal('hide');
                $("#data-invoice").html(html);
                //fetch_data(); // Reload tabel utama
            }
        });
        
        // pindah ke halaman pilihan data
        //post_to_url("<?=base_url()?>invoice_update/index", data, 'post');
    });
    
    // lanjut proses
    $(document).on('click', '.lanjut-proses', function() {
        $("#data-invoice").html('loading...');

        // set variabel data	
		var no_tiket = $(this).data('no_tiket');	
        var id_pengajuan_pemohon = [];
        var nomor_pengajuan = [];
		
		// set flag approve = 1 untuk menampilkan box lanjut proses
		var flag_box_approve = 'lanjut_proses';
        
        $('.pilih_'+no_tiket+':checked').each(function(){ 
            id_pengajuan_pemohon.push($(this).val()); 
            nomor_pengajuan.push($(this).data("nomor_pengajuan"));
        });

		// distinct id_pengajuan_pemohon
		id_pengajuan_pemohon = [...new Set(id_pengajuan_pemohon)];
		nomor_pengajuan = [...new Set(nomor_pengajuan)];

		// jika tidak ada id_pengajuan_pemohon yang dipilih, tampilkan alert dan hentikan proses
		if (id_pengajuan_pemohon.length === 0) {
			// tutup modal jika tidak ada id_pengajuan_pemohon yang dipilih
			$('#modal-invoice').modal('hide');
			alert('Anda harus memilih setidaknya 1 nomor pengajuan untuk diproses.');
			return false;
		}

        var data = {
            tgl: $(this).data('tgl'),
            bulan: $(this).data('bulan'),
            tahun: $(this).data('tahun'),
            no_tiket: $(this).data('no_tiket'),
            no_invoice_pp: $(this).data('no_invoice_pp'),
            uraian: $(this).data('uraian'),
			id_pengajuan_pemohon: id_pengajuan_pemohon,
			nomor_pengajuan: nomor_pengajuan,
			flag_box_approve: flag_box_approve
        };
      	//alert('Data yang akan dikirim ke akuntan: ' + JSON.stringify(data)); // Debugging alert
		//return false; // Hentikan eksekusi lebih lanjut untuk debugging
      	$.ajax({
            url: "<?=base_url()?>korpum/invoice_pp/invoice_approval_form",
            type: "POST",
            data: data,
            success: function(html) {
                //$('#modal-invoice').modal('hide');
                $("#data-invoice").html(html);
                //fetch_data(); // Reload tabel utama
            }
        });
        
        // pindah ke halaman pilihan data
        //post_to_url("<?=base_url()?>invoice_update/index", data, 'post');
    });
    
    // Ketika checkbox invoice diklik
    $(document).on('change', '.check-invoice', function() {
        // Ambil nomor tiket dari data-tiket induknya
        var noTiket = $(this).data('tiket');
        var isChecked = $(this).is(':checked');
        
        // Centang/hilangkan centang pada semua checkbox anak dengan class pilih_NOTIKET
        $('.pilih_' + noTiket).prop('checked', isChecked);
    });
    
    // retur
    $(document).on('click', '.retur', function() {
    	//alert('underconstruction..'); return false;
        $("#data-invoice").html('loading...');

        // set variabel data	
		var no_tiket = $(this).data('no_tiket');	
        var id_pengajuan_pemohon = [];
		var nomor_pengajuan = [];

		// set flag approve = 1 untuk menampilkan box lanjut proses
		var flag_box_approve = 'retur';

        $('.pilih_'+no_tiket+':checked').each(function(){ 
            id_pengajuan_pemohon.push($(this).val()); 
            nomor_pengajuan.push($(this).data("nomor_pengajuan"));
        });

		// distinct id_pengajuan_pemohon
		id_pengajuan_pemohon = [...new Set(id_pengajuan_pemohon)];
		nomor_pengajuan = [...new Set(nomor_pengajuan)];

		// jika tidak ada id_pengajuan_pemohon yang dipilih, tampilkan alert dan hentikan proses
		if (id_pengajuan_pemohon.length === 0) {
			// tutup modal jika tidak ada id_pengajuan_pemohon yang dipilih
			$('#modal-invoice').modal('hide');
			alert('Anda harus memilih setidaknya 1 nomor pengajuan untuk diproses.');
			return false;
		}

        var data = {
            tgl: $(this).data('tgl'),
            bulan: $(this).data('bulan'),
            tahun: $(this).data('tahun'),
            no_tiket: $(this).data('no_tiket'),
            no_invoice_pp: $(this).data('no_invoice_pp'),
            uraian: $(this).data('uraian'),
			id_pengajuan_pemohon: id_pengajuan_pemohon,
			nomor_pengajuan: nomor_pengajuan,
			flag_box_approve: flag_box_approve
        };
      	//alert('Data yang akan dikirim ke akuntan: ' + JSON.stringify(data)); // Debugging alert
		//return false; // Hentikan eksekusi lebih lanjut untuk debugging
      	$.ajax({
            url: "<?=base_url()?>korpum/invoice_pp/invoice_approval_form",
            type: "POST",
            data: data,
            success: function(html) {
                //$('#modal-invoice').modal('hide');
                $("#data-invoice").html(html);
                //fetch_data(); // Reload tabel utama
            }
        });
        
        // pindah ke halaman pilihan data
        //post_to_url("<?=base_url()?>invoice_update/index", data, 'post');
    });
});

// Fungsi baru untuk simpan kendali dokumen
function simpanKendaliDokumen() {
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

// Fungsi untuk memilih semua checkbox yang terlihat
function toggleAll(source) {
    var checkboxes = document.querySelectorAll('input[name="pilih[]"]');
    for (var i = 0; i < checkboxes.length; i++) {
        // Hanya centang yang barisnya terlihat (display bukan none)
        if (checkboxes[i].closest('tr').style.display !== 'none') {
            checkboxes[i].checked = source.checked;
        }
    }
}
</script>

<?php //include(APPPATH.'views/mutasi/mutasi_form_edit_script.php');?>

<style type="text/css">
.styled-table {
   border-collapse: collapse;
   margin: 25px 0;
   font-size:12px;
   font-family: Arial, Helvetica, sans-serif;
   min-width: 400px;
   box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
}
.styled-table thead tr {
   background-color: #43A5BE;
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
/*.styled-table tbody tr:nth-of-type(even) {
   background-color: #f3f3f3;
}*/
.styled-table tbody tr:last-of-type {
   border-bottom: 2px solid #43A5BE;
}
.styled-table tbody tr.active-row {
   font-weight: bold;
   color: #43A5BE;
}

.space {
	background-color:#fff;
}


/* 1. Kontainer untuk mengaktifkan scroll */
.table-container {
    max-height: 600px; /* Atur tinggi maksimal tabel sesuai kebutuhan */
    overflow-y: auto;   /* Aktifkan scroll vertikal */
    border: 1px solid #ddd;
    position: relative;
}

/* 2. Membuat header menjadi Sticky */
#invoice-table thead th {
    position: sticky;
    top: 0;             /* Menempel di bagian atas kontainer */
    background-color: #43A5BE; /* Warna harus sama dengan inline style TR Anda */
    color: #fff;
    z-index: 10;        /* Memastikan header berada di atas baris body */
    padding: 10px;
    box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4); /* Garis halus di bawah header */
}

/* 3. Style tambahan untuk isi tabel agar rapi */
#invoice-table {
    /* border-collapse: separate; Penting agar sticky border tidak pecah
    border-spacing: 0; */
}

/* 4. Style untuk filter */
.filter-wrapper {
    display: inline-flex;
    margin-right: 10px;
    align-items: center;
}

.filter-wrapper input {
    padding: 5px;
    border: 1px solid #ccc;
}

.filter-wrapper button {
    padding: 5px 10px;
    margin-left: -1px; /* Efek menempel */
    cursor: pointer;
    /*background-color: #f0f0f0;*/
    border: 1px solid #ccc;
}
</style>