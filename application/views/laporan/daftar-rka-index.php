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
                    
                    <!--<label for="pagu" class=" control-label text-right">Pagu</label>
                    <select name="pagu" id="pagu" class="form-control" >
                        <option value="">Pilih Pagu</option>
                        <option value="All">All</option>
                        <option value="fixed">Fixed Cost (Procost Remun + Umum)</option>
                        <option value="unit" selected >Procost Unit</option>
                        <option value="Procost Remun">Procost Remun</option>
                        <option value="Procost Umum">Procost Umum</option>
                    </select>-->

                <?php
                $array_role = array('pum');
                $array_username = array('PUM_REMUN', 'pum_remun', 'tiwi.gunarti');
                if(in_array($this->session->userdata('logged_anggaran')['role'], $array_role)){
                    if(in_array($this->session->userdata('logged_anggaran')['username'], $array_username)){
                        ?>
                        <select name="pagu" id="pagu" class="form-control" >
                            <option value="">Pilih Pagu</option>
	                        <option value="All">All</option>
	                        <option value="fixed">Fixed Cost (Procost Remun + Umum)</option>
	                        <option value="unit" >Procost Unit</option>
	                        <option value="Procost Remun" selected >Procost Remun</option>
	                        <option value="Procost Umum">Procost Umum</option>
                        </select>
                <?php
                    } else {
                        ?>
                        <input type="text" id="pagu" value="unit" placeholder="Filter Pagu.." class="table-search-filters" style="display: none;">
                        <?php
                    }
                } else {
                ?>
                    <select name="pagu" id="pagu-search" class="form-controlx table-search-filters" onchange="search()" >
                        <option value="">Pilih Pagu</option>
                        <option value="All">All</option>
                        <option value="fixed">Fixed Cost (Procost Remun + Umum)</option>
                        <option value="unit" selected >Procost Unit</option>
                        <option value="Procost Remun">Procost Remun</option>
                        <option value="Procost Umum">Procost Umum</option>
                    </select>
                <?php
                }
                ?>
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
    var pagu = $('#pagu').val(); 
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>daftar_rka/data_per_dpsj',  //url: '<?php echo base_url(); ?>koordinator/ajax/'+page_num,
		data: {tahun:tahun, pagu:pagu},
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
        var pagu = $('#pagu').val(); 

		// ambil data form edit pengajuan menggunakan AJAX
        $.ajax({
            url: "<?=base_url()?>daftar_rka/data_per_dpsj",
            type: "POST",
            data: {tahun: tahun, pagu: pagu},
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
		var pagu = $("#pagu").val();
        
		// ambil data form edit pengajuan menggunakan AJAX
        $.ajax({
            url: "<?=base_url()?>daftar_rka/detail_akun",
            type: "POST",
            data: {tahun: tahun, kode_dpsj:kode_dpsj, pagu: pagu},
            success: function(response) {
                // Tampilkan pesan sukses atau lakukan tindakan lain                
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
		var tahun = $("#tahun").val();
        
		// ambil data form edit pengajuan menggunakan AJAX
        $.ajax({
            url: "<?=base_url()?>daftar_rka/detail_mutasi",
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

	$(document).on("change","#pagu", function(){
		var tahun = $("#tahun").val();
		var kode_dpsj = $(this).data("kode_dpsj");
		var pagu = $(this).val();
		console.log(tahun + ' ' + pagu);

		// ambil data form edit pengajuan menggunakan AJAX
        $.ajax({
            url: "<?=base_url()?>daftar_rka/data_per_dpsj",
            type: "POST",
            data: {tahun: tahun, kode_dpsj: kode_dpsj, pagu: pagu},
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

	$(document).on("click",".detail-all", function(){
		var kode_dpsj = $(this).data("kode_dpsj");
		var tahun = $("#tahun").val();
		var pagu = $("#pagu").val();
        //console.log(kode_dpsj+' '+tahun+' '+pagu);return false;
        post_to_url("<?=base_url()?>daftar_rka/detail_akun_dpsj", {tahun: tahun, kode_dpsj:kode_dpsj, pagu: pagu}, "POST");
	});
});

// untuk menampilkan di halaman yang baru
function post_to_url(path, params, method) {
	method = method || "post";

	var form = document.createElement("form");
	form.setAttribute("method", method);
	form.setAttribute("action", path);
    form.setAttribute("target", "_blank") ;

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