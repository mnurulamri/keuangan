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
$opt_bulan ='';
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
        <!-- Konten utama Anda di sini -->
         <input type="text" id="temp_page" value="0" style="display:none">
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
                    //$array_status = array_status();
                    $array_status = array();
                    foreach($status_list as $row){
                        $array_status[$row['kode_status']] = $row['nama_status'];
                    }
                    //$array_status['Disetujui'] = 'Disetujui';
                    $array_status['Diretur'] = 'Diretur';
                    $array_status['Semua'] = 'Semua';
                    //echo '<pre>';print_r($array_status);echo '</pre>';
                    // tentukan nilai select berdasarkan kode_status dari get
                    if($kode_status != '' or $kode_status != 0){
                        $key = $kode_status;
                        $value = $array_status[$kode_status];
                    } else if($kode_status == 'Semua' ){
                        $key = 'Semua';
                        $value = 'Semua Status';
                    }/* else if($kode_status == 'Disetujui' ){
                        $key = 'Disetujui';
                        $value = 'Disetujui'; 
                    } */ else {
                        $key = $row['kode_status'];
                        $value = $array_status[$row['kode_status']];
                    }

                    //echo '<pre>';print_r($array_status); echo '</pre>';
                    // buat tag select dengan opsi dari $array_status

                    // sort $array_status tempatkan 'Semua' di posisi paling awal, diretur di posisi kedua
                    uasort($array_status, function($a, $b) {
                        if ($a === 'Semua') return -1;
                        if ($b === 'Semua') return 1;
                        if ($a === 'Diretur') return -1;
                        if ($b === 'Diretur') return 1;
                        if ($a === 31) return -1;
                        if ($b === 31) return 1;
                        if ($a === 44) return -1;
                        if ($b === 44) return 1;
                        return strcmp($a, $b);
                    });
                    ?>
                    <div class="row text-center">
                        <select id="status" onchange="searchFilter()" class="form-controlx col-lg-5 col-lg-5 col-md-5">
                            <!--<option value="Semua">Semua Status</option>--> 
                            <?php foreach($array_status as $k => $v){ ?>
                                <option value="<?php echo $k; ?>" <?php if($key == $k) echo 'selected'; ?>><?php echo $v; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <select id="sortBy" onchange="searchFilter()" style="display:none">
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

<!-- modal realisasi -->
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
    var status = $('#status').val();
	var tahun = $("#tahun").val();
	var bulan = $("#bulan").val();
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>kasir/monitoring_ajax/data/0',
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
	/*var string_path = window.location.pathname;
	var last_string_path = string_path.slice(-1);
	if(last_string_path == '/'){
		$url = 'data/'+page;
	} else {
		$url = 'kasir/monitoring_ajax/data/'+page;
	}*/
    $url = "<?=base_url().'kasir/monitoring_ajax/data/'?>"+page;
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
	    
	    $("#data-approval").html('loading...');
        var id_monitoring= $(this).data("id_monitoring");
		var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
        var kode_dpsj = $(this).data("kode_dpsj");
        $("#data-approval").html('loading..');
        // set title modal approval
		$("#approval-title").html('<div class="text-info text-center"><strong>Proses Kasir</strong></div>');

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

	$(document).on("change", "#tahun, #bulan", function() {
		searchFilter();
	});

	$(document).on("click", ".view-realisasi", function() {
		var id = $(this).data('id');
		var nomor_pengajuan = $(this).data('nomor_pengajuan');
		// Load the form for viewing realisasi
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

	$(document).on("click", ".terima-spj", function() {
        // set title
		$("#approval-title").html('<div class="text-info text-center"><strong>Pengembalian UMKO</strong></div>'); 
        
		//set variabel
        var id_monitoring = $(this).data('id_monitoring');
        var id_pengajuan_pemohon = $(this).data('id_pengajuan_pemohon');
        var kode_dpsj = $(this).data('kode_dpsj');

		// Cari elemen <tr> terdekat dari tombol yang ditekan
        var $row = $(this).closest('tr');
        
        // Ambil nilai dari masing-masing class
        var nominal_pengajuan = $row.find('.nominal_pengajuan').text();
        var realisasi = $row.find('.realisasi').text();
        var sisa = $row.find('.sisa').text();
        
        // Tampilkan atau gunakan nilai tersebut
        console.log('Nominal Pengajuan: ' + nominal_pengajuan);
        console.log('Realisasi: ' + realisasi);
        console.log('Sisa: ' + sisa);

		// Load the form for viewing catatan
		$.ajax({
			url: '<?php echo base_url(); ?>kasir/monitoring/konfirmasiApprovalTerimaSPJ',
			type: 'POST',
			data: {id_monitoring:id_monitoring,id_pengajuan_pemohon:id_pengajuan_pemohon, kode_dpsj:kode_dpsj, 
            nominal_pengajuan:nominal_pengajuan, realisasi:realisasi, sisa:sisa},
			beforeSend: function() {
				$('.loading-overlay').show();
			},
			success: function(data) {
				$('#data-approval').html(data);
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

<?php include(APPPATH.'views/style_table.php') ?>