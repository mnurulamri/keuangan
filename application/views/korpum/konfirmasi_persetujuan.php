<?php
//echo '<pre>';print_r($result_monitoring[0]['nomor_pengajuan']);echo '</pre>';
$nomor_pengajuan = $result_monitoring[0]['nomor_pengajuan'];

if(!isset($teks_catatan_perbaikan) || empty($teks_catatan_perbaikan)) {
    $teks_catatan_perbaikan = 'Lanjut proses';
}

?>

<div id="test-rincian"></div>
<!--<pre id="realisasi" ><?php print_r($realisasi); ?></pre>-->
<input type="hidden" value="<?php print_r($id); ?>" id="id_input" />
<input type="hidden" value="<?php print_r($realisasi); ?>" id="realisasi_input" />
<div class="row" style="width:99%; margin:0 auto;">
    <?php
    // jika kode_status adalah 51 (Menunggu Pemeriksaan Verifikator)
    if($result_monitoring[0]['kode_status'] == 51) {
        $verifikator_keterangan = isset($result_monitoring[0]['verifikator_keterangan_disetujui']) ? $result_monitoring[0]['verifikator_keterangan_disetujui'] : '';
    } else if($result_monitoring[0]['kode_status'] == 52) {            
        $verifikator_keterangan = isset($result_monitoring[0]['keterangan_retur']) ? $result_monitoring[0]['keterangan_retur'] : '';
    } else {
        $verifikator_keterangan = '';
    }
    ?>
    <br>
    <div class="col-sm-12 box">
        <div class="box-header with-border text-center" style="line-heightx:7px"><b>Verifikasi</b></div>
        <div class="box-body form-group" class="row">
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
            <hr>
            <div id="approvalForm" class="text-center">
                <div class="form-group">
                    <label for="verifikator_keterangan" style="color:#555">Catatan</label>
                    
                    <div id="catatan-perbaikan" contenteditable="true" class="text-left" style="padding:10px; border:1px solid rgb(235, 229, 229); margin-bottom:10px;">
                        <?php //echo $teks_catatan_perbaikan ; ?>
                    </div>
                </div>
                <button class="btn btn-success simpan-catatan" id="disetujui" data-id_monitoring="<?=$result_monitoring[0]['id']?>"  data-id_pengajuan_pemohon="<?=$result_monitoring[0]['id_pengajuan_pemohon']?>" data-form="<?=$result_monitoring[0]['form']?>"><i class="fa fa-check"></i> Disetujui </button>
                <button class="btn btn-warning simpan-catatan" id="pending_verifikasi" data-id_monitoring="<?=$result_monitoring[0]['id']?>"  data-id_pengajuan_pemohon="<?=$result_monitoring[0]['id_pengajuan_pemohon']?>" data-form="<?=$result_monitoring[0]['form']?>" > <i class="fa fa-exclamation-circle"></i> Pending </button>
                <button class="btn btn-warning simpan-catatan" id="pending" data-id_monitoring="<?=$result_monitoring[0]['id']?>"  data-id_pengajuan_pemohon="<?=$result_monitoring[0]['id_pengajuan_pemohon']?>" data-form="<?=$result_monitoring[0]['form']?>" ><i class="fa fa-undo"></i> Dikembalikan </button>
                <button class="btn btn-danger simpan-catatan" id="batal" data-id_monitoring="<?=$result_monitoring[0]['id']?>" data-id_pengajuan_pemohon="<?=$result_monitoring[0]['id_pengajuan_pemohon']?>" data-form="<?=$result_monitoring[0]['form']?>"><i class="fa fa-minus-circle"></i> Dibatalkan</button>
            </div>      
        </div>
    </div>
</div>

<div id="keterangan">
    <ul class="text-left text-info" style="color:#555">
        <li><b>Disetujui</b>: Pengajuan disetujui dan dilanjutkan ke proses berikutnya.</li>
        <li><b>Dipending</b>: Pengajuan dipending untuk melengkapi berkas.</li>
        <li><b>Dikembalikan</b>: Pengajuan dikembalikan ke PUM untuk perbaikan nominal atau akun.</li>        
        <li><b>Dibatalkan</b>: Pengajuan dibatalkan dan tidak akan diproses lebih lanjut.</li>
    </ul>
</div>

<script>
$(document).ready(function()
{
    
    $("#disetujui").click(function()
    {
        var id_monitoring = $(this).data("id_monitoring");
        var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
        var catatan_perbaikan = $("#catatan-perbaikan").html();
        var id_input = $("#id_input").val();
        var realisasi_input = $("#realisasi_input").val();
		var korpum_tanggal = $("#korpum_tanggal").val();
		var korpum_waktu = $("#korpum_waktu").val();
        var form = '-';

        $.ajax({
            url: '<?=base_url("korpum/periksa_realisasi/lanjutProses")?>',
            type: 'POST',
            data: {
                id_monitoring: id_monitoring,
                id_pengajuan_pemohon: id_pengajuan_pemohon,
                catatan_perbaikan: catatan_perbaikan,
                id_input: id_input,
                realisasi_input: realisasi_input,
				korpum_tanggal:$('#korpum_tanggal').val(),
				korpum_waktu:$('#korpum_waktu').val()
            },
            //dataType: 'json',
            success: function(res) {
                alert('approval realisasi berhasil disimpan.');
                $("#status_"+id_pengajuan_pemohon).text('Lanjut Procost');
                console.log(res);
            },
            error: function() {
                alert('Terjadi kesalahan saat menyimpan catatan.');
            }
        });

        kendali_dokumen('Approve', id_monitoring, korpum_tanggal, korpum_waktu, form);
    });
    
    $("#pending").click(function()
    {
        var id_monitoring = $(this).data("id_monitoring");
        var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
        //var catatan = $("#catatan-verifikator").text();
        var catatan_perbaikan = $("#catatan-perbaikan").html();
		var korpum_tanggal = $("#korpum_tanggal").val();
		var korpum_waktu = $("#korpum_waktu").val();
        var form = $(this).data("form");
        console.log(catatan_perbaikan);

        $.ajax({
            url: '<?=base_url("korpum/periksa_realisasi/pending")?>',
            type: 'POST',
            data: {
                id_monitoring: id_monitoring,
                id_pengajuan_pemohon: id_pengajuan_pemohon,
                catatan_perbaikan: catatan_perbaikan,
                korpum_tanggal:$('#korpum_tanggal').val(),
                korpum_waktu:$('#korpum_waktu').val(),
                form:form
            },
            //dataType: 'json',
            success: function(res) {
                //alert('Catatan berhasil disimpan.');
                $("#status_"+id_pengajuan_pemohon).text('Retur oleh Kor PUM');
                $("#button_verifikasi_"+id_monitoring).prop("disabled", true);
                console.log(res);
            },
            error: function() {
                alert('Terjadi kesalahan saat menyimpan catatan.');
            }
        });

        kendali_dokumen('Pending', id_monitoring, korpum_tanggal, korpum_waktu, form);
    });
    
    $("#pending_verifikasi").click(function() {
        var id_monitoring = $(this).data("id_monitoring");
        var catatan = $("#catatan-perbaikan").html();
        var tgl = $("#korpum_tanggal").val();
        var waktu = $("#korpum_waktu").val();
        
        // 1. Update ke database (Monitoring)
        $.ajax({
            url: '<?=base_url("korpum/periksa_realisasi/pendingVerifikasi")?>',
            type: 'POST',
            data: { id_monitoring: id_monitoring, catatan_perbaikan: catatan },
            success: function() {
                // 2. Simpan ke Kendali Dokumen
                kendali_dokumen('Pending_verifikasi', id_monitoring, tgl, waktu, '-');
                alert('Data dipending.');
            }
        });
    });
    
    $("#batal").click(function()
    {
		if (!confirm("Apakah Anda yakin akan membatalkan data pengajuan ini?")) {
			return false;
		} else {
            var id_monitoring = $(this).data("id_monitoring");
            var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
            //var catatan = $("#catatan-verifikator").text();
            var catatan_perbaikan = $("#catatan-perbaikan").html();
            var korpum_tanggal = $("#korpum_tanggal").val();
            var korpum_waktu = $("#korpum_waktu").val();
            var form = $(this).data("form");
            console.log(catatan_perbaikan);

            $.ajax({
                url: '<?=base_url("korpum/periksa_realisasi/batal")?>',
                type: 'POST',
                data: {
                    id_monitoring: id_monitoring,
                    id_pengajuan_pemohon: id_pengajuan_pemohon,
                    catatan_perbaikan: catatan_perbaikan,
                    korpum_tanggal:$('#korpum_tanggal').val(),
                    korpum_waktu:$('#korpum_waktu').val(),
                    form:form
                },
                //dataType: 'json',
                success: function(res) {
                    //alert('Catatan berhasil disimpan.');
                    $("#status_"+id_pengajuan_pemohon).text('Dibatalkan oleh Kor PUM');
                    $("#button_verifikasi_"+id_monitoring).prop("disabled", true);
                    console.log(res);
                },
                error: function() {
                    alert('Terjadi kesalahan saat menyimpan catatan.');
                }
            });

            kendali_dokumen('Batal', id_monitoring, korpum_tanggal, korpum_waktu, form);
        }
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
});

function kendali_dokumen(flag_approve, id_monitoring, korpum_tanggal, korpum_waktu, form){
    var kd_pengajuan = "<?= $nomor_pengajuan ?>";
    var catatan_perbaikan = $("#catatan-perbaikan").html();
    if(flag_approve == 'Pending'){        
        if(form=='D01'){
            kode_status = 41;  // dikembalikan ke PUM untuk pengisian SPJ
        } else if(form=='D02') {
            kode_status = 63;  // dikembalikan ke PUM untuk pengajuan ulang
        }
    } else if(flag_approve == 'Pending_verifikasi'){        
        kode_status = 62;  // dibatalkan ke PUM 
    } else if(flag_approve == 'Batal'){        
        kode_status = 64;  // dibatalkan ke PUM 
    } else {
        kode_status = 13; // diteruskan ke unit anggaran untuk membuat procost
    }
    //console.log("Simpan catatan untuk pengajuan: " + kd_pengajuan + " dengan keterangan: " + anggaran_keterangan+ ' dan kode_status: '+kode_status);
    $.ajax({
        url: '<?=base_url("Kendali_dokumen")?>',
        type: 'POST',
        data: {
			id_monitoring: id_monitoring,
            kd_pengajuan: kd_pengajuan,
            keterangan: catatan_perbaikan,
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
// tampilkan menit dan detik secara realtime mengikuti waktu client
//document.addEventListener('DOMContentLoaded', function() {
    // tampilkan menit dan detik secara realtime mengikuti waktu client
    setInterval(function() {
        var currentTime = new Date();
        var hours = String(currentTime.getHours()).padStart(2, '0');
        var minutes = String(currentTime.getMinutes()).padStart(2, '0');
        var seconds = String(currentTime.getSeconds()).padStart(2, '0');
        var formattedTime = hours + ':' + minutes + ':' + seconds;
        // Check if the element exists before trying to set its value
        var korpumWaktuElement = document.getElementById('korpum_waktu');
        if (korpumWaktuElement) {
            korpumWaktuElement.value = formattedTime;
        } else {
            console.warn("Element with ID 'korpum_waktu' not found.");
            //clearInterval(window.timeInterval); // Stop the interval if the element is not found
            //clear setInterval(this);
        }
    }, 1000);

    // tampilkan tanggal saat dokumen dibuka dalam format bahasa Indonesia dengan format "Hari, DD Bulan YYYY"
//});
</script>