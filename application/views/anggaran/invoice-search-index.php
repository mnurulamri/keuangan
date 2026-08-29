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
         <input type="hidden" id="temp_page" value="0" style="display:nonex">
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
	                    <select name="bulan" id="bulan" class="form-control" onchange="searchTable()">
	                        <?php echo $opt_bulan; ?>
	                    </select>
	                </div>                                   
	            </div>
	            <div class="row text-center">
					<label for="keywords" class=" control-label text-right">Nomor Pengajuan</label>
	            	<input type="text" id="searchInput" placeholder="cari Nomor Pengajuan.." onkeyup="searchTable()"/>
				</div>
	        </div>	
	    </div>
        <div class="text-center">
            <button class="btn btn-primary btn-sm" id="buat-procost">Buat Procost</button>
        </div>
	    <div class="row">
	        <div class="box col-md-12 col-lg-12">
	            <div class="box-body" style="overflow:auto">
	                
	                <div class="loading-overlay"><div class="overlay-content">Loading.....</div></div>
	                <div class="post-list table-container" id="postList"></div>
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
			<div class="modal-body" style="overflowx:autox">
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

<script>
fetch_data();
searchTable();
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
        url: '<?php echo base_url(); ?>invoice/search_data',
        data:'page='+page_num+'&keywords='+keywords+'&sortBy='+sortBy+'&status='+status+'&tahun='+tahun+'&bulan='+bulan,
        beforeSend: function () {
            $('.loading-overlay').show();
        },
        success: function (html) {
            $('#postList').html(html);
            $('.loading-overlay').fadeOut("slow");
            // saat halaman dibuat, lakukan fungsi searchTable untuk menampilkan data sesuai filter
            searchTable();
        }
    });
}

function getDataPage(page){              
	var keywords = $('#keywords').val();
	var sortBy = $('#sortBy').val();
    
    $url = "<?php echo base_url(); ?>invoice/search_data";
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
    // sebelum di approval, tampilkan data pengajuan melalui form konfirmasi
	$(document).on("click",".approval", function(){
        var id_monitoring= $(this).data("id_monitoring");
		var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
        var kode_dpsj = $(this).data("kode_dpsj");
        
        var nama_form = $(this).data("nama_form");

        // set title modal approval
		$("#approval-title").html('<div class="text-info text-center"><strong>Proses Verifikasi Anggaran</strong></div>');

		// ambil data form edit pengajuan menggunakan AJAX
        $.ajax({
            url: "<?=base_url()?>index.php/unit_anggaran/monitoring/konfirmasiApproval",
            type: "POST",
            data: {id_monitoring:id_monitoring, id_pengajuan_pemohon: id_pengajuan_pemohon, kode_dpsj: kode_dpsj,  nama_form: nama_form},
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
		$("#approval-title").html('<div class="text-danger text-center"><strong>Data Pengajuan Sudah Disetujui Unit Anggaran</strong></div>');

		// ambil data form edit pengajuan menggunakan AJAX
        $.ajax({
            url: "<?=base_url()?>index.php/unit_anggaran/monitoring/detailApproval",
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

    $(document).on("click", ".simpan-tgl-terima", function() {
        // Simpan tanggal terima
        var id = $(this).data("id");
        var tgl_terima = $("#input_tgl_terima_"+id).val();
        if (!tgl_terima) {
            alert("Tanggal terima harus diisi.");
            return;
        }
        $.ajax({
            url: "<?=base_url()?>index.php/unit_anggaran/monitoring/simpanTanggalTerima",
            type: "POST",
            data: {id: id, tgl_terima: tgl_terima},
            success: function(response) {
                alert(response);
                // Update tampilan atau lakukan tindakan lain jika perlu
                $("#input_tgl_terima_"+id).val(tgl_terima); // Update input field dengan tanggal yang baru   
                console.log(response);             
            },
            error: function(xhr, status, error) {
                alert("Gagal menyimpan tanggal terima.");
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
	
	$(document).on("click", ".view-realisasi", function() {
	    $('#data-realisasi').html('loading...');
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

	/*$(document).on("change", "#tahun, #bulan", function() {
		searchFilter();
	});*/
});
</script>

<script>
function searchTable() {
    document.querySelectorAll('[id^="detail-"]').forEach(el => el.style.display = 'none');
    var input = document.getElementById("searchInput");
    var filter = input.value.toLowerCase();
    var inputBulan = document.getElementById("bulan");
    var filterBulan = inputBulan.value.toLowerCase();
    var table = document.getElementById("examplex");
    var tr = table.getElementsByClassName("row-utama");
    
    var counter = 1; // Mulai hitungan dari 1

    for (var i = 0; i < tr.length; i++) {
        var tdNomor = tr[i].getElementsByTagName("td")[3]; // Kolom Nomor Pengajuan
        var tdNorut = tr[i].getElementsByClassName("col-norut")[1]; // Kolom Norut        
        var tdBulan = tr[i].getElementsByTagName("td")[13]; // Kolom bulan (misal di kolom ke-4, sesuaikan dengan posisi sebenarnya)

        
        if (tdNomor && tdBulan) {
            var txtValue = tdNomor.textContent || tdNomor.innerText;
            var isMatch = txtValue.toLowerCase().indexOf(filter) > -1;
            var isMatchBulan = tdBulan.textContent.toLowerCase().indexOf(filterBulan) > -1;
            
            // 1. Sembunyikan/Tampilkan baris utama
            tr[i].style.display = isMatch && isMatchBulan ? "" : "none";
            
            /*// 2. Sembunyikan baris detail terkait jika baris utama disembunyikan
            var idKey = tr[i].querySelector('button').getAttribute('data-id_pengajuan_pemohon');
            var detailRow = document.getElementById('detail-' + idKey);
            if (detailRow) {
                detailRow.style.display = isMatch ? (detailRow.style.display === 'table-row' ? 'table-row' : 'none') : 'none';
            }
            
            // 3. Update Nomor Urut jika baris sedang tampil
            if (isMatch) {
                tdNorut.textContent = counter;
                counter++;
            }*/
        }
    }
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

<?php include(APPPATH.'views/style_table.php') ?>


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

/* Mengatur kontainer agar memiliki tinggi maksimal dan scroll */
    .table-container {
        max-height: 500px; /* Atur tinggi sesuai kebutuhan */
        overflow-y: auto;
        border: 1px solid #ddd;
    }

    .styled-table {
        border-collapse: collapse;
        width: 100%;
    }

    /* Membuat header tetap di atas */
    .styled-table thead th {
        position: sticky;
        top: 0;
        background-color: #43A5BE; /* Warna background agar tidak transparan */
        z-index: 10; /* Agar header di atas isi tabel */
        box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4); /* Opsional: memberi garis bawah halus */
    }

    /* Khusus untuk rincian biaya yang Anda buat (sub-tabel) */
    /* Jika ingin header sub-tabel juga sticky (opsional) */
    #tabel tr:first-child th {
        position: sticky;
        top: 40px; /* Sesuaikan dengan tinggi header utama */
        background: #4bc2cb;
        z-index: 9;
    }
</style>