<?php
if (!$this->session->userdata('kode_bidang')) {
    //redirect('auth/unit_kerja');
}
//echo '<pre>';print_r($pejabat);echo '</pre>';
?>

                
                <!--<div class="panel panel-default">-->
                    <!--<div class="panel-body">-->
                        <div class="boxx box-primary">                            
                            <div class="box-header with-border text-center">
                                <div class="panel-title"><b>Data Pemohon</b></div>
                            </div>
                            <div class="box-body">
                                <!-- data pemohon -->
                                 <table id="pemohon">                                    
                                    <tr>
                                        <td width="20%" class="label">Nomor Pengajuan</td>
                                        <td>: </td>
                                        <td width="30%"> <?= $preview_nomor ?></td>
                                        <td width="20%" class="label">Penanggung Jawab</td>
                                        <td>: </td>
                                        <td width="30%"> <?= $pejabat[0]['nama'] ?></td>
                                    </tr>
                                    <tr>
                                        <td width="20%" class="label">Tanggal Pengajuan</td>
                                        <td>: </td>
                                        <td width="30%"> <?= dateTimeToTanggal($pejabat[0]['tgl_diajukan']) ?></td>
                                        <td width="20%" class="label">NPM/NIP/NUP</td>
                                        <td>: </td>
                                        <td width="30%"> <?= $pejabat[0]['nip'] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="label">PAF/Dept/Prog/Unit</td>
                                        <td>: </td>
                                        <td> <?= $nama_unit ?></td>
                                        <td class="label">No Telepon</td>
                                        <td>: </td>
                                        <td> <?= $pejabat[0]['telp'] ?></td>
                                    </tr>
                                 </table>
                            </div>
                        </div>

                        <hr>
                        <!--<div class="box box-primary">
                            <div class="box-header with-border text-center">
                                <h3 class="box-title">Rincian Pembayaran</h3>
                            </div>
                            <div class="box-body" style="overflow:auto">-->
                                <div class="row" style="width:99%; margin:0 auto;">

                                    <div class="col-sm-12 kotakx">
                                        
                                      <div class="box-header with-border text-center" style="line-height:7px;"><b>Rincian Pembayaran</b></div>
                                        <br>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="uraian" class="col-sm-3 control-label text-right" style="color:#555">Untuk</label>
                                                <div class="col-sm-9">
                                                    <?=$untuk?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <label for="untuk_nama" class="col-sm-3 control-label text-right" style="color:#555">Atas Nama</label>
                                                <div class="col-sm-9">
                                                    <?= $array_dpsj[0]['deskripsi_dpsj'] ?>
                                                </div>
                                            </div>
                                        </div>
                                        <!--
                                        <div class="form-group">
                                            <label for="untuk_nama">Untuk dan Atas Nama</label>
                                            <input type="text" class="form-control" id="uraian" name="uraian" required>
                                        </div>
                                        -->
                                        <input type="hidden" class="form-control" id="uraian" name="uraian">

                                        <div style="line-height:14x;">&nbsp;</div>

                                        <input type="hidden" id="id" value="0" >
                                        <input type="hidden" id="newId" value="0" >
                                        <input type="hidden" id="kode_dpsj" value="<?=$array_dpsj[0]['kode_dpsj']?>">
                                        <input type="hidden" id="kode_bidang" value="<?=$kode_bidang?>">

                                        <!-- Di bagian Rincian Pembayaran -->
                                        <div style="overflow:auto;">
                                            <table class="tabel" id="tabel-rincian">
                                                <thead>
                                                    <tr style="color:#555">
                                                        <th width="5%">No</th>
                                                        <th width="25%" colspan="2">Nomor dan Nama Project Costing</th>
                                                        <th width="25%" colspan="3">Nomor dan Nama Akun</th>
                                                        <th width="15%">Jumlah (Rp)</th>
                                                        <th width="15%">Jumlah Disetujui</th>
                                                        <th width="20%">Keterangan</th>
                                                        <th width="5%">Sisa Anggaran</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $rowCount = 1;
                                                    $newId = 1;
                                                    
                                                    $kode_dpsj = $array_dpsj[0]['kode_dpsj'];
                                                    $nominal_pengajuan = 0;
                                                    foreach($rincian as $row){

                                                        // Inisialisasi variabel untuk menghitung sisa anggaran  
                                                        $kode_kegiatan = $row['kode_kegiatan'];
                                                        $kode_akun = $row['kode_akun'];
                                                        $kode_dana = $row['kode_dana'];

                                                        // Ambil sisa anggaran dari database
                                                        
                                                        $sisa = 0;
                                                        echo '
                                                        <tr>
                                                            <td id="'.$row['id'].'">'.$rowCount.'</td>
                                                            <td class="kode-kegiatan" id="kode_kegiatan_'.$newId.'">'.$row['kode_kegiatan'].'</td>
                                                            <td class="nama-kegiatan" data-id="'.$newId.'">'.$row['nama_kegiatan'].'</td>
                                                            <td class="kode-akun" id="kode_akun_'.$newId.'">'.$row['kode_akun'].'</td>
                                                            <td class="deskripsi-akun" id="akun_'.$newId.'" data-id="'.$newId.'">'.$row['deskripsi_akun'].'</td>
                                                            <td class="kode-dana" id="dana_'.$newId.'">'.$row['kode_dana'].'</td>
                                                            <td class="jumlah" id="jumlah_'.$newId.'">'.number_format($row['komitmen']).'</td>
                                                            <td>
                                                                '.number_format($row['komitmen']).'
                                                            </td>                                                        
                                                            <td class="keterangan">'.$row['keterangan'].'</td>
                                                            <td class="sisa_anggaran" id="sisa_anggaran_'.$newId.'" data-sisa_anggaran="'.$sisa_anggaran[$kode_dpsj][$kode_kegiatan][$kode_akun][$kode_dana].'">'.$sisa_anggaran[$kode_dpsj][$kode_kegiatan][$kode_akun][$kode_dana].'</td>

                                                        </tr>';
                                                        $rowCount++;
                                                        $newId++;    
                                                        $nominal_pengajuan += $row['komitmen'];                                                
                                                    }
                                                    ?>
                                                    <script>$("#newId").val(<?=$newId?>);</script>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="6" class="text-right"><b>Total: </b></td>
                                                        <td class="total"><?=number_format($nominal_pengajuan)?></td>
                                                        <td class="total-disetujui"><?=number_format($nominal_pengajuan)?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="9" style="border: 1px solid #fff">             
                                                            
                                                        </td>
                                                    </tr>
                                                    <!--
                                                    <tr>
                                                        <td colspan="10" class="text-info" style="border: 1px solid #fff">
                                                            <ul>
                                                                <li><i>double klik pada kolom nama project costing, nama akun, jumlah dan keterangan untuk edit</i></li>
                                                            </ul>
                                                        </td>
                                                    </tr>
                                                    -->
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <!--</div>
                        </div>-->
                                                    <hr>
                    <div class="row" style="width:99%; margin:0 auto;">

                        <div class="col-sm-12 kotakx">
                            <div class="box-header with-border text-center" style="line-height:7px"><b>Approval</b></div>
                            <br>
                            <div id="approvalForm" class="text-center">
                                <div class="form-group">
                                    <label for="keterangan_anggaran_umko" style="color:#555">Disetujui Unit Anggaran Tanggal</label>
                                    <div class="form-control" id="anggaran_keterangan" name="anggaran_keterangan" rows="3"><?=$anggaran_tgl_disetujui?></div>
                                </div>
                                <div class="form-group">
                                    <label for="keterangan_anggaran_umko" style="color:#555">Keterangan</label>
                                    <div class="form-control" id="anggaran_keterangan" name="anggaran_keterangan" rows="3"><?=$anggaran_keterangan?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!--</div>-->
                

<script>
$(document).ready(function()
{
    // Format input jumlah    
    $(document).on("click", ".jumlah-disetujui", function(){
        $(this).select();
        $("#id").val($(this).data('id'));
    });

    //$(document).on("keyup", ".input-jumlah", function(evt){
    $(".jumlah-disetujui").keyup(function(evt){
        
        // Cek apakah inputan hanya berisi angka
        let keyCode = $(this).val();
        let value = keyCode.replace(/[^\d.]/g, '');
        let jumlah = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        $(this).val(jumlah);
        
        // hitung sisa anggaran
        var id = $("#id").val();
        var kode_dpsj = $("#kode_dpsj").val();
        var kode_kegiatan = $("#kode_kegiatan_" + id).text();
        var kode_akun = $("#kode_akun_" + id).text();
        var kode_dana = $("#dana_" + id).text();
        var temp_jumlah = $("#jumlah_" + id).text().replace(/,/g, '');
        // Ambil sisa anggaran dari data atribut
        var sisa_anggaran = parseFloat($("#sisa_anggaran_" + id).data('sisa_anggaran').replace(/,/g, '')) + parseFloat(temp_jumlah) - parseFloat(value.replace(/,/g, ''));
        
        // Update sisa anggaran
        $("#sisa_anggaran_" + id).text(sisa_anggaran.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        
        // hitung total
        var total = 0;
        $(".jumlah-disetujui").each(function(){
            var val = $(this).val().replace(/,/g, '');
            if(val != "")
            {
                total += parseFloat(val);
            }
        });
        
        // Format the total as currency
        let jumlah_total = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        $(".total-disetujui").text(jumlah_total);
        
    });

    $('#setujui').on('click', function() {
        var id_monitoring = $(this).data('id_monitoring');
        var id_pengajuan_pemohon = '<?=$id_pengajuan_pemohon?>';
        var anggaran_keterangan = $('#anggaran_keterangan').val();
        var page = $("temp_page").val(); // halaman saat ini, bisa diubah sesuai kebutuhan
        // hitung total jumlah komitmen yang di setujui
        var total_disetujui = 0;
        $(".jumlah-disetujui").each(function(){
            var val = $(this).val().replace(/,/g, '');
            if(val != "")
            {
                total_disetujui += parseFloat(val);
            }
        });

        var array_jumlah_disetujui = [];
        $(".jumlah-disetujui").each(function(){
            var inputId = $(this).attr('id').replace('jumlah_disetujui_', '');
            // Ambil id dari kolom pertama pada baris yang sama
            var row = $(this).closest('tr');
            var id = row.find('td:first').attr('id');
            var jumlah = $(this).val().replace(/,/g, '');
            array_jumlah_disetujui.push({
                id: id,
                jumlah_disetujui: jumlah
            });
        });
        
        $.ajax({
            url: '<?=base_url("unit_anggaran/monitoring/approval")?>',
            type: 'POST',
            data: {
                id_monitoring: id_monitoring,
                id_pengajuan_pemohon: id_pengajuan_pemohon,
                anggaran_keterangan: anggaran_keterangan,
                array_jumlah_disetujui:array_jumlah_disetujui,
                total_disetujui: total_disetujui
            },
            //dataType: 'json',
            success: function(res) {
                getDataPageMonitoring(page); // refresh data
				$("#modal-approval").modal('hide');
                /*if(res.status == 'success') {
                    alert('Approval berhasil disimpan.');
                } else {
                    alert('Gagal menyimpan approval.');
                }*/
                console.log(res);
            },
            error: function() {
                alert('Terjadi kesalahan saat menyimpan approval.');
            }
        });
    });
});

function getDataPageMonitoring(page){              
	var keywords = $('#keywords').val();
	var sortBy = $('#sortBy').val();
	var string_path = window.location.pathname;
	var last_string_path = string_path.slice(-1);
	if(last_string_path == '/'){
		$url = 'data/'+page;
	} else {
		$url = 'monitoring_ajax/data/'+page;
	}
	$.ajax({
		method: "POST",
		url: $url,
		data: { page:page, keywords:keywords, sortBy:sortBy },
		success: function(data){
			$('#postList').html(data);
            $('.loading-overlay').fadeOut("slow");
		}
	});
}
</script>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .panel, .panel * {
        visibility: visible;
    }
    .panel {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        border: none;
        box-shadow: none;
    }
    .btn-remove-row, #btn-add-row, .box-footer {
        display: none !important;
    }
    .form-control {
        border: none;
        background: transparent;
        box-shadow: none;
    }
    input.form-control {
        border-bottom: 1px dotted #000;
    }
}

/* Untuk autocomplete
.ui-autocomplete {
    position: absolute;
    z-index: 1000;
    cursor: default;
    padding: 0;
    margin-top: 2px;
    list-style: none;
    background-color: #ffffff;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    max-height: 300px;
    overflow-y: auto;
    overflow-x: hidden;
}

.ui-autocomplete > li {
    padding: 8px 12px;
    border-bottom: 1px solid #eee;
}

.ui-autocomplete > li:hover, 
.ui-autocomplete > li.ui-state-focus {
    background-color: #f5f5f5;
    cursor: pointer;
}

.ui-helper-hidden-accessible {
    display: none;
}
 */
/* Untuk tabel rincian */
#tabel-rincian td {
    vertical-align: middle !important;
}

#tabel-rincian .sisa-anggaran {
    font-weight: bold;
    color: #333;
}
/* autocomplete deskrip dpss */
table.autocomplete-dpsj {
	/*left: 30px;
	width:191px;*/
	position: absolute;
	z-index: 99;
	border-collapse: collapse;
	box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
	
}
table.autocomplete-dpsj tr {
	cursor: pointer;
}
table.autocomplete-dpsj tr th {
	background-color: lightgray;
	border: 1px solid lightgray;
	padding: 5px;
	font-size: 13px;
}
table.autocomplete-dpsj tr td {
	background-color: #fafafa;
	border: 1px solid lightgray;
	padding: 5px;
	font-size: 13px;
}
table.autocomplete-dpsj tr td:hover {
	background-color: #ddd;
}


/* autocomplete deskrip pc */
table.autocomplete-pc {
	/*left: 30px;
	width:191px;*/
	position: absolute;
	z-index: 99;
	border-collapse: collapse;
	box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
	
}
/*table.autocomplete-pc tr {
	cursor: pointer;
}*/
table.autocomplete-pc tr th {
	background-color: lightgray;
	border: 1px solid lightgray;
	padding: 5px;
	font-size: 13px;
}
table.autocomplete-pc tr td {
	background-color: #fafafa;
	border: 1px solid lightgray;
	padding: 5px;
	font-size: 13px;
}
/*table.autocomplete-pc tr td:hover {
	background-color: #ddd;
}*/

.isi_pc:hover, .isi_akun:hover {
    color:#fa0;
    cursor: pointer;
}

table {
    border-collapse: collapse;
    width: 100%;
}
th, td {
    border: 1px solid #ddd;
    padding: 8px;
}

.autocomplete {
    position: relative;
    display: inline-block;
}
.autocomplete-items {
    position: absolute;
    border: 1px solid #d4d4d4;
    border-bottom: none;
    border-top: none;
    z-index: 99;
    top: 100%;
    left: 0;
    right: 0;
}
.autocomplete-items div {
    padding: 10px;
    cursor: pointer;
    background-color: #fff; 
    border-bottom: 1px solid #d4d4d4; 
}
.autocomplete-items div:hover {
    background-color: #e9e9e9; 
}

.kotak{
    background-color: #fff;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 15px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border-top: 2px solid #fa0;
    border-left: 1px solid #ddd;
    border-right: 1px solid #ddd;
    border-bottom: 1px solid #ddd;
}

#pemohon tr td {
    border: 1px solid #fff;
    padding: 2px;
    font-size:14px;
}

#pemohon tr td.label {
    color: #555;
    font-weight: bold;
    width: 20%;
    text-align: right;
    padding-right: 10px;
    font-size:14px;
}
</style>