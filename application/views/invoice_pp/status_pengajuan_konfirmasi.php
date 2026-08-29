<?php

//$periode = array();
foreach($result as $row){
    $periode[$row['tahun']][$row['bulan']][$row['tgl']][$row['no_tiket']][] = $row;
    $head_invoice = $row['tgl'].'-'.$row['bulan'].'-'.$row['tahun'];
}

$keterangan = 'Buat Invoice PP No: '.$result[0]['no_invoice_pp'].' Tanggal: '.$head_invoice.' ';
//echo '<pre>';print_r($array_id_pengajuan_pemohon);echo '</pre>';exit();
?>


<input type="hidden" id="tgl" value="<?=$result[0]['tgl']?>" >
<input type="hidden" id="bulan" value="<?=$result[0]['bulan']?>" >
<input type="hidden" id="tahun" value="<?=$result[0]['tahun']?>" >
<input type="hidden" id="no_invoice_pp" value="<?=$result[0]['no_invoice_pp']?>" >
<input type="hidden" id="uraian" value="<?=$result[0]['uraian']?>" >
<input type="hidden" id="id_pengajuan_pemohon" value="<?=$array_id_pengajuan_pemohon?>" >

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
<br><br>
<!--
<div class="text-center" style="margin-top:10px;">
    <button class="btn btn-success" id="terbayarkan" data-no_tiket="<?=$result[0]['no_tiket']?>" data-id_pengajuan_pemohon="<?=$array_id_pengajuan_pemohon?>"><i class="fa fa-money"></i> Terbayarkan </button>
    <button class="btn btn-warning" id="retur" data-no_tiket="<?=$result[0]['no_tiket']?>" data-id_pengajuan_pemohon="<?=$array_id_pengajuan_pemohon?>"><i class="fa fa-times"></i> Dikembalikan </button>
</div>
-->
<div class="row">
        <div class="col-md-6">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header text-center" style="padding:5px; background-color:#00a65a; color:#fff;">
                <b><i class="fa fa-money"></i> Terbayarkan</b>
            </div>
            <div class="box-body">
                <input type="date" class="form-control" id="tgl_transfer" name="tgl_transfer" value="<?=date('Y-m-d')?>">
            </div>
          </div>
          <!-- /.widget-user -->
        </div>
        <div class="col-md-6">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header text-center" style="padding:5px; background-color:#f56954; color:#fff;">
                <b><i class="fa fa-times"></i> Dikembalikan</b>
            </div>
            <div class="box-body text-center">

                <div class="simple-toggle" id="simpleToggle">
                <input type="radio" name="color" value="red" id="simple_dengan_perubahan"
                        data-no_tiket="<?=$result[0]['no_tiket']?>" 
                        data-id_pengajuan_pemohon="<?=$array_id_pengajuan_pemohon?>">
                <label for="simple_dengan_perubahan" class="toggle-option-simple" id="labelDenganPerubahan">Dengan Perubahan</label>
                
                <input type="radio" name="color" value="blue" id="simple_tanpa_perubahan" checked
                        data-no_tiket="<?=$result[0]['no_tiket']?>" 
                        data-id_pengajuan_pemohon="<?=$array_id_pengajuan_pemohon?>">
                <label for="simple_tanpa_perubahan" class="toggle-option-simple active" id="labelTanpaPerubahan">Tanpa Perubahan</label>
                </div>
        </div>
    </div>
</div>

<style>
  .simple-toggle {
    display: inline-flex;
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1px;
    cursor: pointer;
    gap: 6px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
  }
  
  .simple-toggle input {
    display: none;
  }
  
  .toggle-option-simple {
    padding: 5px 20px;
    border-radius: 4px;
    transition: all 0.1s cubic-bezier(0.4, 0, 0.2, 1);
    font-family: 'Segoe UI', Arial, sans-serif;
    font-weight: 200;
    cursor: pointer;
    text-align: center;
    min-width: 120px;
    font-size: 14px;
    letter-spacing: 0.5px;
  }
  
  /* Efek hover */
  .toggle-option-simple:hover {
    transform: scale(1.08);
    background: rgba(76, 175, 80, 0.1);
  }
  
  /* Style untuk option aktif */
  .toggle-option-simple.active {
    background: linear-gradient(135deg, #AE4CAA, #ed1a72);
    color: white;
    box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4);
  }
  
  /* Hover untuk option aktif */
  .toggle-option-simple.active:hover {
    transform: scale(1.02);
    background: linear-gradient(135deg, #a355a0, #ed1a72);
    box-shadow: 0 6px 16px rgba(76, 175, 80, 0.5);
  }
  
  /* Efek klik */
  .toggle-option-simple:active {
    transform: scale(0.98);
  }
</style>

<script>
var denganPerubahan = document.getElementById('simple_dengan_perubahan');
var tanpaPerubahan = document.getElementById('simple_tanpa_perubahan');
var labelDengan = document.getElementById('labelDenganPerubahan');
var labelTanpa = document.getElementById('labelTanpaPerubahan');

function updateToggleUI() {
  if (denganPerubahan.checked) {
    labelDengan.classList.add('active');
    labelTanpa.classList.remove('active');
    console.log('Dipilih: Dengan Perubahan');
    console.log('No Tiket:', denganPerubahan.getAttribute('data-no_tiket'));
    console.log('ID Pengajuan:', denganPerubahan.getAttribute('data-id_pengajuan_pemohon'));
  } else {
    labelDengan.classList.remove('active');
    labelTanpa.classList.add('active');
    console.log('Dipilih: Tanpa Perubahan');
    console.log('No Tiket:', tanpaPerubahan.getAttribute('data-no_tiket'));
    console.log('ID Pengajuan:', tanpaPerubahan.getAttribute('data-id_pengajuan_pemohon'));
  }
}

denganPerubahan.addEventListener('change', updateToggleUI);
tanpaPerubahan.addEventListener('change', updateToggleUI);

// Panggil sekali untuk inisialisasi
updateToggleUI();
</script>

<table class="styled-table invoice-table" width="100%" style="display:none;">
    
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
<table class="styled-table invoice-table" width="100%" style="display:none;">
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

// terbayarkan
$("#terbayarkan").on('click', function() {
    var no_tiket = $(this).data('no_tiket');
    var id_pengajuan_pemohon = $(this).data('id_pengajuan_pemohon');
    console.log(no_tiket, id_pengajuan_pemohon);
});

// toggle invoice table
$("#toggle-invoice-table").on('click', function() {
    $(".invoice-table").fadeToggle(500);
    if ($(".invoice-table").is(":visible")) {
        $("#toggle-invoice-table").html('<i class="fa fa-eye"></i> Sembunyikan Detail Invoice');
    } else {
        $("#toggle-invoice-table").html('<i class="fa fa-eye-slash"></i> Tampilkan Detail Invoice');
    }
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
