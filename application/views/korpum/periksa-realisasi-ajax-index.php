
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
      <small>Daftar Realisasi</small>
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
	            <input type="text" id="keywords" placeholder="cari nomor pengajuan.." onkeyup="searchFilter()"/>
	                    
	            <input type="hidden" id="temp_page" value="">

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
	            
				 <?php
                //$array_status = array_status();
                $array_status = array();
                foreach($status_list as $row){
                    $array_status[$row['kode_status']] = $row['nama_status'];
                }
                $array_status['Disetujui'] = 'Disetujui';
				$array_status['Diretur'] = 'Diretur';
				$array_status['Semua'] = 'Semua';
                
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
                
                <select id="status" onchange="searchFilter()">
                    <!--<option value="Semua">Semua Status</option>--> 
                    <?php foreach($array_status as $k => $v){ ?>
                        <option value="<?php echo $k; ?>" <?php if($key == $k) echo 'selected'; ?>><?php echo $v; ?></option>
                    <?php } ?>
                </select>
                
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

<!-- modal catatan -->
<div class="modal fade" id="modal-catatan" tabindex="-1" role="dialog" aria-labelledby="viewcatatanModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title text-center" id="catatan-title">Catatan</h4>
			</div>
			<div class="modal-body" style="text-align:center;justify-content:center; align-items:center;">
				<div class="row" style="width:100%;">
					<div class="col-md-12" id="data-rincian" style="font-size:12px; font-family:Arial;text-align:center; margin:0 auto;"></div>
				</div>
				<div class="row" style="width:100%;">					
					<div class="col-md-12" id="data-catatan"></div>
					<input type="hidden" id="korpum_waktu">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal" aria-label="Close">Tutup</button>
			</div>
		</div>
	</div>
</div>

<!-- modal approval -->
<div class="modal fade" id="modal-approval" tabindex="-1" role="dialog" aria-labelledby="viewApprovalModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title text-center" id="approval-title">Approval</h4>
			</div>
			<div class="modal-body" style="text-align:center;justify-content:center; align-items:center;">
				<div class="row" style="width:100%;">					
					<div class="col-md-12" id="data-approval"></div>
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
    var status = $('#status').val();
	var tahun = $("#tahun").val();
	var bulan = $("#bulan").val();
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>korpum/periksa_realisasi_ajax/data/0',  //url: '<?php echo base_url(); ?>koordinator/ajax/'+page_num,
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

function searchFilter() {
	var keywords = $('#keywords').val();
	var status = $('#status').val();
	var sortBy = $('#sortBy').val();
	var page = 0; // reset to first page
	getDataPage(page);
}

function getDataPage(page){              
	var keywords = $('#keywords').val();
	var sortBy = $('#sortBy').val();
	var status = 0;
	$url = "<?=base_url().'korpum/periksa_realisasi_ajax/data/'?>"+page;
	
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

$(document).ready(function()
{
	$(document).on("click", ".view-realisasi", function() {
		var id = $(this).data('id');
		var nomor_pengajuan = $(this).data('nomor_pengajuan');

		$('#data-realisasi').html("loading...");
		$('#data-rincian').html("");

		// Load the form for creating a new realisasi
		$.ajax({
			url: '<?php echo base_url(); ?>verifikator/monitoring/view',
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

	$(document).on("click", ".periksa-realisasi", function() {
		$('#data-realisasi').html("loading...");
		$('#data-rincian').html("");

		var id = $(this).data('id');
		var id_monitoring = $(this).data('id_monitoring');
		var nomor_pengajuan = $(this).data('nomor_pengajuan');
		// Load the form for creating a new realisasi
		$.ajax({
			url: '<?php echo base_url(); ?>korpum/periksa_realisasi/periksa',
			type: 'POST',
			data: {id:id, id_monitoring:id_monitoring, nomor_pengajuan: nomor_pengajuan},
			beforeSend: function() {
				$('.loading-overlay').show();
			},
			success: function(data) {
				$('#data-realisasi').html(data);
				$('.loading-overlay').fadeOut("slow");
			},
			error: function() {
				alert('Error loading form for periksa realisasi.');
				$('.loading-overlay').fadeOut("slow");
			},
		});
	});

	$(document).on("click", ".view-catatan", function() {
		var id = $(this).data('id');

		$('#data-catatan').html("loading...");
		$('#data-rincian').html("");

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
		$('#data-rincian').html("");

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

	$(document).on("click", ".lanjut-proses", function() {
		//set variabel
        var id_monitoring = $(this).data('id_monitoring');
        var id_pengajuan_pemohon = $(this).data('id_pengajuan_pemohon');
        var form = $(this).data('form');
		var i = 0;

		// ambil data realisasi dari tabel
		var id = $(this).closest('tr').next('tr').find('td.realisasi').map(function() {
			return $(this).data("id");
		}).get().join(', ');
		var realisasi = $(this).closest('tr').next('tr').find('td.realisasi').map(function() {
			return $(this).text();
		}).get().join(', ');

		// ambil data rincian dari tabel
		var data_rincian_asli = $(this).closest('tr').next('tr').find('.detail-row-rincian').find('#tabel');
		
		var $table = data_rincian_asli.clone();
		$table.find('tr').each(function(index) {
    	var $row = $(this);
    
			// Baris pertama (header)
			if (index === 0) {
				//$row.find('th').eq(-1).remove(); // Hapus kolom terakhir
				$row.find('th').eq(-1).remove(); // Hapus kolom kedua dari terakhir
			}
			// Baris data
			else if ($row.hasClass('data-row-realisasi')) {
				$row.find('td').eq(-1).remove(); // Hapus tombol view
				$row.find('td').eq(-1).remove(); // Hapus tombol Periksa
			}
			// Baris TOTAL (terakhir)
			else if (index === $table.find('#tabel tr').length - 1) {
				//$row.find('td').eq(-1).remove();
				//$row.find('td').eq(-1).remove();
			}
		});

		//data_rincian = $tableContent.html();
		$('#data-rincian').html("loading...");
		var centeredTable = $('<div style="margin:0 auto; width:fit-content;"></div>').append($table);
		$("#data-rincian").html(centeredTable);
		
		console.log(centeredTable.html());

		// Load the form for viewing catatan
		$('#data-catatan').html("loading...");
		$.ajax({
			url: '<?php echo base_url(); ?>korpum/periksa_realisasi/konfirmasiLanjutProses',
			type: 'POST',
			data: {id_monitoring:id_monitoring,id_pengajuan_pemohon:id_pengajuan_pemohon, realisasi:realisasi, id:id, form:form},
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
</script>

<?php include(APPPATH.'views/style_table.php') ?>
