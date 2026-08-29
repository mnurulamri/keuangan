<?php

//$periode = array();
foreach($result as $row){
    if($row['aktual'] > 0){
        $periode[$row['tahun']][$row['bulan']][$row['tgl']][$row['no_tiket']][] = $row;
    }
    $head_invoice = $row['tgl'].'-'.$row['bulan'].'-'.$row['tahun'];
}

$keterangan = 'Menyetujui Invoice PP No: '.$result[0]['no_invoice_pp'].' Tanggal: '.$head_invoice.' ';
//echo '<pre>';print_r($array_id_pengajuan_pemohon);echo '</pre>';exit();
?>


<input type="hidden" id="tgl" value="<?=$result[0]['tgl']?>" >
<input type="hidden" id="bulan" value="<?=$result[0]['bulan']?>" >
<input type="hidden" id="tahun" value="<?=$result[0]['tahun']?>" >
<input type="hidden" id="no_invoice_pp" value="<?=$result[0]['no_invoice_pp']?>" >
<input type="hidden" id="uraian" value="<?=$result[0]['uraian']?>" >
<input type="hidden" id="id_pengajuan_pemohon" value="<?=$array_id_pengajuan_pemohon?>" >

<table class="styled-table" id="invoice-table" width="100%">
    
        <tr style="background-color:#fff; color:#43A5BE">
            <th>PERIODE</th> 
            <th>NO INVOICE PP</th>
            <th>URAIAN</th>
        </tr>
        <tr style="color:#696969">
            <th><?=$head_invoice?></th>
            <th><?=$result[0]['no_invoice_pp']?></th>
            <th><?=$result[0]['uraian']?></th>
        </tr>
   
</table>
<table class="styled-table" id="invoice-table" width="100%">
    <thead>
        <tr style="background-color:#fff; color:#43A5BE">
            <th>NOMOR PENGAJUAN</th>
            <th>PROCOST</th>
            <th>AKUN</th>
            <th>KODE DANA</th>
            <th>SEGMEN</th>
            <th>BRUTO</th>
            <th>PAJAK</th>
			<th>NETTO</th>
        </tr>
    </thead>
    <tbody>
    <?php
    if(isset($periode)){

        foreach($periode as $thn => $bulan_data){
            foreach($bulan_data as $bln => $tgl_data){     
                foreach($tgl_data as $tgl => $no_tiket_data){
					$no = 1;
                    foreach($no_tiket_data as $no_tiket => $data_row){
                        $total_aktual = 0;
                        $total_pph = 0;
                        $total_netto = 0;
						$j=1;
                        foreach($data_row as $row){
                        
                                $row_nomor_pengajuan = $row['nomor_pengajuan'];
                                $row_no_invoice_pp = $row['no_invoice_pp'];
                                $row_uraian = $row['uraian'];
                                $row_no = $no;
                                $row_tanggal = $row['tgl'].'-'.$row['bulan'].'-'.$row['tahun'];
                            echo '<tr>';
                            echo '<td>' . $row['nomor_pengajuan'] . '</td>';
                            echo '<td>' . $row['kode_kegiatan'] . '</td>';
                            echo '<td>' . $row['kode_akun'] . '</td>';
                            echo '<td>' . $row['kode_dana'] . '</td>';
                            echo '<td>' . $row['deskripsi_dpsj'] . '</td>';
                            echo '<td class="text-right">' . number_format($row['aktual']) . '</td>';   
                            
                            // jika form = D02 maka gunakan pph_d02 dan netto_d02
                            if($row['form'] == 'D02'){
                                echo '<td class="text-right">' . $row['pph_d02'] . '</td>';      
                                echo '<td class="text-right">' . number_format($row['netto_d02']) . '</td>';   
                            } else {
                                echo '<td class="text-right">' . $row['pph'] . '</td>';      
                                echo '<td class="text-right">' . number_format($row['netto']) . '</td>';   
                            }
                            echo '</tr>';   
                            $j++;
                            $pph = ($row['form'] == 'D02') ? $row['pph_d02'] : $row['pph'];
                            $netto = ($row['form'] == 'D02') ? $row['netto_d02'] : $row['netto'];
                            $total_aktual += $row['aktual'];
                            $total_pph += $pph;
                            $total_netto += $netto;                           
                        }
						$no++;
                        echo '<tr style="font-weight:bold; background-color:#f0f0f0; color:#2c6a7a">';
                        //echo '<td colspan="8" class="text-center">TOTAL PERIODE ' . str_pad($tgl, 2, "0", STR_PAD_LEFT) . '-' . str_pad($bln, 2, "0", STR_PAD_LEFT) . '-' . $thn . ' (No Tiket: ' . $no_tiket . ')</td>';
                        echo '<td colspan="5" class="text-center total">TOTAL </td>';
                        echo '<td class="text-right total">' . number_format($total_aktual) . '</td>';
                        echo '<td class="text-right total">' . number_format($total_pph) . '</td>';
                        echo '<td class="text-right total">' . number_format($total_netto) . '</td>';
                        echo '</tr>';
                    }
                }
            }
        }        
    } else {
        echo '<tr><td colspan="11" class="text-center">Data tidak ditemukan</td></tr>';
    }
    ?>
    </tbody>
</table>

                            <div id="approvalForm" class="text-center">
                                <div style="clear:both;"></div>
                                <div class="form-group" class="row">
                                    <label for="tanggal" style="color:#555" class="col-sm-3">Tanggal Penyerahan</label>
                                    <div class="col-sm-5">
                                        <!--<input type="date" class="form-control col-sm-3" id="invoice_tanggal" name="invoice_tanggal" value="<?=date('Y-m-d')?>" formatted="yyyy-mm-dd">-->
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control pull-right" id="invoice_tanggal" name="invoice_tanggal">
                                        </div>
                                    </div>


                                        <!-- time Picker -->
                                        <div class="bootstrap-timepicker col-sm-4">
                                            <div class="form-groupx">
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-clock-o"></i>
                                                    </div>
                                                    <input type="text" class="form-control timepicker" id="invoice_waktu" name="invoice_waktu" disabled >
                                                </div>
                                                <!-- /.input group -->
                                            </div>
                                            <!-- /.form group -->
                                        </div>

                                </div>
                                <div style="clear:both;"></div>
                                <div class="form-group" class="row">
                                    <label for="keterangan" style="color:#555" class="col-sm-3">Keterangan</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control col-sm-9" id="keterangan" name="keterangan" rows="3"><?=$keterangan?></textarea>
                                    </div>
                                </div>  
                            </div>                            
                            <div style="clear:both;"></div>
                            <br>
                            <div class="text-center">
                                <button class="btn btn-primary send_to_akuntan" data-no_tiket="<?=$result[0]['no_tiket']?>" data-id_pengajuan_pemohon="<?=$result[0]['id_pengajuan_pemohon']?>" ><i class="fa fa-check"></i> Lanjut Proses</button>
                                <button class="btn btn-danger send_to_akuntan_batalkan" data-no_tiket="<?=$result[0]['no_tiket']?>" data-id_pengajuan_pemohon="<?=$result[0]['id_pengajuan_pemohon']?>" ><i class="fa fa-times"></i> Batalkan</button>
                                <button class="btn btn-primary" id="simpan-kendali-dokumen" style="display:none" >Simpan Kendali Dokumen</button>
                            </div>

<script>

$(document).ready(function()
{
    //Datepicker
    $(document).on('focus', '#invoice_tanggal', function(){
        $('#invoice_tanggal').datepicker({
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
// tampilkan menit dan detik secara realtime mengikuti waktu client
setInterval(function() {
    var currentTime = new Date();
    var hours = String(currentTime.getHours()).padStart(2, '0');
    var minutes = String(currentTime.getMinutes()).padStart(2, '0');
    var seconds = String(currentTime.getSeconds()).padStart(2, '0');
    var formattedTime = hours + ':' + minutes + ':' + seconds;
    document.getElementById('invoice_waktu').value = formattedTime;
}, 1000);

// tampilkan tanggal saat dokumen dibuka dalam format bahasa Indonesia dengan format "Hari, DD Bulan YYYY"
$("#invoice_tanggal").val(function(){
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
#invoice-table tr td {
    border: 1px solid #ddd;
}
.total{
    background-color:#edf7fa;
    color:#2E7385;
    font-weight:bold;
}
</style>
