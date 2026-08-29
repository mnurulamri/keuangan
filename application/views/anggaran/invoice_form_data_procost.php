<?php
//echo '<pre>';print_r($sql);print_r($result);echo '</pre>';
?>
<table class="table" width="100%">
    <thead>
        <tr>
            <th>No</th>
            <th>NOMOR PENGAJUAN</th>
            <!--<th>NO INVOICE PP</th>-->
            <th>URAIAN</th>
            <th>PROCOST</th>
            <th>AKUN</th>
            <th>SUMBER DANA</th>
            <th>SEGMEN</th>
            <th>BRUTO</th>
            <th>PAJAK</th>
			<th>NETTO</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $no = 1;
    foreach ($result as $row) {
        echo '<tr>';
        echo '<td>' . $no . '</td>';
        echo '<td>' . $row['nomor_pengajuan'] . '</td>';
        //echo '<td>' . $no_invoice_pp . '</td>';
        echo '<td>' . $row['untuk'] . '</td>';
        echo '<td>' . $row['kode_kegiatan'] . '</td>';
        echo '<td>' . $row['kode_akun'] . '</td>';
        echo '<td>' . $row['kode_dana'] . '</td>';
        echo '<td>' . $row['deskripsi_dpsj'] . '</td>';
        echo '<td class="text-right">' . number_format($row['aktual']) . '</td>';    
        echo '<td class="text-right">' . $row['pph'] . '</td>';      
        echo '<td class="text-right">' . number_format($row['netto']) . '</td>';     
        echo '</tr>';   
        $no++; 
    }
    ?>
    </tbody>
</table>
<input type="text" value="<?= $id_pengajuan_pemohon ?>" id="id_pengajuan_pemohon" style="display:none">
<div class="text-center" style="margin-top:10px">
    <button class="btn btn-primary" id="simpan-rekap-procost">Simpan Rekap Procost</button>
</div>