<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      <?= $title ?>
      <small>Daftar RKA per DPSJ</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active"><?= $title ?></li>
    </ol>
			<b style="color:red">- UNDER CONSTRUCTION -</b>
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Konten utama Anda di sini -->

<!-- modal rincian akun -->
<div class="modal fade" id="modal-akun" tabindex="-1" role="dialog" aria-labelledby="viewAkunModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="akun-title">RINCIAN AKUN</h4>
			</div>
			<div class="modal-body" style="overflow:auto">
				<div id="data-akun">
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
            <div class="row text-center">
                <div class="form-group form-inline col-sm-12">
                    <label for="tahun" class=" control-label text-right">Tahun</label>
                    <select name="tahun" id="tahun" class="form-control" >
                        <?php echo optTahun($tahun); ?>
                    </select>
                    <!--<label for="bulan" class=" control-label text-right">Bulan</label>-->
                    <select name="bulan" id="bulan" class="form-control"  style="display: none;">
                        <?php echo optBulan($bulan); ?>
                    </select>
                </div>                                   
            </div>
            <div class="text-center">
                <input type="text" id="kode_dpsj-search" onkeyup="search()" placeholder="Filter Kode DPSJ.." class="table-search-filters">
                <input type="text" id="kode_kegiatan-search" onkeyup="search()" placeholder="Filter Procost.." class="table-search-filters">
                <input type="text" id="kode_akun-search" onkeyup="search()" placeholder="Filter Akun.." class="table-search-filters">
                
                <?php
                $array_role = array('pum');
                $array_username = array('PUM_REMUN', 'tiwi.gunarti');
                if(in_array($this->session->userdata('logged_anggaran')['role'], $array_role)){
                    if(in_array($this->session->userdata('logged_anggaran')['username'], $array_username)){
                        ?>
                        <select name="pagu" id="pagu-search" class="form-controlx table-search-filters" onchange="search()">
                            <option value="">Pilih Pagu</option>
                            <option value="unit">Procost Unit</option>
                            <option value="Procost Remun" selected >Procost Remun</option>
                            <option value="Procost Umum">Procost Umum</option>
                        </select>
                <?php
                    } else {
                        ?>
                        <input type="text" id="pagu-search" value="Procost Unit" placeholder="Filter Pagu.." class="table-search-filters" style="display: none;">
                        <?php
                    }
                } else {
                ?>
                    <select name="pagu" id="pagu-search" class="form-controlx table-search-filters" onchange="search()" >
                        <option value="">Pilih Pagu</option>
                        <option value="unit" selected >Procost Unit</option>
                        <option value="Procost Remun">Procost Remun</option>
                        <option value="Procost Umum">Procost Umum</option>
                    </select>
                <?php
                }
                ?>
            </div>
            <div class="row">
                <div class="boxx col-md-12 col-lg-12">
                    <div class="post-list" id="postList"></div>
                </div>
            </div>
		</section>
</div>

<script>
fetch_data()
function fetch_data()  
{  
    page_num = 0;
    var keywords = "";
    var sortBy = "DESC";
    var tahun = $('#tahun').val(); 
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>daftar_rka/data_akun',  //url: '<?php echo base_url(); ?>koordinator/ajax/'+page_num,
		data: {tahun:tahun},
        //data:'page='+page_num+'&keywords='+keywords+'&sortBy='+sortBy+'&status='+status,
        beforeSend: function () {
            $('.loading-overlay').show();
        },
        success: function (html) {
            $('#postList').html(html);
            $('.loading-overlay').fadeOut("slow");
        }
    });
}


$(document).ready(function()
{
	$(document).on("change","#tahun", function(){
		var tahun = $(this).val();

		// ambil data form edit pengajuan menggunakan AJAX
        $.ajax({
            url: "<?=base_url()?>daftar_rka/data_akun",
            type: "POST",
            data: {tahun: tahun},
            success: function(response) {
                // Tampilkan pesan sukses atau lakukan tindakan lain
                //alert("Data berhasil disimpan!");
                $("#postList").html(response);
                console.log(response);
            },
            error: function(xhr, status, error) {
                // Tampilkan pesan kesalahan
                //alert("Terjadi kesalahan saat ...");
                //console.log(error);
            }
        });
	});

	$(document).on("click",".detail", function(){
		var kode_dpsj = $(this).data("kode_dpsj");
		var tahun = $("#tahun").val();
console.log(kode_dpsj+' '+tahun);
		// ambil data form edit pengajuan menggunakan AJAX
        $.ajax({
            url: "<?=base_url()?>daftar_rka/detail_akun",
            type: "POST",
            data: {tahun: tahun, kode_dpsj:kode_dpsj},
            success: function(response) {
                // Tampilkan pesan sukses atau lakukan tindakan lain
                //alert("Data berhasil disimpan!");
                $("#data-akun").html(response);
                console.log(response);
            },
            error: function(xhr, status, error) {
                // Tampilkan pesan kesalahan
                //alert("Terjadi kesalahan saat ...");
                //console.log(error);
            }
        });
	});

	$(document).on("click",".detail_komitmen_aktual", function(){
		var tahun = $("#tahun").val();
		var kode_kegiatan = $(this).data("kode_kegiatan");
		var kode_akun = $(this).data("kode_akun");
		var kode_dana = $(this).data("kode_dana");
        
		// ambil data form edit pengajuan menggunakan AJAX
        $.ajax({
            url: "<?=base_url()?>daftar_rka/detail_akun_komitmen_aktual",
            type: "POST",
            data: {tahun: tahun, kode_kegiatan:kode_kegiatan, kode_akun:kode_akun, kode_dana:kode_dana},
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

	$(document).on("click",".detail-mutasi", function(){
		var kode_dpsj = $(this).data("kode_dpsj");
		var kode_kegiatan = $(this).data("kode_kegiatan");
		var kode_akun = $(this).data("kode_akun");
		var kode_dana = $(this).data("kode_dana");
		var tahun = $("#tahun").val();
console.log(kode_dpsj+' '+tahun);
		// ambil data form edit pengajuan menggunakan AJAX
        $.ajax({
            url: "<?=base_url()?>daftar_rka/detail_akun_mutasi",
            type: "POST",
            data: {tahun: tahun, kode_dpsj:kode_dpsj, kode_kegiatan:kode_kegiatan, kode_akun:kode_akun, kode_dana:kode_dana},
            success: function(response) {
                // Tampilkan pesan sukses atau lakukan tindakan lain
                //alert("Data berhasil disimpan!");
                $("#data-akun").html(response);
                console.log(response);
            },
            error: function(xhr, status, error) {
                // Tampilkan pesan kesalahan
                //alert("Terjadi kesalahan saat ...");
                //console.log(error);
            }
        });
	});
});
</script>

<style>
.table-search-filters {
    margin: 5px;
	width: 15%;
	height: 30px;
	margin-bottom: 5px;
	padding: 5px;
	border: 1px solid #ddd;
	border-radius: 4px;
	background-color: #f9f9f9;

}
</style>