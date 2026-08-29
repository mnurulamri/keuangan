<?php
//echo '<pre>';print_r($result_monitoring[0]['nomor_pengajuan']);echo '</pre>';
$nomor_pengajuan = $result_monitoring[0]['nomor_pengajuan'];

if(!isset($teks_catatan_perbaikan) || empty($teks_catatan_perbaikan)) {
    $teks_catatan_perbaikan = 'Dokumen lengkap';
}

?>

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
    <div class="col-sm-12 kotakx">
        <div class="box-header with-border text-center" style="line-height:7px"><b>Verifikasi</b></div>
        <br>
        
        <div class="form-group" class="row">
            <label for="tanggal" style="color:#555" class="col-sm-3">Tanggal</label>
            <div class="col-sm-5">
                <!--<input type="date" class="form-control col-sm-3" id="anggaran_tanggal" name="anggaran_tanggal" value="<?=date('Y-m-d')?>" formatted="yyyy-mm-dd">-->
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control pull-right" id="verifikator_tanggal" name="verifikator_tanggal">
                </div>
            </div>


                <!-- time Picker -->
                <div class="bootstrap-timepicker col-sm-4">
                    <div class="form-groupx">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-clock-o"></i>
                            </div>
                            <input type="text" class="form-control timepicker" id="verifikator_waktu" name="verifikator_waktu" disabled >
                        </div>
                        <!-- /.input group -->
                    </div>
                    <!-- /.form group -->
                </div>

        </div>

        <div id="approvalForm" class="text-center">
            <div class="form-group">
                <label for="verifikator_keterangan" style="color:#555">Catatan</label>
                
                <div id="catatan-perbaikan" contenteditable="true" class="text-left" style="padding:10px; border:1px solid #433b3bff; margin-bottom:10px;">
                    <?php echo $teks_catatan_perbaikan ; ?>
                </div>

            </div>
            <button class="btn btn-success simpan-catatan" id="lanjut-proses" data-id_monitoring="<?=$result_monitoring[0]['id']?>"  data-id_pengajuan_pemohon="<?=$result_monitoring[0]['id_pengajuan_pemohon']?>"><i class="fa fa-check"></i> Lanjut Proses </button>
            <!--<button class="btn btn-warning simpan-catatan" id="pending" data-id_monitoring="<?=$result_monitoring[0]['id']?>"  data-id_pengajuan_pemohon="<?=$result_monitoring[0]['id_pengajuan_pemohon']?>"><i class="fa fa-undo"></i> Dikembalikan </button>
            <button class="btn btn-danger simpan-catatan" id="batal" data-id_monitoring="<?=$result_monitoring[0]['id']?>"  data-id_pengajuan_pemohon="<?=$result_monitoring[0]['id_pengajuan_pemohon']?>"><i class="fa fa-minus-circle"></i> Dibatalkan </button>-->
        </div>
    </div>
    </div>


<script>
$(document).ready(function()
{
    $("#lanjut-proses").click(function()
    {
        var id_monitoring = $(this).data("id_monitoring");
        var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
        //var catatan = $("#catatan-verifikator").text();
        var catatan_perbaikan = $("#catatan-perbaikan").html();

		var verifikator_tanggal = $("#verifikator_tanggal").val();
		var verifikator_waktu = $("#verifikator_waktu").val();

        console.log(catatan_perbaikan);
        $.ajax({
            url: '<?=base_url("verifikator/monitoring/lanjutProses")?>',
            type: 'POST',
            data: {
                id_monitoring: id_monitoring,
                id_pengajuan_pemohon: id_pengajuan_pemohon,
                verifikator_tanggal: verifikator_tanggal,
                verifikator_waktu: verifikator_waktu,
                catatan_perbaikan: catatan_perbaikan
            },
            //dataType: 'json',
            success: function(res) {
                alert('Catatan berhasil disimpan.');
                $("#status_"+id_pengajuan_pemohon).text('Verifikasi Kor PUM')
                console.log(res);
            },
            error: function() {
                alert('Terjadi kesalahan saat menyimpan catatan.');
            }
        });

        kendali_dokumen('Approve', id_monitoring, verifikator_tanggal, verifikator_waktu);
    });
    
    $("#pending").click(function()
    {
        if (!confirm("Apakah Anda yakin akan mengembalikan data pengajuan ini ke PUM?")) {
            return false;
        } else {
            var id_monitoring = $(this).data("id_monitoring");
            var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
            var catatan_perbaikan = $("#catatan-perbaikan").html();
            var verifikator_tanggal = $("#verifikator_tanggal").val();
            var verifikator_waktu = $("#verifikator_waktu").val();
            console.log(catatan_perbaikan);
            $.ajax({
                url: '<?=base_url("verifikator/monitoring/pending")?>',
                type: 'POST',
                data: {
                    id_monitoring: id_monitoring,
                    id_pengajuan_pemohon: id_pengajuan_pemohon,
                    verifikator_tanggal: verifikator_tanggal,
                    verifikator_waktu: verifikator_waktu,
                    catatan_perbaikan: catatan_perbaikan
                },
                //dataType: 'json',
                success: function(res) {
                    alert('Catatan berhasil disimpan.');
                    $("#status_"+id_pengajuan_pemohon).text('Dikembalikan ke PUM')
                    console.log(res);
                },
                error: function() {
                    alert('Terjadi kesalahan saat menyimpan catatan.');
                }
            });
            kendali_dokumen('Pending', id_monitoring, verifikator_tanggal, verifikator_waktu);
        }
    });
    
    $("#batal").click(function()
    {
		if (!confirm("Apakah Anda yakin akan membatalkan data pengajuan ini?")) {
			return false;
		} else {
            var id_monitoring = $(this).data("id_monitoring");
            var id_pengajuan_pemohon = $(this).data("id_pengajuan_pemohon");
            var catatan_perbaikan = $("#catatan-perbaikan").html();
            var verifikator_tanggal = $("#verifikator_tanggal").val();
            var verifikator_waktu = $("#verifikator_waktu").val();
            console.log(catatan_perbaikan);
            $.ajax({
                url: '<?=base_url("verifikator/monitoring/batal")?>',
                type: 'POST',
                data: {
                    id_monitoring: id_monitoring,
                    id_pengajuan_pemohon: id_pengajuan_pemohon,
                    verifikator_tanggal: verifikator_tanggal,
                    verifikator_waktu: verifikator_waktu,
                    catatan_perbaikan: catatan_perbaikan
                },
                //dataType: 'json',
                success: function(res) {
                    alert('Catatan berhasil disimpan.');
                    $("#status_"+id_pengajuan_pemohon).text('Dibatalkan Verifikator')
                    console.log(res);
                },
                error: function() {
                    alert('Terjadi kesalahan saat menyimpan catatan.');
                }
            });
            kendali_dokumen('Batal', id_monitoring, verifikator_tanggal, verifikator_waktu);
        }
    });

    //Datepicker
    $(document).on('focus', '#verifikator_tanggal', function(){
        $('#verifikator_tanggal').datepicker({
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

    // tampilkan menit dan detik secara realtime mengikuti waktu client
    setInterval(function() {
        var currentTime = new Date();
        var hours = String(currentTime.getHours()).padStart(2, '0');
        var minutes = String(currentTime.getMinutes()).padStart(2, '0');
        var seconds = String(currentTime.getSeconds()).padStart(2, '0');
        var formattedTime = hours + ':' + minutes + ':' + seconds;
        document.getElementById('verifikator_waktu').value = formattedTime;
    }, 1000);

    // tampilkan tanggal saat dokumen dibuka dalam format bahasa Indonesia dengan format "Hari, DD Bulan YYYY"
    $("#verifikator_tanggal").val(function(){
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

function kendali_dokumen(flag_approve, id_monitoring, verifikator_tanggal, verifikator_waktu){
    var kd_pengajuan = "<?= $nomor_pengajuan ?>";
    var catatan_perbaikan = $("#catatan-perbaikan").html();
    
    if(flag_approve == 'Pending'){
        kode_status = 52;
    } else if(flag_approve == 'Batal') {
        kode_status = 53;
    } else {
        kode_status = 61;
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
            tanggal: verifikator_tanggal,
            waktu: verifikator_waktu
        },
        //dataType: 'json',
        success: function(res) {
            //alert('Catatan berhasil disimpan.');
            console.log(res);
        },
        error: function() {
            alert('Terjadi kesalahan saat menyimpan catatan.');
        }
    });
}
</script>