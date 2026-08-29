<?php
// buat array dari $array_rincian untuk membuat tabel rincian biaya
// buat array realisasi dari $array_realisasi untuk mengisi kolom realisasi pada tabel rincian biaya
foreach($array_rincian as $key => $value) {
    foreach($value as $row) {
        $array_rincian_data[$row['id']] = $row;
    }
}
?>

<h4 class="text-center">Anda akan mengajukan Realisasi Uang Muka dengan data pengajuan sebagai berikut:</h4>

<?php
    $html= '
        <div id="approvalForm" class="text-center" style="padding:10px">
            <div class="form-group" class="row">
                <div class="col-lg-2 col-md-2 col-sm-2"></div>
                <label for="tanggal" style="color:#555" class="col-lg-4 col-md-4 col-sm-4 text-right form-label">Tanggal Diajukan:</label>
                <input type="text" class="form-controlx pull-left form-control-xs" id="user_tanggal" name="user_tanggal" style="border:1px solid #ddd" disabled>

                <!-- 
                <div class="col-lg-3 col-md-3 col-sm-3 text-left">
                    <div class="input-group date">
                        <div class="input-group-addon input-group-xs">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-controlx pull-left form-control-xs" id="user_tanggal" name="user_tanggal">
                    </div>
                </div>
                time Picker -->
                <div class="bootstrap-timepicker col-lg-3 col-md-3 col-sm-3" style="display:none">
                    <div class="form-groupx">
                        <div class="input-group">
                            <div class="input-group-addon input-group-xs">
                                <i class="fa fa-clock-o"></i>
                            </div>
                            <input type="text" class="form-control timepicker" id="user_waktu" name="user_waktu" disabled >
                        </div>
                        <!-- /.input group -->
                    </div>
                    <!-- /.form group -->
                </div>
            </div>
        </div>
        <br>
        <table id="tabel">
            <tr style="background-color:#f7f7f7;color:#777">
                <th class="subhead">Kode Procost</th>
                <th>Nama Procost</th>
                <th>Kode Akun</th>
                <th>Deskripsi Akun</th>
                <th>Jumlah UMKO</th>
                <th>Realisasi</th>
                <th>Sisa UMKO</th>
            </tr>';

            $total_komitmen = 0;
            $total_realisasi = 0;
            $n=1;
            foreach($array_rincian_data as $row) {

if(isset($array_realisasi[$row['id']]['total_bruto'])){
	$aktual = $array_realisasi[$row['id']]['total_bruto'];
} else {
	$aktual = 0;
}


                $html.= '<tr>';
                $html.= '<td>'.$row['kode_kegiatan'].'</td>';
                $html.= '<td>'.$row['nama_kegiatan'].'</td>';
                $html.= '<td>'.$row['kode_akun'].'</td>';
                $html.= '<td>'.$row['deskripsi_akun'].'</td>';
                $html.= '<td style="border-right:1px solid #ddd;">'.number_format($row['komitmen']).'</td>';
                //$html.= '<td style="border-right:1px solid #ddd;">'.number_format($array_realisasi[$row['id']]['total_bruto']).'</td>';
				$html.= '<td style="border-right:1px solid #ddd;">'.number_format($aktual).'</td>';
                //$html.= '<td style="border-right:1px solid #ddd;">'.number_format($row['komitmen'] - $array_realisasi[$row['id']]['total_bruto']).'</td>';
				$html.= '<td style="border-right:1px solid #ddd;">'.number_format($row['komitmen'] - $aktual).'</td>';
                $html.= '</tr>';
                $n++;

                $total_komitmen += $row['komitmen'];
                //$total_realisasi += $array_realisasi[$row['id']]['total_bruto'];
				$total_realisasi += $aktual;

            }

        $html.= '<tr style="background-color:#eee;font-weight:bold; border-top:2px solid #ddd;">
            <td colspan="4" style="text-align:right; border-right:2px solid #ddd;">TOTAL</td>
            <td style="border-right:1px solid #ddd;">'.number_format($total_komitmen).'</td>
            <td style="border-right:1px solid #ddd;">'.number_format($total_realisasi).'</td>
            <td style="border-right:1px solid #ddd;">'.number_format($total_komitmen - $total_realisasi).'</td>
        </tr>';
    
    $html.= 
        '</table>';
        echo $html;
?>
<br>
<div class="text-center">
    <button id="ajukan" class="btn btn-primary" data-id_pengajuan_pemohon="<?=$id_pengajuan_pemohon?>" > Ajukan </button>  
</div>

<script>

// tampilkan menit dan detik secara realtime mengikuti waktu client
setInterval(function() {
    var currentTime = new Date();
    var hours = String(currentTime.getHours()).padStart(2, '0');
    var minutes = String(currentTime.getMinutes()).padStart(2, '0');
    var seconds = String(currentTime.getSeconds()).padStart(2, '0');
    var formattedTime = hours + ':' + minutes + ':' + seconds;
    document.getElementById('user_waktu').value = formattedTime;
}, 1000);

// tampilkan tanggal saat dokumen dibuka dalam format bahasa Indonesia dengan format "Hari, DD Bulan YYYY"
$("#user_tanggal").val(function(){
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
    var formattedDate = day + ' ' + monthName + ' ' + year;
    return formattedDate;
});

</script>
