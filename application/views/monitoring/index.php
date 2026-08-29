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
    <section class="contentx">
        <!-- Konten utama Anda di sini -->
         <input type="hidden" id="temp_page" value="0" style="display:nonex">
	<div class="containerx" style="padding:2px">
	    <div class="row">
	        <div class="post-search-panel col-lg-12 col-md-12 col-sm-12" style="padding:10px;">
				
                <h4 class="text-center" style="color:red; background: yellow;">u n d e r &nbsp; c o n s t r u c t i o n</h4>
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
                    <input type="text" id="filterNomor" onkeyup="filterTable()" placeholder="Filter Nomor Pengajuan..." 
                        style="padding: 3px; width: 250px; border: 1px solid #ccc; border-radius: 4px;">
    
                    <input type="text" id="filterUnit" onkeyup="filterTable()" placeholder="Filter Kode Unit..." 
                        style="padding: 3px; width: 150px; border: 1px solid #ccc; border-radius: 4px;">
				</div>

				 <?php
                //$array_status = array_status();
                $array_status = array();
                foreach($status_list as $row){
                    $array_status[$row['kode_status']] = $row['nama_status'];
                }
                $array_status['Disetujui'] = 'Disetujui';
				$array_status['Diretur'] = 'Diretur';
                
                // tentukan nilai select berdasarkan kode_status dari get
                if($kode_status != '' or $kode_status != 0){
                    $key = $kode_status;
                    $value = $array_status[$kode_status];
                } else if($kode_status == 'Semua' ){
                    $key = 'Semua';
                    $value = 'Semua Status';
                } else if($kode_status == 'Disetujui' ){
                    $key = 'Disetujui';
                    $value = 'Disetujui';
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
                
	            <select id="sortBy" onchange="searchFilter()" style="display:none">
	                <option value="">Sort By</option>
	                <option value="asc">Ascending</option>
	                <option value="desc">Descending</option>
	            </select>
                -->
	        </div>
	
	    </div>
        <div class="post-list" id="postList"></div>
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
        url: '<?php echo base_url(); ?>monitoring/data/0',
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
		$url = 'monitoring/data/'+page;
	}*/
    $url = "<?=base_url().'monitoring/data/'?>"+page;
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
    // saat halaman dimuat, jalankan toggle sidebar
    $(".sidebar-toggle").click();

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

	$(document).on("change", "#tahun, #bulan", function() {
		searchFilter();
	});
});

function filterTable() {// 1. Ambil nilai dari kedua input
    var inputNomor = document.getElementById("filterNomor").value.toUpperCase();
    var inputUnit = document.getElementById("filterUnit").value.toUpperCase();
    
    var table = document.querySelector(".styled-table");
    var tr = table.getElementsByTagName("tr");

    // 2. Loop semua baris (lewati header di indeks 0)
    for (var i = 1; i < tr.length; i++) {
        // Kolom nomor_pengajuan (indeks 1) dan kode_unit (indeks 2)
        var tdNomor = tr[i].getElementsByTagName("td")[1];
        var tdUnit = tr[i].getElementsByTagName("td")[2];
        
        if (tdNomor && tdUnit) {
            var textNomor = tdNomor.textContent || tdNomor.innerText;
            var textUnit = tdUnit.textContent || tdUnit.innerText;

            // 3. Logika AND: Cek apakah kedua kolom cocok dengan input masing-masing
            var matchNomor = textNomor.toUpperCase().indexOf(inputNomor) > -1;
            var matchUnit = textUnit.toUpperCase().indexOf(inputUnit) > -1;

            if (matchNomor && matchUnit) {
                tr[i].style.display = ""; // Tampilkan jika keduanya cocok
            } else {
                tr[i].style.display = "none"; // Sembunyikan jika salah satu tidak cocok
            }
        }
    }
}
</script>

<?php //$this->load->view('style_table'); ?>

<style>

        /* wrapper untuk scroll horizontal dan sticky header */
        .table-wrapper {
            overflow-x: auto;
            /*border-radius: 14px;
            border: 1px solid #e2e8f0;
            background: white;
            margin-bottom: 20px;
            position: relative;*/
        }

        .styled-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 1.25rem;
            min-width: 1800px;   /* Supaya scroll horizontal muncul karena banyak kolom */
            font-family: 'Inter', system-ui, 'Segoe UI', sans-serif;
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

        .styled-table thead th {
            background-color: #1e466e;
            color: white;
            font-weight: 600;
            padding: 12px 8px;
            border-bottom: 2px solid #0f2c44;
            white-space: nowrap;
            text-align: left;
            letter-spacing: 0.3px;
            position: relative;/**/
        }

        /* Efek bayangan agar header lebih jelas saat scroll
        .styled-table thead th::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: #ffd966;
        } */

        .styled-table tbody td {
            padding: 10px 8px;
            /*border-bottom: 1px solid #e2edf2;
            white-space: nowrap;*/
            border: 1px solid #d7dad6ff;
            color: #1f2a44;
            background-color: white; /* Harus ada background agar tidak transparan saat ditumpuk */
        }

        .styled-table tbody tr:hover {
            background-color: #f1f9fe;
            transition: 0.1s;
        }

        /* Responsif 
        @media (max-width: 720px) {
            body {
                padding: 12px;
            }
            .container {
                padding: 16px;
            }
            .search-area {
                flex-direction: column;
                align-items: stretch;
                border-radius: 24px;
            }
            .info-result {
                margin-left: 0;
                text-align: center;
            }
        }*/

        footer {
            /*font-size: 0.7rem;*/
            text-align: center;
            color: #6c86a3;
            padding: 16px 0 0 0;
            margin-top: 16px;
        }

        /* Indikator scroll */
        .scroll-indicator {
            text-align: right;
            /*font-size: 0.7rem;*/
            color: #6c86a3;
            margin-bottom: 8px;
        }
        /* Logika Sticky Kolom (1 sampai 6) */
    /* nth-child(1) s/d nth-child(6) adalah Tanggal sampai Nominal Pengajuan */
    .styled-table th:nth-child(-n+6),
    .styled-table td:nth-child(-n+6) {
        position: sticky;
        z-index: 5;
    }
    
    /* Pengaturan Lebar Kolom agar Konsisten (PENTING) */
    .col-1 { min-width: 100px; width: 100px; } /* tanggal */
    .col-2 { min-width: 180px; width: 180px; } /* nomor_pengajuan */
    .col-3 { min-width: 80px;  width: 80px;  } /* kode_unit */
    .col-4 { min-width: 60px;  width: 60px;  } /* form */
    .col-5 { min-width: 200px; width: 200px; } /* uraian */
    .col-6 { min-width: 130px; width: 130px; } /* nominal_pengajuan */

    /* Perhitungan Akumulasi Left Offset */
    .styled-table th:nth-child(1), .styled-table td:nth-child(1) { left: 0; }
    .styled-table th:nth-child(2), .styled-table td:nth-child(2) { left: 100px; } /* col1 */
    .styled-table th:nth-child(3), .styled-table td:nth-child(3) { left: 280px; } /* col1+2 */
    .styled-table th:nth-child(4), .styled-table td:nth-child(4) { left: 360px; } /* col1+2+3 */
    .styled-table th:nth-child(5), .styled-table td:nth-child(5) { left: 420px; } /* col1+2+3+4 */
    .styled-table th:nth-child(6), .styled-table td:nth-child(6) { 
        left: 620px; /* col1+2+3+4+5 */
        border-right: 3px solid #000; /* Garis pembatas tebal */
    }

    /* Menaikkan z-index untuk header yang juga sticky kolom */
    .styled-table thead th:nth-child(-n+6) {
        z-index: 15;
    }

    /* HEADER KHUSUS KOLOM STICKY */
    /* Harus punya z-index paling tinggi agar tidak tertimpa header lain saat scroll diagonal */
    .styled-table thead th:nth-child(-n+6) {
        z-index: 30;
        background-color: #007d63; 
    }

    /* Baris saat di-hover (opsional) */
    .styled-table tbody tr:hover td {
        background-color: #f1f1f1;
    }
</style>