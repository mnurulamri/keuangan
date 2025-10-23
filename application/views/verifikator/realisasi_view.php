<?php 
//echo '<pre>'; print_r($result_realisasi); echo '</pre>';
$id_pengajuan_rincian = isset($result[0]['id']) ? $result[0]['id'] : '';
$kode_akun = isset($result[0]['kode_akun']) ? $result[0]['kode_akun'] : '';
$deskripsi_akun = isset($result[0]['deskripsi_akun']) ? $result[0]['deskripsi_akun'] : '';
$kode_kegiatan = isset($result[0]['kode_kegiatan']) ? $result[0]['kode_kegiatan'] : '';
$nama_kegiatan = isset($result[0]['nama_kegiatan']) ? $result[0]['nama_kegiatan'] : '';
$nomor_pengajuan = isset($result[0]['nomor_pengajuan']) ? $result[0]['nomor_pengajuan'] : '';
$kode_dana = isset($result[0]['kode_dana']) ? $result[0]['kode_dana'] : '';
$kegiatan = isset($result[0]['kegiatan']) ? $result[0]['kegiatan'] : '';
$jadwal = isset($result[0]['jadwal']) ? $result[0]['jadwal'] : '';
$jenis_biaya = isset($result[0]['jenis_biaya']) ? $result[0]['jenis_biaya'] : '';

?>

                          
<div class="containerx">
    

    <input type="hidden" id="id" value="0" >
    <input type="hidden" id="newId" value="0" >

    <table  style="margin:auto; width:80%" class="table table-bordered table-stripedx">
        <tr>
            <td class="label-1">KEGIATAN</td>
            <td>:</td>
            <td width="70%" id="kegiatan" contenteditable="true"><?php echo $kegiatan;?></td>
        </tr>
        <tr>
            <td class="label-1">HARI/TANGGAL/WAKTU/TEMPAT</td>
            <td>:</td>
            <td id="jadwal" contenteditable="true"><?php echo $jadwal;?></td>
        </tr>
        <tr>
            <td class="label-1">JENIS BIAYA</td>
            <td>:</td>
            <td id="jenis_biaya" contenteditable="true"><?php echo $jenis_biaya;?></td>
        </tr>
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

    <table class="table" style="margin:auto" id="tabel-rincian">
        <label for="">&nbsp;</label>
        <thead>
            <tr>
                <td class="text-center" colspan="10">REKAP BIAYA</td>
            </tr>
            <tr>
                <td rowspan="2">NO</td>
                <td rowspan="2">TANGGAL</td>
                <td rowspan="2">KETERANGAN</td>
                <td colspan="3">SATUAN</td>
                <td rowspan="2">BRUTO</td>
                <td colspan="2">TARIF PAJAK</td>
                <td rowspan="2">NETTO (Rp)</td>
            </tr>
            <tr>
                <td>VOL</td>
                <td>KET VOL</td>
                <td>HARGA (Rp)</td>
                <td>%</td>
                <td>PPh (Rp)</td>
            </tr>            
        </thead>
        <tbody>
            <?php
            $no = 1;
            $bruto_total = 0;
            $netto_total = 0;
            if (isset($result_realisasi) && is_array($result_realisasi)) {
                // Jika ada data realisasi, tampilkan data tersebut
                foreach ($result_realisasi as $row) {
                    echo '<tr>';
                    echo '<td class="text-center">' . $no . '</td>';
                    echo '<td class="tanggal" >' . $row['tanggal'] . '</td>';
                    echo '<td class="keterangan" >' . $row['keterangan'] . '</td>';
                    echo '<td class="volume" >' . $row['volume'] . '</td>';
                    echo '<td class="ket_volume" >' . $row['ket_volume'] . '</td>';
                    echo '<td class="harga" >' . number_format($row['harga'], 0, ',', '.') . '</td>';
                    echo '<td class="bruto" >' . number_format($row['bruto'], 0, ',', '.') . '</td>';
                    echo '<td class="tarif_pajak" >' . $row['persen_pajak'] . '</td>';
                    echo '<td class="pph" >' . number_format($row['pph'], 0, ',', '.') . '</td>';
                    echo '<td class="netto" >' . number_format($row['netto'], 0, ',', '.') . '</td>';
                    echo '</tr>';
                    $no++;
                    $bruto_total += $row['bruto'];
                    $netto_total += $row['netto'];
                }
            } else {
                // Jika tidak ada data, tampilkan satu baris kosong
                echo '<tr>';
                echo '<td class="text-center">1</td>';
                echo '<td class="tanggal" ></td>';
                echo '<td class="keterangan" ></td>';
                echo '<td class="volume" ></td>';
                echo '<td class="ket_volume" ></td>';
                echo '<td class="harga" ></td>';
                echo '<td class="bruto" ></td>';
                echo '<td class="tarif_pajak" ></td>';
                echo '<td class="pph"></td>';
                echo '<td class="netto" ></td>';
                echo '</tr>';
            }


            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" style="text-align:right">TOTAL</td>
                <td class="total"><?=number_format($bruto_total)?></td>
                <td></td>
                <td></td>
                <td><?=number_format($netto_total)?></td>
            </tr>
        </tfoot>
    </table>
</div>
