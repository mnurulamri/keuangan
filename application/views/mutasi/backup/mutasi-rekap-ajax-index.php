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
	        <div class="post-search-panel">
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
                    <input type="text" id="nomor_pengajuan-search" onkeyup="search()" placeholder="Filter Nomor Pengajuan" class="table-search-filters">
                    <input type="text" id="dpsj_pengaju-search" onkeyup="search()" placeholder="Filter DPSJ Pengaju" class="table-search-filters">
				</div>
	        </div>
	
	    </div>
	    <div class="post-list" id="postList"></div>
	</div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>

fetch_data();

$(document).ready(function() {
    // saat halaman dimuat, jalankan toggle sidebar
    $(".sidebar-toggle").click();
    
	$(document).on("change", "#tahun, #bulan", function() {		
		searchFilter();
	});
});

function fetch_data()  
{  
    page_num = 0;
    var keywords = "";
    var sortBy = "DESC";
    var status = $('#status').val();
	var tahun = $("#tahun").val();
	var bulan = $("#bulan").val();
	$('#postList').html('loading...');
    
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>mutasi_rekap/data/0',  //url: '<?php echo base_url(); ?>koordinator/ajax/'+page_num,
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
    var tahun = $('#tahun').val();
    var bulan = $('#bulan').val();
	var string_path = window.location.pathname;
	var last_string_path = string_path.slice(-1);
	if(last_string_path == '/'){
		$url = 'data/'+page;
	} else {
		$url = 'mutasi_rekap/data/'+page;
	}
	$('#postList').html('loading...');
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
    var tahun = $('#tahun').val();
    var bulan = $('#bulan').val();
	getDataPage(page);
}

</script>

<style type="text/css">

/* Tables */


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

.styled-table {
   border-collapse: collapse;
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
.styled-table tbody tr:nth-of-type(even) {
   background-color: #f3f3f3;
}
.styled-table tbody tr:last-of-type {
   border-bottom: 2px solid #43A5BE;
}
.styled-table tbody tr.active-row {
   font-weight: bold;
   color: #43A5BE;
}

    .resize-wrapper {
        resize: both;
        overflow: auto;
        border: 1px solid #ccc;
        min-width: 200px;
        min-height: 200px;
        padding: 10px;
    }
    
    .table-container {
        width: 100%;
        overflow-x: auto;
    }
    
    .styled-table {
        width: 100%;
        border-collapse: collapse;
    }
</style>

<?php //include(APPPATH.'views/style_table.php') ?>