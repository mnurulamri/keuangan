<input type="hidden" value="<?php print_r($id_monitoring); ?>" id="id_monitoring" >

<table class="table-judul">
    <tr>
        <td>PERIODE PENGISIAN KAS</td>
        <td>: <input type="text" id="periode" value="" ></td>
    </tr>
    <tr>
        <td>SALDO AKHIR PERIODE</td>
        <td>: <input type="text" id="saldo_akhir" value="0" > </td>
    </tr>
</table>
<table class="table table-bordered table-striped" id="data-konfirmasi">     
    <thead>
        <tr>
            <th colspan="5" style="text-align:center; font-size:16px;">DATA FORM PENGISIAN KAS OPERASIONAL DAN KUITANSI YANG AKAN DICETAK</th>
        </tr>            
        <tr>
            <th>No</th>
            <th>Tanggal Pengajuan</th>
            <th>Nomor Pengajuan</th>
            <th>Uraian</th>
            <th style="text-align:center;">Nominal Pengajuan</th>
            <!--<th>Nominal Cair</th>-->
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1;
        $total_nominal = 0;
        foreach($kuitansi_data as $row): ?>
        <tr>
            <td style="text-align:center;"><?php echo $no++; ?></td>
            <td><?php echo $row['tgl_pengajuan']; ?></td>
            <td><?php echo $row['nomor_pengajuan']; ?></td>
            <td><?php echo $row['uraian']; ?></td>
            <td style="text-align:right;"><?php echo number_format($row['nominal_disetujui_umko']); ?></td>
            <!--<td><input type="text" value="<?php echo number_format($row['nominal_disetujui_umko']); ?>"></td>-->
        </tr>
        <?php 
        $total_nominal += $row['nominal_disetujui_umko'];
        endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4" class="total">TOTAL</td>
            <td class="total">
                <?php 
                    
                    echo number_format($total_nominal); 
                ?>
            </td>
        </tr>
    </tfoot>
</table>

<style>
#data-konfirmasi th, .total {
    color: #555555;
}

.total {
    text-align:right;
    font-weight:bold;
    color: #555555;
    padding-right:25px;
}
</style>