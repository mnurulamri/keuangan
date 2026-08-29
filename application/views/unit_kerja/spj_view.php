<?php 
//echo '<pre>'; print_r($result); echo '</pre>';exit();
$id_pengajuan_rincian = isset($result[0]['id']) ? $result[0]['id'] : '';
$id_pengajuan_pemohon = isset($result[0]['id_pengajuan_pemohon']) ? $result[0]['id_pengajuan_pemohon'] : '';
$kode_akun = isset($result[0]['kode_akun']) ? $result[0]['kode_akun'] : '';
$deskripsi_akun = isset($result[0]['deskripsi_akun']) ? $result[0]['deskripsi_akun'] : '';
$kode_kegiatan = isset($result[0]['kode_kegiatan']) ? $result[0]['kode_kegiatan'] : '';
$nama_kegiatan = isset($result[0]['nama_kegiatan']) ? $result[0]['nama_kegiatan'] : '';
$nomor_pengajuan = isset($result[0]['nomor_pengajuan']) ? $result[0]['nomor_pengajuan'] : '';
$kode_dana = isset($result[0]['kode_dana']) ? $result[0]['kode_dana'] : '';
$keterangan = isset($result[0]['keterangan']) ? $result[0]['keterangan'] : '';
$jenis_biaya = isset($result[0]['jenis_biaya']) ? $result[0]['jenis_biaya'] : '';
$jadwal = isset($result[0]['jadwal']) ? $result[0]['jadwal'] : '';
$komitmen = isset($result[0]['komitmen']) ? $result[0]['komitmen'] : '0';
//$tanggal = isset($result[0]['tanggal']) ? date('d-m-Y', strtotime($result[0]['tanggal'])) : '';

?>

                          
<div class="containerx">
    
    <input type="hidden" id="id" value="0" >
    <input type="hidden" id="newId" value="0" >
    <input type="hidden" id="komitmen" value="<?=$komitmen?>">

    <table  style="margin:auto; width:80%" class="tablex table-bordered table-stripedx">
        <tr>
            <td class="label-1">KEGIATAN</td>
            <td>:</td>
            <td width="70%" id="kegiatan" contenteditable="true"><?=$keterangan?></td>
        </tr>
        <tr style="display:none">
            <td class="label-1">HARI/TANGGAL</td>
            <td>:</td>
            <td id="jadwal" contenteditable="true"><?=$jadwal?></td>
        </tr>
        <!--<tr>
            <td class="label-1">JENIS BIAYA</td>
            <td>:</td>
            <td id="jenis_biaya" contenteditable="true"><?=$jenis_biaya?></td>
        </tr>-->
		<input type="hidden" id="jenis_biaya" value="-" >
        <tr>
            <td class="label-1">NOMOR/NAMA AKUN</td>
            <td>:</td>
            <td id="akun"><?php echo $kode_akun .'/'.$deskripsi_akun; ?></td>
        </tr>
        <tr>
            <td class="label-1">NOMOR/NAMA PROCOST</td>
            <td>:</td>
            <td id="procost"><?php echo $kode_kegiatan .'/'.$nama_kegiatan; ?></td>
        </tr>
    </table>

    <div class="kotakx">
        <table style="margin:auto" id="tabel-rincian">
            <label for="">&nbsp;</label>
            <thead>
                <tr>
                    <td class="text-center" colspan="7" style="border-top:1px solid #fff;border-left:1px solid #fff; "></td>
					<td class="text-center" colspan="4" style="padding-top:1px; padding-bottom:1px; background: #F43378; color:#fff; font-weight:bold; font-size:12px">UMKO: <?php echo number_format($komitmen)?></td>
                </tr>
                <tr>
                    <td class="text-center" colspan="11"><b>REKAP BIAYA</b></td>
                </tr>
                <tr>
                    <th rowspan="2">NO</th>
                    <th rowspan="2">TANGGAL</th>
                    <th rowspan="2">KETERANGAN</th>
                    <th colspan="3">SATUAN</th>
                    <th rowspan="2">BRUTO</th>
                    <th colspan="2">TARIF PAJAK</th>
                    <th rowspan="2">NETTO (Rp)</th>
                    <th rowspan="2"></th>
                </tr>
                <tr>
                    <th>VOL</th>
                    <th>KET VOL</th>
                    <th>HARGA (Rp)</th>
                    <th>%</th>
                    <th>PPh (Rp)</th>
                </tr>            
            </thead>
            <tbody id="spj-rincian-tbody">
            <?php
            $n = 1; // Initialize counter for rows
			$total_brut = 0;
			$total_net = 0;
            // Loop through the result_realisasi array to display each row
            foreach($result_realisasi as $key => $row) {
                echo '<tr>';
                echo '<td id="'.$row['id'].'">'.$n.'</td>';
                echo '<td class="tanggal" contenteditable="true">'.dbToTanggal($row['tanggal']).'</td>';
                echo '<td class="keterangan" contenteditable="true">'.$row['keterangan'].'</td>';
                echo '<td class="volume" contenteditable="true">'.$row['volume'].'</td>';
                echo '<td class="ket_volume" contenteditable="true">'.$row['ket_volume'].'</td>';
                echo '<td class="harga" contenteditable="true">'.number_format($row['harga']).'</td>';
                echo '<td class="bruto text-right" contenteditable="true">'.number_format($row['bruto']).'</td>';
                echo '<td class="persen_pajak" contenteditable="true">'.$row['persen_pajak'].'</td>';
                echo '<td class="pph" contenteditable="true">'.number_format($row['pph']).'</td>';
                echo '<td class="netto text-right" contenteditable="true">'.number_format($row['netto']).'</td>';
                echo '
                    <td>
                        <button type="button" class="btn btn-danger btn-xs btn-remove-row-db" id="'.$row['id'].'"><i class="fa fa-times"></i></button>
                    </td>';
                echo '</tr>';
                $n++;
				$total_brut=$total_brut+$row['bruto'];
				$total_net=$total_net+$row['netto'];
            }
            ?>
            </tbody>
            <!-- Ganti bagian tfoot dengan ini -->
            <tfoot>
                <tr id="input-data-rincian" style="display:none">
                    <td id="newId"></td>
                    <td><input type="text" id="input-tanggal" value="" class="form-control input-sm tanggal" placeholder="Tanggal"></td>
                    <td><input type="text" id="input-keterangan" value="" class="form-control input-sm" placeholder="Keterangan"></td>
                    <td><input type="text" id="input-volume" value="0" class="form-control input-sm" placeholder="0"></td>
                    <td><input type="text" id="input-ket_volume" value="" class="form-control input-sm" placeholder="Ket Vol"></td>
                    <td><input type="text" id="input-harga" value="0" class="form-control input-sm" placeholder="0"></td>
                    <td><input type="text" id="input-bruto" value="0" class="form-control input-sm" placeholder="0" readonly></td>
                    <td><input type="text" id="input-persen_pajak" value="0" class="form-control input-sm" placeholder="0"></td>
                    <td><input type="text" id="input-pph" value="0" class="form-control input-sm" placeholder="0" readonly></td>
                    <td><input type="text" id="input-netto" value="0" class="form-control input-sm" placeholder="0" readonly></td>
                </tr>
                <tr style="background-color:#eee;font-weight:bold; font-size:12px; color:#555; border-top:2px solid #ddd;">
                    <td colspan="6" style="text-align:right">TOTAL</td>
                    <td class="total-bruto text-right"><?=number_format($total_brut)?></td>
                    <td></td>
                    <td></td>
                    <td class="total-netto text-right"><?=number_format($total_net)?></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                </tr>
                <tr>
                    <td colspan="10" style="border: 1px solid #fff">
                        <button type="button" class="btn btn-primary btn-sm" id="btn-add-row" data-id="<?=$id_pengajuan_rincian?>">
                            <i class="fa fa-plus"></i> Tambah Rincian
                        </button>
                        <button class="btn btn-warning btn-sm" id="simpan" data-id="<?=$id_pengajuan_rincian?>">
                            <i class="fa fa-floppy-o"></i> Simpan
                        </button>
                        <button class="btn btn-default btn-sm rincian-biaya-excel" data-id="<?=$id_pengajuan_rincian?>" data-nomor-pengajuan="<?=$nomor_pengajuan?>" data-id_pengajuan_pemohon="<?=$id_pengajuan_pemohon?>">
                            <i class="fa fa-file-excel-o text-default" style="font-size:16px;color:green;"></i> Cetak Excel
                        </button>
                        <i class="pesan"></i>
                    </td>
                </tr>
            </tfoot>
        </table>        
    </div>

</div>

<!-- bootstrap datepicker -->
<script src="<?=base_url()?>assets/AdminLTE/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?=base_url()?>assets/AdminLTE/plugins/datepicker/locales/bootstrap-datepicker.id.js"></script>
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?=base_url()?>assets/AdminLTE/plugins/datepicker/datepicker3.css">

<?php include(APPPATH.'views/unit_kerja/spj_script.php');?>

<style>
    /* Tables */
table#tabel-rincian tbody tr td, th {
	
}

#tabel-rincian {
    font-family: Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 90%;
    margin:auto;
}

#tabel-rincian tbody tr td {
    border: 1px solid #ddd;
    padding-top: 5px;
    padding-bottom: 5px;
    padding-left: 5px;
    padding-right: 5px;
    font-size:12px;
    cursor:pointer;
}

#tabel-rincian thead th{text-align:center}
/*#tabel-rincian tbody tr:nth-child(even){background-color: #f2f2f2;}
#tabel-rincian tbody tr:nth-child(odd){background-color: #fff;}

#tabel-rincian tbody tr:hover {background-color: #ddd;}*/

#tabel-rincian thead tr th {
    padding-top: 2px;
    padding-bottom: 2px;
    text-align: center;
    vertical-align: middle;
    background-color: #53BDA5;
    color: #fff;
    font-size:12px;
}
</style>