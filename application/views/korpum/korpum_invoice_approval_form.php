<?php

//$periode = array();
foreach($result as $row){
    if($row['aktual'] > 0){
        $periode[$row['tahun']][$row['bulan']][$row['tgl']][$row['no_tiket']][] = $row;
    }
    $head_invoice = $row['tgl'].'-'.$row['bulan'].'-'.$row['tahun'];
}

$keterangan = 'Transaksi selesai';
//echo '<pre>';print_r($array_id_pengajuan_pemohon);echo '</pre>';exit();
?>
<div id="test_script"></div>

<input type="hidden" id="tgl" value="<?=$result[0]['tgl']?>" >
<input type="hidden" id="bulan" value="<?=$result[0]['bulan']?>" >
<input type="hidden" id="tahun" value="<?=$result[0]['tahun']?>" >
<input type="hidden" id="no_invoice_pp" value="<?=$result[0]['no_invoice_pp']?>" >
<input type="hidden" id="uraian" value="<?=$result[0]['uraian']?>" >
<input type="hidden" id="id_pengajuan_pemohon" value="<?=$array_id_pengajuan_pemohon?>" >
<input type="hidden" id="nomor_pengajuan" value="<?=$result[0]['nomor_pengajuan']?>" >
<input type="hidden" id="id_monitoring" value="<?=$result[0]['id_monitoring']?>" >

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


<!-- tabel data pengajuan -->
<table class="styled-table" id="invoice-table" width="100%">
    <tr style="background-color:#fff; color:#43A5BE;">
        <th>UNTUK: <?=$result[0]['uraian_pengajuan']?></th>
        <th></th>
        <th>FORM: <?=$result[0]['form']?></th>
        <th></th>
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
<hr>
<div id="approvalForm" class="text-center">
    <div style="clear:both;"></div>
    <div class="form-group" class="row">
        <label for="tanggal" style="color:#555" class="col-sm-3">Tanggal Approval</label>
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
    <div class="form-group" class="row" style="display:none">
        <label for="keterangan" style="color:#555" class="col-sm-3">Keterangan</label>
        <div class="col-sm-9">
            <textarea class="form-control col-sm-9" id="keterangan" name="keterangan" rows="3"><?=$keterangan?></textarea>
        </div>
    </div>  
</div>
<br>
<div class="row">
    <div class="col-md-6">
        <!-- Widget: user widget style 1 -->
        <div class="box box-default" style="background-color:#eeeeee">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header text-center text-success" style="padding:5px; font-size:16px">
                <b><i class="fa fa-money"></i> Disetujui</b>
            </div>
            <div class="box-body text-center">
                <b class="text-success">Tanggal Transfer</b>
                <input type="date" class="form-controlx" id="tgl_transfer" name="tgl_transfer" value="<?=date('Y-m-d')?>">
                <br><br>
                <textarea class="form-control col-sm-9" id="keterangan_transfer" name="keterangan_transfer" rows="2"><?=$keterangan?></textarea>
                <br><br>
                <div class="row">
                    <br>
                    <div class="col-md-1"></div>
                    <button type="button" class="btn btn-success" id="btn-simpan-bayar">
                        <i class="fa fa-save"></i> Simpan 
                    </button>
                    <div class="col-md-1"></div>
                </div>
            </div>
        </div>
    <!-- /.widget-user -->
    </div>
    <div class="col-md-6">
        <!-- Widget: user widget style 1 -->
        <div class="box box-default" style="background-color:#eeeeee">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header text-center text-danger" style="padding:5px; font-size:16px">
                <b><i class="fa fa-times"></i> Retur </b>
            </div>
            <div class="box-body text-center">
                
                <div style="">
                    <b style="color:#ed1a72">Tanggal Retur dari PAU </b>
                    <input type="date" class="form-controlx" id="tgl_retur_dari_pau" name="tgl_retur_dari_pau" value="<?=date('Y-m-d')?>">
                    <br><br>
                    <textarea class="form-control col-sm-6" id="keterangan_retur_pau" name="keterangan_retur_pau" rows="2">Transaksi...</textarea>
                </div>
                <br><br><br>
                <div class="simple-toggle" id="simpleToggle">
                    <input type="radio" name="retur" value="tanpa_perubahan" id="tanpa_perubahan"
                            data-no_tiket="<?=$result[0]['no_tiket']?>" 
                            data-id_pengajuan_pemohon="<?=$array_id_pengajuan_pemohon?>">
                    <label for="tanpa_perubahan" class="toggle-option-simple active" id="labelTanpaPerubahan">Pending</label>
                    
                    <input type="radio" name="retur" value="dengan_perubahan" id="dengan_perubahan" checked
                            data-no_tiket="<?=$result[0]['no_tiket']?>" 
                            data-id_pengajuan_pemohon="<?=$array_id_pengajuan_pemohon?>">
                    <label for="dengan_perubahan" class="toggle-option-simple" id="labelDenganPerubahan">Dikembalikan</label>
                    
                    <input type="radio" name="retur" value="dibatalkan" id="dibatalkan"
                            data-no_tiket="<?=$result[0]['no_tiket']?>" 
                            data-id_pengajuan_pemohon="<?=$array_id_pengajuan_pemohon?>">
                    <label for="dibatalkan" class="toggle-option-simple active" id="labelDibatalkan">Dibatalkan</label>
                </div>
                
                <div style="">
                    
                </div>
                <button type="button" class="btn btn-danger" id="btn-simpan-retur">
                    <i class="fa fa-save"></i> Simpan 
                </button>
            </div>
        </div>
    </div>                                       
</div>
<script>
var denganPerubahan = document.getElementById('dengan_perubahan');
var tanpaPerubahan = document.getElementById('tanpa_perubahan');
var labelDengan = document.getElementById('labelDenganPerubahan');
var labelTanpa = document.getElementById('labelTanpaPerubahan');
var dibatalkan = document.getElementById('dibatalkan');
var labelDibatalkan = document.getElementById('labelDibatalkan');

function updateToggleUI() {
  if (denganPerubahan.checked) {
    labelDengan.classList.add('active');
    labelTanpa.classList.remove('active');
    labelDibatalkan.classList.remove('active');
    var selectedDate = new Date($('#tgl_retur_dari_pau').val());
    var formattedDate = selectedDate.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    
    keterangan_retur_pau.value = 'Transaksi dikembalikan, tanggal retur dari PAU: ' + formattedDate;

    console.log('Dipilih: Dengan Perubahan');
    console.log('No Tiket:', denganPerubahan.getAttribute('data-no_tiket'));
    console.log('ID Pengajuan:', denganPerubahan.getAttribute('data-id_pengajuan_pemohon'));
  } else if (tanpaPerubahan.checked) {
    labelDengan.classList.remove('active');
    labelTanpa.classList.add('active');
    labelDibatalkan.classList.remove('active');
    var selectedDate = new Date($('#tgl_retur_dari_pau').val());
    var formattedDate = selectedDate.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    keterangan_retur_pau.value = 'Transaksi dipending, tanggal retur dari PAU: ' + formattedDate;

    console.log('Dipilih: Tanpa Perubahan');
    console.log('No Tiket:', tanpaPerubahan.getAttribute('data-no_tiket'));
    console.log('ID Pengajuan:', tanpaPerubahan.getAttribute('data-id_pengajuan_pemohon'));
  } else {
    labelDengan.classList.remove('active');
    labelTanpa.classList.remove('active');
    labelDibatalkan.classList.add('active');
    var selectedDate = new Date($('#tgl_retur_dari_pau').val());
    var formattedDate = selectedDate.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    keterangan_retur_pau.value = 'Transaksi dibatalkan, tanggal retur dari PAU: ' + formattedDate;
  }
}

denganPerubahan.addEventListener('change', updateToggleUI);
tanpaPerubahan.addEventListener('change', updateToggleUI);
dibatalkan.addEventListener('change', updateToggleUI);

// Panggil sekali untuk inisialisasi
updateToggleUI();
</script>
<div class="text-center" style="margin-top:10px">
    <button class="btn btn-primary send_to_akuntan" data-no_tiket="<?=$result[0]['no_tiket']?>" data-id_pengajuan_pemohon="<?=$result[0]['id_pengajuan_pemohon']?>" >Send to Akuntan</button>
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

    // event tgl_transfer change -> set keterangan_transfer
    $('#tgl_transfer').on('change', function() {
        // rubah format tanggal menjadi "DD, d MM yyyy"
        var selectedDate = new Date($(this).val());
        var formattedDate = selectedDate.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        $('#keterangan_transfer').val('Transaksi selesai, tanggal transfer: ' + formattedDate);
    });

    // event tgl_retur_dari_pau change -> set keterangan_retur_pau berdasarkan toggle option dan tanggal
    $('#tgl_retur_dari_pau').on('change', function() {
        //alert('Tanggal retur dari PAU diubah. Silakan pilih salah satu opsi: Pending, Dikembalikan, atau Dibatalkan untuk memperbarui keterangan.');
        var tgl = new Date($(this).val());
        var formatTgl = tgl.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        
        if(denganPerubahan.checked){
            $('#keterangan_retur_pau').val('Transaksi dikembalikan, tanggal retur dari PAU: ' + formatTgl);
        } else if(tanpaPerubahan.checked){
            $('#keterangan_retur_pau').val('Transaksi pending, tanggal retur dari PAU: ' + formatTgl);
        } else if(dibatalkan.checked){
            $('#keterangan_retur_pau').val('Transaksi dibatalkan, tanggal retur dari PAU: ' + formatTgl);
        }
    });

    // Event Klik Terbayar
    $('#btn-simpan-bayar').on('click', function() {
        var data = {
            id_pengajuan_pemohon: $('#id_pengajuan_pemohon').val(),
            no_tiket: '<?=$result[0]['no_tiket']?>',
            kode_status: 76,
            tgl_transfer: $('#tgl_transfer').val(),
            keterangan: $('#keterangan_transfer').val(),
            
            invoice_tanggal: $("#invoice_tanggal").val(),
            invoice_waktu: $("#invoice_waktu").val(),
            nomor_pengajuan: $("#nomor_pengajuan").val(),
            id_monitoring: $("#id_monitoring").val()
        };
        
        kendali_dokumen(data); 
        //return false;
        prosesStatus('<?=base_url("korpum/invoice_pp/simpan_bayar")?>', data, 'Yakin ingin mengubah ke status TERBAYAR?');
    });	

    // Event Klik Terbayar
    $('#btn-simpan-retur').on('click', function() {
        // jika toggle option "Pending" dipilih, maka kode_status = 62
        // jika toggle option "Dikembalikan" dipilih, maka kode_status = 63
        // jika toggle option "Dibatalkan" dipilih, maka kode_status = 64
        var kode_status = 0;
        if(denganPerubahan.checked){
            kode_status = 63;
        } else if(tanpaPerubahan.checked){
            kode_status = 62;
        } else if(dibatalkan.checked){
            kode_status = 64;
        }

        var data = {
            id_pengajuan_pemohon: $('#id_pengajuan_pemohon').val(),
            no_tiket: '<?=$result[0]['no_tiket']?>',
            kode_status: kode_status,
            tgl_retur_dari_pau: $('#tgl_retur_dari_pau').val(),
            keterangan: $('#keterangan_retur_pau').val(),
            
            invoice_tanggal: $("#invoice_tanggal").val(),
            invoice_waktu: $("#invoice_waktu").val(),
            nomor_pengajuan: $("#nomor_pengajuan").val(),
            id_monitoring: $("#id_monitoring").val()
        };
        
        kendali_dokumen(data); 
        //return false;
        prosesStatus('<?=base_url("korpum/invoice_pp/simpan_retur")?>', data, 'Yakin ingin mengubah ke status RETUR?');
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

// Helper untuk AJAX
function prosesStatus(url, data, pesan) {
    if (confirm(pesan)) {
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            //dataType: 'json',
            success: function(response) {
                $("#test_script").html(response)
                /*if(response.status === 'success') {
                    $("#test_script").html(response)
                    //alert('Data berhasil disimpan!');
                    //location.reload();
                } else {
                    alert('Gagal: ' + response.message);
                }*/
            },
            error: function() {
                alert('Terjadi kesalahan koneksi.');
            }
        });
    }
}

//function kendali_dokumen(nomor_pengajuan, id_monitoring, invoice_tanggal, invoice_waktu, keterangan, kode_status){
function kendali_dokumen(data){
    //
    //console.log("Simpan catatan untuk pengajuan: " + kd_pengajuan + " dengan keterangan: " + anggaran_keterangan+ ' dan kode_status: '+kode_status);
    $.ajax({
        url: '<?=base_url("Kendali_dokumen/korpum_invoice")?>',
        type: 'POST',
        data: data,
        //dataType: 'json',
        success: function(res) {
            $("#test_script").html(res); //return false;
            alert('Catatan berhasil disimpan.');
            console.log(res);
        },
        error: function() {
            alert('Terjadi kesalahan saat menyimpan catatan.');
        }
    });
}

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

  .simple-toggle {
    display: inline-flex;
    background: #fff;
    border-radius: 12px;
    padding: 1px;
    cursor: pointer;
    /*gap: 6px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);*/
  }
  
  .simple-toggle input {
    display: none;
  }
  
  .toggle-option-simple {
    padding: 5px 20px;
    border-radius: 4px;
    transition: all 0.1s cubic-bezier(0.4, 0, 0.2, 1);
    font-family: 'Segoe UI', Arial, sans-serif;
    font-weight: 500;
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
    background: linear-gradient(135deg, #fa0, #fa0);
    color: white;
    box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4);
  }
  
  /* Hover untuk option aktif */
  .toggle-option-simple.active:hover {
    transform: scale(1.02);
    background: linear-gradient(135deg, #fa0, #fa0);
    box-shadow: 0 6px 16px rgba(76, 175, 80, 0.5);
  }
  
  /* Efek klik */
  .toggle-option-simple:active {
    transform: scale(0.98);
  }
</style>
