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
	            <input type="text" id="keywords" placeholder="cari nomor pengajuan.." onkeyup="searchFilter()" style="display:none"/>

				 <?php
                //$array_status = array_status();
                $array_status = array();
                foreach($status_list as $row){
                    $array_status[$row['kode_status']] = $row['nama_status'];
                }
                
                // tentukan nilai select berdasarkan kode_status dari get
                if($kode_status != '' or $kode_status != 0){
                    $key = $kode_status;
                    $value = $array_status[$kode_status];
                } else {
                    $key = 'Semua';
                    $value = 'Semua Status';
                }

                //echo '<pre>';print_r($array_status); echo '</pre>';
                // buat tag select dengan opsi dari $array_status
                ?>
                <!--
                <select id="status" onchange="searchFilter()">
                    <option value="Semua">Semua Status</option> 
                    <?php foreach($array_status as $k => $v){ ?>
                        <option value="<?php echo $k; ?>" <?php if($key == $k) echo 'selected'; ?>><?php echo $v; ?></option>
                    <?php } ?>
                </select>
                -->
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

<!-- modal konfirmasi -->
<div class="modal fade" id="modal-kuitansi" tabindex="-1" role="dialog" aria-labelledby="viewkuitansiModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="catatan-title">KONFIRMASI CETAK</h4>
			</div>
			<div class="modal-body" style="overflow:auto">
                    <input type="text" id="ids_kuitansi" value="testing..." style="display:nonex">
				<div id="data-kuitansi">
					<?php //include(APPPATH.'views/unit_kerja/form_edit_pengajuan.php') ?>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" id="cetak-form-ko" class="btn btn-info btn-sm" aria-label="Close">Cetak Form Pengisian Kas Operasional</button>
				<button type="button" id="cetak-kuitansi" class="btn btn-warning btn-sm" aria-label="Close">Cetak Kuitansi</button>
				<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal" aria-label="Close">Tutup</button>
			</div>
		</div>
	</div>
</div>

<!-- bootstrap datepicker -->
<script src="<?=base_url()?>assets/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?=base_url()?>assets/AdminLTE/plugins/datepicker/locales/bootstrap-datepicker.id.js"></script>
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?=base_url()?>assets/AdminLTE/plugins/datepicker/datepicker3.css">


<script>
fetch_data();
function fetch_data()  
{  
    page_num = 0;
    var keywords = "";
    var sortBy = "DESC";
    var status = '0'; //$("#status_pengaduan").val();
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>kasir/kuitansi/data/0',
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

    $url = "<?=base_url().'kasir/kuitansi/data/'?>"+page;

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
    // format kolom tanggal -> datepicker dengan format dd mmm yyyy dalam bahasa Indonesia
    $(document).on("focus", ".tgl_penyerahan_spj_umko", function() {
        $(this).datepicker({
            format: "dd MM yyyy",
            autoclose: true,
            language: "id",
            todayHighlight: true,
            orientation: "bottom auto"
        }).on('changeDate', function(e) {
            $(this).text(e.format());
        });
    });

    // simpan tanggal penyerahan SPJ UMKO
    $(document).on("click", ".simpan-tgl-terima-spj", function() {
        var id = $(this).data("id");
        var tgl_penyerahan_spj_umko = $(this).closest(".input-group").find(".tgl_penyerahan_spj_umko").val();
        
        if (tgl_penyerahan_spj_umko == "") {
            alert("Tanggal penyerahan SPJ UMKO tidak boleh kosong.");
            return;
        }
        
        // kirim data ke server untuk disimpan
        $.ajax({
            url: "<?=base_url()?>index.php/kasir/monitoring/simpanTglPenyerahanSpj",
            type: "POST",
            data: {id: id, tgl_penyerahan_spj_umko: tgl_penyerahan_spj_umko},
            success: function(response) {
                // Tampilkan pesan sukses atau lakukan tindakan lain
                alert("Tanggal penyerahan SPJ UMKO berhasil disimpan!");
                fetch_data(); // refresh data
            },
            error: function(xhr, status, error) {
                // Tampilkan pesan kesalahan
                alert("Terjadi kesalahan saat menyimpan tanggal penyerahan SPJ UMKO.");
                console.log(error);
            }
        });
    });

    
    // sebelum di approval, tampilkan data pengajuan melalui form konfirmasi
	$(document).on("click",".approval", function(){
        var id_monitoring= $(this).data("id_monitoring");
		var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
        var kode_dpsj = $(this).data("kode_dpsj");

        // set title modal approval
		$("#approval-title").html('<div class="text-info text-center"><strong>Proses Penyerahan Uang Muka Kas Operasional</strong></div>');

		// ambil data form edit pengajuan menggunakan AJAX
        $.ajax({
            url: "<?=base_url()?>index.php/kasir/monitoring/konfirmasiApproval",
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
		$("#approval-title").html('<div class="text-danger text-center"><strong>Pengajuan UMKO Sudah Dikeluarkan</strong></div>');

		// ambil data form edit pengajuan menggunakan AJAX
        $.ajax({
            url: "<?=base_url()?>index.php/kasir/monitoring/detailApproval",
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

    $(document).on("click", "#cetak-kuitansi", function() {
        
        var id_monitoring = $("#ids_kuitansi").val();
        //var id_pengajuan_pemohon = $(this).data('id_pengajuan_pemohon');
        cetak_kuitansi(id_monitoring) 
    });

    $(document).on("click", "#cetak-form-ko", function() {
        
        var id_monitoring = $("#ids_kuitansi").val();
        //var id_pengajuan_pemohon = $(this).data('id_pengajuan_pemohon');
        cetak_form_ko(id_monitoring) 
    });
});

function cetak_kuitansi(id_monitoring) {
    
    var kasir_penerima = 'kasir_penerima';
    var kasir_keterangan = 'kasir_keterangan';
    var nominal_umko_cair = 'nominal_umko_cair';
    var telah_terima_dari = 'telah_terima_dari';
    var untuk_pembayaran = 'untuk_pembayaran';
    var yang_menyerahkan = 'yang_menyerahkan';

    post_to_url("<?=base_url('kasir/Kuitansi_docx/generate_kuitansi')?>", {             
            id_monitoring: id_monitoring
        }, 'post'
    );
}

function cetak_form_ko(id_monitoring) {
    
    var periode = $("#periode").val();
    var saldo_akhir = $("#saldo_akhir").val();
    var diajukan_oleh = 'Linariswati';
    var diperiksa_oleh = 'Astuti Utaminingtyas';
    var disetujui_oleh = 'Anggiriani, S.E.';

    post_to_url("<?=base_url('kasir/Form_ko_docx')?>", {             
            id_monitoring: id_monitoring, periode:periode, saldo_akhir:saldo_akhir, diajukan_oleh:diajukan_oleh, diperiksa_oleh:diperiksa_oleh, disetujui_oleh:disetujui_oleh
        }, 'post'
    );
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

<?php include(APPPATH.'views/style_table.php') ?>