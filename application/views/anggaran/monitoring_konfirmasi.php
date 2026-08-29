<?php
if (!$this->session->userdata('kode_bidang')) {
    //redirect('auth/unit_kerja');
}
//echo '<pre>';print_r($this->session->userdata);echo '</pre>';
?>


<!-- bootstrap time picker -->
<script src="<?=base_url()?>assets/AdminLTE/plugins/timepicker/bootstrap-timepicker.min.js"></script>
  <!-- Bootstrap time Picker -->
  <link rel="stylesheet" href="<?=base_url()?>assets/AdminLTE/plugins/timepicker/bootstrap-timepicker.min.css">

<div><button id="view-data-pemohon-rincian" class="btn btn-primary btn-xs">Detail Data Pemohon dan Rincian</button></div>
            <div id="data-pemohon-rincian" style="display:none;">                
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
                                        <td width="30%"> <?= dateTimeToTanggal($tgl_diajukan) ?></td>
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
                                                    <?= $deskripsi_dpsj ?>
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
                                            <table class="tablex table-borderedx" id="tabel-rincian">
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
                                                          
                                                        $kode_dpsj = $row['kode_dpsj'];  
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
                                                                <input type="text" class="form-control jumlah-disetujui" id="jumlah_disetujui_'.$newId.'" data-id="'.$newId.'" style="width:100px" value="'.number_format($row['komitmen']).'">
                                                            </td>                                                        
                                                            <td class="keterangan">'.$row['keterangan'].'</td>
                                                            <td class="sisa_anggaran" id="sisa_anggaran_'.$newId.'" data-sisa_anggaran="'.$sisa_anggaran[$kode_kegiatan][$kode_akun][$kode_dana].'">'.$sisa_anggaran[$kode_kegiatan][$kode_akun][$kode_dana].'</td>

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
                                                    <!--
                                                    <tr>
                                                        <td colspan="9" style="border: 1px solid #fff">             
                                                            
                                                        </td>
                                                    </tr>
                                                    
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
            </div>    
                                                    <hr>
                    <div class="row" style="width:99%; margin:0 auto;">

                        <div class="col-sm-12 kotakx">
                            <div class="box-header with-border text-center" style="line-height:7px"><b>Verifikasi</b></div>
                            <br>
                            <div id="approvalForm" class="text-center">
                                <div class="form-group" class="row">
                                    <label for="tgl_diajukan" style="color:#555" class="col-sm-3">Tgl Pengajuan</label>
                                    <div class="col-sm-8">
                                        <input class="form-control" id="tgl_diajukan" name="tgl_diajukan" rows="3" value="<?=dbToTanggal($tgl_diajukan)?>">
                                    </div>
                                </div>  
                                <div style="clear:both;"></div>
                                <div class="form-group" class="row">
                                    <label for="tanggal" style="color:#555" class="col-sm-3">Tgl Verifikasi</label>
                                    <div class="col-sm-5">
                                        <!--<input type="date" class="form-control col-sm-3" id="anggaran_tanggal" name="anggaran_tanggal" value="<?=date('Y-m-d')?>" formatted="yyyy-mm-dd">-->
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control pull-right" id="anggaran_tanggal" name="anggaran_tanggal">
                                        </div>
                                    </div>


                                        <!-- time Picker -->
                                        <div class="bootstrap-timepicker col-sm-4">
                                            <div class="form-groupx">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                    </div>
                                                    <input type="text" class="form-control timepicker" id="anggaran_waktu" name="anggaran_waktu" disabled >
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                            <!-- /.form group -->
                                        </div>

                                </div>
                                <div style="clear:both;"></div>
                                <div class="form-group" class="row">
                                    <label for="keterangan_anggaran_umko" style="color:#555" class="col-sm-3">Keterangan</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control col-sm-9" id="anggaran_keterangan" name="anggaran_keterangan" rows="3"><?=$anggaran_keterangan?></textarea>
                                    </div>
                                </div>  
                            </div>
                            <div style="line-height:14x;">&nbsp;</div>
                            <div class="row text-center">  
                                <button class="btn btn-success" id="setujui" data-id_pengajuan_pemohon="<?=$id_pengajuan_pemohon?>"  data-id_monitoring="<?=$id_monitoring?>" data-nama_form="<?=$form?>"><i class="fa fa-check"></i> Disetujui</button>                              
                                <button type="button" class="btn btn-warning" data-id_pengajuan_pemohon="<?=$id_pengajuan_pemohon?>" id="pending" data-id_monitoring="<?=$id_monitoring?>" data-nama_form="<?=$form?>" ><i class="fa fa-undo"></i> Dikembalikan </button>                            
                                <button type="button" class="btn btn-danger" data-id_pengajuan_pemohon="<?=$id_pengajuan_pemohon?>" id="batal" data-id_monitoring="<?=$id_monitoring?>" data-nama_form="<?=$form?>" ><i class="fa fa-minus-circle"></i> Dibatalkan </button>
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
        var nama_form = $(this).data('nama_form');

		var anggaran_tanggal = $("#anggaran_tanggal").val();
		var anggaran_waktu = $("#anggaran_waktu").val();

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
                total_disetujui: total_disetujui,
                nama_form: nama_form,
				anggaran_tanggal:anggaran_tanggal,
				anggaran_waktu:anggaran_waktu
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

        // proses simpan ke kendali dokumen
        kendali_dokumen(flag_approve='Proses', id_monitoring, anggaran_tanggal, anggaran_waktu, nama_form);
    });

    $("#view-data-pemohon-rincian").click(function(){
        $("#data-pemohon-rincian").toggle('slow');
    });  

    $("#pending").click(function(){
        var id_monitoring = $(this).data('id_monitoring');
        var id_pengajuan_pemohon = '<?=$id_pengajuan_pemohon?>';
        var anggaran_keterangan = $('#anggaran_keterangan').val();
        var page = $("temp_page").val(); // halaman saat ini, bisa diubah sesuai kebutuhan
        var nama_form = $(this).data('nama_form');

		var anggaran_tanggal = $("#anggaran_tanggal").val();
		var anggaran_waktu = $("#anggaran_waktu").val();

        $.ajax({
            url: '<?=base_url("unit_anggaran/monitoring/pending")?>',
            type: 'POST',
            data: {
                id_monitoring: id_monitoring,
                id_pengajuan_pemohon: id_pengajuan_pemohon,
                anggaran_keterangan: anggaran_keterangan,
                nama_form: nama_form,
				anggaran_tanggal:anggaran_tanggal,
				anggaran_waktu:anggaran_waktu
            },
            //dataType: 'json',
            success: function(res) {
                //getDataPageMonitoring(page); // refresh data
				$("#modal-approval").modal('hide');
                $("#status_"+id_monitoring).text('Dikembalikan Unit Anggaran');
				$("#button_"+id_monitoring).html('');

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

        kendali_dokumen(flag_approve='Pending', id_monitoring, anggaran_tanggal, anggaran_waktu, nama_form);
    });

    $("#batal").click(function(){

		if (!confirm("Apakah Anda yakin akan membatalkan data pengajuan ini?")) {
			return false;
		} else {

            var id_monitoring = $(this).data('id_monitoring');
            var id_pengajuan_pemohon = '<?=$id_pengajuan_pemohon?>';
            var anggaran_keterangan = $('#anggaran_keterangan').val();
            var page = $("temp_page").val(); // halaman saat ini, bisa diubah sesuai kebutuhan
            var nama_form = $(this).data('nama_form');

            var anggaran_tanggal = $("#anggaran_tanggal").val();
            var anggaran_waktu = $("#anggaran_waktu").val();

            $.ajax({
                url: '<?=base_url("unit_anggaran/monitoring/batal")?>',
                type: 'POST',
                data: {
                    id_monitoring: id_monitoring,
                    id_pengajuan_pemohon: id_pengajuan_pemohon,
                    anggaran_keterangan: anggaran_keterangan,
                    nama_form: nama_form,
                    anggaran_tanggal:anggaran_tanggal,
                    anggaran_waktu:anggaran_waktu
                },
                //dataType: 'json',
                success: function(res) {
                    //getDataPageMonitoring(page); // refresh data
                    $("#modal-approval").modal('hide');
                    $("#status_"+id_monitoring).text('Dibatalkan Unit Anggaran');
                    $("#button_"+id_monitoring).html('');

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

            kendali_dokumen(flag_approve='Batal', id_monitoring, anggaran_tanggal, anggaran_waktu, nama_form);
        }
    });

    //Datepicker
    $(document).on('focus', '#anggaran_tanggal', function(){
        $('#anggaran_tanggal').datepicker({
            autoclose: true,
            language: "id",
            format:"DD, d MM yyyy",
            todayHighlight: true,
            onSelect: function(selectedDate) {
                // Tindakan setelah tanggal dipilih (jika diperlukan)
                console.log("Tanggal dipilih: " + selectedDate);
            }
        });
    });
});

function kendali_dokumen(flag_approve, id_monitoring, anggaran_tanggal, anggaran_waktu, nama_form){
    var kd_pengajuan = "<?= $preview_nomor ?>";
    var anggaran_keterangan = $('#anggaran_keterangan').val();
    if(flag_approve == 'Pending'){
        kode_status = 12;
    } else if(flag_approve == 'Batal'){
        kode_status = 14;
    } else {
        if(nama_form == 'D02'){
            kode_status = 51;
        } else {
            kode_status = 21;
        }
    }
    //console.log("Simpan catatan untuk pengajuan: " + kd_pengajuan + " dengan keterangan: " + anggaran_keterangan+ ' dan kode_status: '+kode_status);
    $.ajax({
        url: '<?=base_url("Kendali_dokumen")?>',
        type: 'POST',
        data: {
			id_monitoring: id_monitoring,
            kd_pengajuan: kd_pengajuan,
            keterangan: anggaran_keterangan,
            kode_status: kode_status,
            tanggal: anggaran_tanggal,
            waktu: anggaran_waktu
        },
        //dataType: 'json',
        success: function(res) {
            alert('Catatan berhasil disimpan.');
            console.log(res);
        },
        error: function() {
            alert('Terjadi kesalahan saat menyimpan catatan.');
        }
    });
}

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

// tampilkan menit dan detik secara realtime mengikuti waktu client
setInterval(function() {
    var currentTime = new Date();
    var hours = String(currentTime.getHours()).padStart(2, '0');
    var minutes = String(currentTime.getMinutes()).padStart(2, '0');
    var seconds = String(currentTime.getSeconds()).padStart(2, '0');
    var formattedTime = hours + ':' + minutes + ':' + seconds;
    document.getElementById('anggaran_waktu').value = formattedTime;
}, 1000);

// tampilkan tanggal saat dokumen dibuka dalam format bahasa Indonesia dengan format "Hari, DD Bulan YYYY"
$("#anggaran_tanggal").val(function(){
    var months = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    var days = [
        'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
    ];
    var currentDate = new Date();
    var dayName = days[currentDate.getDay()];
    var day = String(currentDate.getDate()).padStart(2, '0');
    var monthName = months[currentDate.getMonth()];
    var year = currentDate.getFullYear();
    var formattedDate = dayName + ', ' + day + ' ' + monthName + ' ' + year;
    return formattedDate;
});

    //Timepicker
    $(".timepicker").timepicker({
        showInputs: false,
        showMeridian: false,
        defaultTime: 'current',
        minuteStep: 1,
        secondStep: 1,
        showSeconds: true


    });
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