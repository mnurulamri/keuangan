<?php
echo '<pre>';
print_r($sql); 
//print_r($posts); 
//$nama_status = nama_status(11); print_r($nama_status);
//var_dump(array_keys($array_deskripsi_dpsj));
echo '</pre>';
//exit();
?>

<table class="table" width="100%">
    <thead>
        <tr>
            <th>Nomor Pengajuan</th>
            <th>Unit</th>
            <th>Form</th>
            <th>Uraian</th>
            <th>Nominal Pengajuan</th>
            <th>Nominal Disetujui</th>
            <th>Nominal Cair</th>
            <th>Tgl Penyerahan SPJ</th>
            <th>Catatan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $row): 
                // Set tanggal terima jika ada
                if (isset($row['tgl_terima']) && !empty($row['tgl_terima'])) {
                    $row['tgl_terima'] = date('Y-m-d', strtotime($row['tgl_terima']));
                } else {
                    $row['tgl_terima'] = '';
                }

                switch ($row['kode_status']) {
					case 11:
						//code block
						$keterangan = $row['anggaran_keterangan_disetujui'];
						break;
					case 12:
						//code block;
						$keterangan = $row['anggaran_keterangan_pending'];
						break;
					case 21:
						//code block
						$keterangan = $row['anggaran_keterangan_pending'];
						break;
					default:
						//code block
						$keterangan = '-';
                }
            ?>
                <tr>
                    <td><?= htmlspecialchars($row['nomor_pengajuan'] ?? '') ?></td>
                    <td><?= htmlspecialchars($array_deskripsi_dpsj[$row['kode_dpsj']] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['form'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['uraian'] ?? '') ?></td>
                    <td><?= isset($row['nominal_pengajuan']) ? number_format($row['nominal_pengajuan'], 0, ',', '.') : '' ?></td>
                    <td><?= isset($row['nominal_disetujui_umko']) ? number_format($row['nominal_disetujui_umko'], 0, ',', '.') : '' ?></td>
                    <td><?= isset($row['nominal_umko_cair']) ? number_format($row['nominal_umko_cair'], 0, ',', '.') : '0' ?></td>
                    <td>
                        <div class="input-group">
                            <input type="text" class="form-controlx tgl_penyerahan_spj_umko" value="<?= dbToTanggal($row['tgl_penyerahan_spj_umko'] ?? '') ?>">
                            <span class="input-group-addon btn btn-primary btn-xs simpan-tgl-terima-spj" data-id="<?=$row['id']?>">simpan</span>
                        </div>                        
                    </td>
                    <td>
                        <button class="btn btn-info btn-xs view-catatan" data-id="<?=$row['id']?>" data-toggle="modal" data-target="#modal-catatan">View</button>
                    </td>
                    <td><?= nama_status($row['kode_status']) ?? $row['kode_status'] ?></td>
                    <td>
                        
                        <?php if ($row['kode_status'] == 31): ?>
                            <button class="btn btn-primary btn-xs approval" data-id_pengajuan_pemohon="<?=$row['id_pengajuan_pemohon']?>" data-id_monitoring="<?=$row['id']?>" data-kode_dpsj="<?=$row['kode_dpsj']?>" data-toggle="modal" data-target="#modal-approval" >Approval</button>
                        <?php else: ?>
                            <button class="btn btn-info btn-xs detail" data-id_pengajuan_pemohon="<?= $row['id_pengajuan_pemohon'] ?>" data-id_monitoring="<?= $row['id'] ?>" data-kode_dpsj="<?=$row['kode_dpsj']?>" data-toggle="modal" data-target="#modal-approval" >Detail</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" align="center">Data tidak ditemukan.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php echo $this->ajax_pagination_anggaran->create_links(); ?>