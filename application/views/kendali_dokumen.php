<?php //echo '<pre>'; print_r($logs); echo '</pre>'; ?>
<table class="styled-table-head" width="100%">
    <tr>
        <td class="label-field">Nomor Pengajuan</td>
        <td>:</td>
        <td><?= htmlspecialchars($nomor_pengajuan ?? '') ?></td>
        <td class="label-field">Uraian Singkat</td>
        <td>:</td>
        <td><?= htmlspecialchars($uraian ?? '') ?></td>
    </tr>
    <tr>
        <td class="label-field">Tanggal Terima</td>
        <td>:</td>
        <td><?= $tgl_terima ?></td>
        <td class="label-field">Unit Pemohon</td>
        <td>:</td>
        <td><?= htmlspecialchars($unit_pemohon ?? '') ?></td>
    </tr>
    <tr>
        <td class="label-field">Nominal Pengajuan</td>
        <td>:</td>
        <td><?=isset($nominal_pengajuan) ? $nominal_pengajuan : '' ?></td>
        <td class="label-field">Nomor PP</td>
        <td>:</td>
        <td><?= htmlspecialchars($no_pp ?? '') ?></td>
    </tr>
</table>

<?php if (!empty($logs)): ?>
    <table class="styled-table-kendali-dokumen" width="100%">
        <thead>
            <tr>
                <th colspan="3" style="text-align:center; border:none; background:#fff;">PROSES KEUANGAN</th>
            </tr>
            <tr>
                <th>Tanggal</th>
                <th>Posisi Dokumen Sebelumnya</th>
                <th>Uraian</th>
                <th>Posisi Dokumen Sekarang</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($logs as $log): ?>
                <?php
                if($log['role']=='korpum' and $log['kode_status']==41){
                    $status = 'Retur - Pengisian SPJ';
                } if($log['role']=='anggaran' and $log['kode_status']==0){
                    $status = 'Ditolak';
                } else {
                    $status = htmlspecialchars($log['status'] ?? '');
                }
                ?>
                <tr>
                    <td><?= htmlspecialchars(dateTimeToTanggal($log['tanggal']) ?? '') ?></td>
                    <td><?= htmlspecialchars($log['role'] ?? '') ?></td>
                    <td><?= html_entity_decode($log['catatan'] ?? '') ?></td>                   
                    <td><?php echo dokumen_sekarang($log['kode_status']);?></td>
                    <td><?= $status ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Tidak ada catatan kendali dokumen.</p>
<?php endif; ?>
<style>
.label-field {
    font-weight:bold;
    color:#555;
    font-size: 0.97em;
    width:120px;
}
#tabel-kendali-dokumen td {
    /*padding: 5px;*/
    font-size: 12px;
}
#tabel-kendali-dokumen td:nth-child(1), #tabel-kendali-dokumen td:nth-child(4) {
    font-weight: bold;
    width: 150px;
}

/* Table Kendali Dokumen Styles */
.styled-table-kendali-dokumen {
   border-collapse: collapse;
   margin: 25px 0;
   font-size:12px;
   font-family: Arial, Helvetica, sans-serif;
   min-width: 400px;
   box-shadow: 0 0 5px rgba(0, 0, 0, 0.15);
}
.styled-table-kendali-dokumen thead tr {
   background-color: #e4e9e9ff;
   color: #444;
   text-align: left;
   font-size:1em;
}
.styled-table-kendali-dokumen thead tr th {
   padding: 5px 15px;
   border: none;
}
.styled-table-kendali-dokumen td {
   padding: 7px 15px;
}
.styled-table-kendali-dokumen tbody tr {
   border-bottom: 1px solid #e4e9e9ff;
}
.styled-table-kendali-dokumen tbody tr:nth-of-type(even) {
   background-color: #f3f3f3;
}
.styled-table-kendali-dokumen tbody tr:last-of-type {
   border-bottom: 2px solid #e4e9e9ff;
}
.styled-table-kendali-dokumen tbody tr.active-row {
   font-weight: bold;
   color: #009879;
}

/* Table Kendali Dokumen Head Styles */
.styled-table-head {
   border-collapse: collapse;
   /*margin: 25px 0;*/
   font-size:12px;
   font-family: Arial, Helvetica, sans-serif;
   min-width: 400px;
   box-shadow: 0 0 5px rgba(0, 0, 0, 0.15);
}
.styled-table-head tr {
   border-bottom: 1px solid #e4e9e9ff;
}
.styled-table-head tr td{
   padding: 5px;
}
.styled-table-head tr:nth-of-type(even) {
   background-color: #f3f3f3;
}
.styled-table-head tr:last-of-type {
   border-bottom: 2px solid #e4e9e9ff;
}
.styled-table-head tr.active-row {
   font-weight: bold;
   color: #009879;
}
</style>