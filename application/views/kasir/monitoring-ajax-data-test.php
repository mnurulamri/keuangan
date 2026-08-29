<?php
//echo '<pre>';
//print_r($sql); 
//print_r($posts); 
//$nama_status = nama_status(11); print_r($nama_status);
//var_dump(array_keys($array_deskripsi_dpsj));
//echo '</pre>';
//exit();*/
?>

<table class="styled-table" width="100%">
    <thead>
        <tr>
            <th>Tanggal Pengajuan</th>
            <th>Nomor Pengajuan</th>
            <th>Unit</th>
            <th>Form</th>
            <th>Uraian</th>
            <th>Nominal Pengajuan</th>
            <th>Nominal Disetujui</th>
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

				$text_decoration = '';

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
					case 14:
						//code block
						$text_decoration = 'text-decoration: line-through;';
						break;
					case 43:
						//code block
						$text_decoration = 'color: red;';
						break;
					case 54:
						//code block
						$text_decoration = 'color: red;';
						break;
					case 64:
						//code block
						$text_decoration = 'color: red;';
						break;
					default:
						//code block
						$keterangan = '-';
						$text_decoration = '';
                }
                
                $komitmen = 0;
                if(isset($array_rincian_komitmen[$row['id_pengajuan_pemohon']])) {
                    foreach($array_rincian_komitmen[$row['id_pengajuan_pemohon']] as $rincian) {
                        $komitmen += $rincian['komitmen'];
                    }
                }
			
            ?>
                <tr>
                    <!--
                    <td>
                        <div class="input-group">
                            <input type="date" class="form-control tgl_terima" id="input_tgl_terima_<?=$row['id']?>" name="tgl_terima" pattern="\d{4}-\d{2}-\d{2}" value="<?= htmlspecialchars($row['tgl_terima'] ?? '') ?>">
                            <?php
                            if ($row['tgl_terima']==0) {?>
                                <span class="input-group-addon btn btn-success simpan-tgl-terima" data-id="<?=$row['id']?>">simpan</span>
                            <?php } ?>
                            
                        </div>
                    </td>
                    -->
                    <!--<td><?= (isset($row['anggaran_tgl_disetujui'])|| !empty($row['anggaran_tgl_disetujui']) || is_null($row['anggaran_tgl_disetujui'])) ? dateTimeToTanggal($row['anggaran_tgl_disetujui']) : '' ?></td>-->
					<td style="<?= $text_decoration; ?>" ><?= dbToTanggal($row['tanggal'] ?? '') ?></td>
                    <td style="<?= $text_decoration; ?>" ><?= htmlspecialchars($row['nomor_pengajuan'] ?? '') ?></td>
                    <td style="<?= $text_decoration; ?>" ><?= htmlspecialchars($array_deskripsi_dpsj[$row['kode_dpsj']] ?? '') ?></td>
                    <td style="<?= $text_decoration; ?>" ><?= htmlspecialchars($row['form'] ?? '') ?></td>
                    <td style="<?= $text_decoration; ?>" ><?= htmlspecialchars($row['uraian'] ?? '') ?></td>
                    <!--<td><?= isset($row['nominal_pengajuan']) ? number_format($row['nominal_pengajuan'], 0, '.', ',') : '' ?></td>-->
                    <td style="<?= $text_decoration; ?>" class="text-right"><?= isset($komitmen) ? number_format($komitmen, 0, '.', ',') : '' ?></td>
                    <td style="<?= $text_decoration; ?>" class="text-right"><?= isset($row['nominal_disetujui_umko']) ? number_format($row['nominal_disetujui_umko'], 0, '.', ',') : '' ?></td>
                    <td>
                        <!--<button class="btn btn-info btn-xs view-catatan" data-id="<?=$row['id']?>" data-toggle="modal" data-target="#modal-catatan">View</button>-->
                        <button class="btn btn-info btn-xs fetch-logs" data-nomor_pengajuan="<?=$row['nomor_pengajuan']?>" data-no_pp="<?=$row['no_pp']?>" data-toggle="modal" data-target="#modal-catatan">View</button>
                    </td>
                    <td id="status_<?= $row['id'] ?>"><?= nama_status($row['kode_status']) ?? $row['kode_status'] ?></td>
                    <td id="button_<?= $row['id'] ?>">
                        
                        <?php if ($row['kode_status'] == 1 or $row['kode_status'] == 10): ?>
                            <button class="btn btn-primary btn-xs approval" data-id_pengajuan_pemohon="<?=$row['id_pengajuan_pemohon']?>" data-id_monitoring="<?=$row['id']?>" data-kode_dpsj="<?=$row['kode_dpsj']?>" data-nama_form="<?=$row['form']?>" data-toggle="modal" data-target="#modal-approval" >Verifikasi</button>
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