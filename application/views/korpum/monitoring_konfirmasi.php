<?php
if (!$this->session->userdata('kode_bidang')) {
    //redirect('auth/unit_kerja');
}
//echo '<pre>';print_r($pejabat);echo '</pre>';
?>

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
                                        <td width="30%"> <?= dateTimeToTanggal($tanggal) ?></td>
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
                                                    $nominal_disetujui = 0;

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
                                                            <td class="jumlah text-right" id="jumlah_'.$newId.'">'.number_format($row['komitmen']).'</td>
                                                            <td class="jumlah-disetujui text-right" id="jumlah_disetujui_'.$newId.'">
                                                                '.number_format($row['komitmen_disetujui']).'
                                                            </td>                                                        
                                                            <td class="keterangan">'.$row['keterangan'].'</td>
                                                            <td class="sisa_anggaran text-right" id="sisa_anggaran_'.$newId.'" data-sisa_anggaran="'.$sisa_anggaran[$kode_dpsj][$kode_kegiatan][$kode_akun][$kode_dana].'">'.$sisa_anggaran[$kode_dpsj][$kode_kegiatan][$kode_akun][$kode_dana].'</td>

                                                        </tr>';
                                                        $rowCount++;
                                                        $newId++;    
                                                        $nominal_pengajuan += $row['komitmen'];
                                                        $nominal_disetujui += $row['komitmen_disetujui'];                                              
                                                    }
                                                    ?>
                                                    <script>$("#newId").val(<?=$newId?>);</script>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="6" class="text-right"><b>Total: </b></td>
                                                        <td class="total text-right"><?=number_format($nominal_pengajuan)?></td>
                                                        <td class="total-disetujui text-right"><?=number_format($nominal_disetujui)?></td>
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
            </div>
                                                    <hr>
                    <div class="row" style="width:99%; margin:0 auto;">

                        <div class="col-sm-12 kotakx">
                            <div class="box-header with-border text-center" style="line-height:7px"><b>Approval</b></div>
                                <div class="form-group" class="row">
                                    <label for="tanggal" style="color:#555" class="col-sm-3">Tanggal</label>
                                    <div class="col-sm-5">
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control pull-right" id="korpum_tanggal" name="korpum_tanggal">
                                        </div>
                                    </div>


                                        <!-- time Picker -->
                                        <div class="bootstrap-timepicker col-sm-4">
                                            <div class="form-groupx">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                    </div>
                                                    <input type="text" class="form-control timepicker" id="korpum_waktu" name="korpum_waktu" disabled >
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                            <!-- /.form group -->
                                        </div>

                                </div>
                                <br>
                            <div id="approvalForm" class="text-center">
                                <div class="form-group">
                                    <label for="korpum_keterangan" style="color:#555">Catatan</label>
                                    <textarea class="form-control" id="korpum_keterangan" name="korpum_keterangan" rows="3"><?=$korpum_keterangan?></textarea>
                                </div>
                                <button type="button" class="btn btn-warning" id="pending" data-id_monitoring="<?=$id_monitoring?>">Pending</button>
                                <button class="btn btn-success" id="setujui" data-id_pengajuan_pemohon="<?=$id_pengajuan_pemohon?>"  data-id_monitoring="<?=$id_monitoring?>"><i class="fa fa-check"></i> Proses</button>
                            </div>
                        </div>
                    </div>
                <!--</div>-->
                
<div>test</div>
<script>
$(document).ready(function()
{
    $('#setujui').on('click', function() {
        var id_monitoring = $(this).data('id_monitoring');
        var id_pengajuan_pemohon = '<?=$id_pengajuan_pemohon?>';
        var korpum_keterangan = $('#korpum_keterangan').val();
        var page = $("temp_page").val(); // halaman saat ini, bisa diubah sesuai kebutuhan

		var korpum_tanggal = $("#korpum_tanggal").val();
		var korpum_waktu = $("#korpum_waktu").val();

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
            url: '<?=base_url("korpum/monitoring/approval")?>',
            type: 'POST',
            data: {
                id_monitoring: id_monitoring,
                id_pengajuan_pemohon: id_pengajuan_pemohon,
                korpum_keterangan: korpum_keterangan,
                array_jumlah_disetujui:array_jumlah_disetujui,
                total_disetujui: total_disetujui,
				korpum_tanggal:$('#korpum_tanggal').val(),
				korpum_waktu:$('#korpum_waktu').val()
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
        kendali_dokumen(flag_approve='Proses', id_monitoring, korpum_tanggal, korpum_waktu);
    });

    $("#view-data-pemohon-rincian").click(function(){
        $("#data-pemohon-rincian").toggle('slow');
    });

    $("#pending").click(function(){
        var id_monitoring = $(this).data('id_monitoring');
        kendali_dokumen(flag_approve='Pending', id_monitoring, korpum_tanggal, korpum_waktu);
    });

    //Datepicker
    $(document).on('focus', '#korpum_tanggal', function(){
        $('#korpum_tanggal').datepicker({
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

    //Timepicker
    /*$(".timepicker").timepicker({
        showInputs: false,
        showMeridian: false,
        defaultTime: 'current',
        minuteStep: 1,
        secondStep: 1,
        showSeconds: true
    });*/
});

function kendali_dokumen(flag_approve, id_monitoring, korpum_tanggal, korpum_waktu){
    var kd_pengajuan = "<?= $preview_nomor ?>";
    var korpum_keterangan = $('#korpum_keterangan').val();
    if(flag_approve == 'Pending'){
        kode_status = 22;
    } else {
        kode_status = 21;
    }
    //console.log("Simpan catatan untuk pengajuan: " + kd_pengajuan + " dengan keterangan: " + anggaran_keterangan+ ' dan kode_status: '+kode_status);
    $.ajax({
        url: '<?=base_url("Kendali_dokumen")?>',
        type: 'POST',
        data: {
			id_monitoring: id_monitoring,
            kd_pengajuan: kd_pengajuan,
            keterangan: korpum_keterangan,
            kode_status: kode_status,
            tanggal: korpum_tanggal,
            waktu: korpum_waktu
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
    document.getElementById('korpum_waktu').value = formattedTime;
}, 1000);

// tampilkan tanggal saat dokumen dibuka dalam format bahasa Indonesia dengan format "Hari, DD Bulan YYYY"
$("#korpum_tanggal").val(function(){
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
</script>

<?php 
include (APPPATH . 'views/template/css/style_form_konfirmasi.php'); 
?>