<?php
//echo '<pre>';
//print_r($sql); 
//print_r($posts); 
//$nama_status = nama_status(11); print_r($nama_status);
//var_dump(array_keys($array_deskripsi_dpsj));
//echo '</pre>';
//exit();
$barisKe = 1;
?>

<table class="styled-table" width="100%">
    <thead>
        <tr>
            <th>Tanggal Terima</th>
            <th>Nomor Pengajuan</th>
            <th>Unit</th>
            <th>Form</th>
            <th>Uraian</th>
            <th>Nominal Pengajuan</th>
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
                
                // jika statusnya belum diajukan, set disabled
                if($row['kode_status'] == 0) {
                    $text_decoration = '';
                } else if($row['kode_status'] == 12 || $row['kode_status'] == 33 || $row['kode_status'] == 52 || $row['kode_status'] == 63 || $row['kode_status'] == 65) {
                    $text_decoration = '';
                } else if($row['kode_status'] == 14 || $row['kode_status'] == 43 || $row['kode_status'] == 53 || $row['kode_status'] == 64) {
                    $text_decoration = 'color: red;';
                } else {
                    $text_decoration = '';
                }
                
                $komitmen = 0;
                if(isset($array_rincian_komitmen[$row['id_pengajuan_pemohon']])) {
                    foreach($array_rincian_komitmen[$row['id_pengajuan_pemohon']] as $rincian) {
                        $komitmen += $rincian['komitmen'];
                    }
                }
                
                $toggleId = 'detail-' . $row['id'];
                $toggleHeadId = 'head-' . $row['id'];

				// tambahkan kolom Realisasi dan Sisa    
                $subtotal_realisasi_main = 0; // inisialisasi subtotal realisasi
                $subtotal_sisa_main = 0; // inisialisasi subtotal sisa
                foreach($array_rincian[$row['id_pengajuan_pemohon']] as $value){                    
                    $subtotal_realisasi_main += $value['netto']; // jumlahkan realisasi
                    $subtotal_sisa_main += $value['sisa_komitmen']+$value['pph']; // jumlahkan sisa
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
                    <td onclick="toggleDetail('<?=$toggleId?>', '<?=$toggleHeadId?>', '<?=$barisKe?>')" style="cursor:pointer; <?=$text_decoration?>" id="<?=$toggleHeadId?>"><?= (isset($row['anggaran_tgl_disetujui'])|| !empty($row['anggaran_tgl_disetujui']) || is_null($row['anggaran_tgl_disetujui'])) ? dateTimeToTanggal($row['anggaran_tgl_disetujui']) : '' ?></td>
                    <td onclick="toggleDetail('<?=$toggleId?>', '<?=$toggleHeadId?>', '<?=$barisKe?>')" style="cursor:pointer; <?=$text_decoration?>" id="<?=$toggleHeadId?>"><?= htmlspecialchars($row['nomor_pengajuan'] ?? '') ?></td>
                    <td onclick="toggleDetail('<?=$toggleId?>', '<?=$toggleHeadId?>', '<?=$barisKe?>')" style="cursor:pointer; <?=$text_decoration?>" id="<?=$toggleHeadId?>"><?= htmlspecialchars($array_deskripsi_dpsj[$row['kode_dpsj']] ?? '') ?></td>
                    <td onclick="toggleDetail('<?=$toggleId?>', '<?=$toggleHeadId?>', '<?=$barisKe?>')" style="cursor:pointer; <?=$text_decoration?>" id="<?=$toggleHeadId?>"><?= htmlspecialchars($row['form'] ?? '') ?></td>
                    <td onclick="toggleDetail('<?=$toggleId?>', '<?=$toggleHeadId?>', '<?=$barisKe?>')" style="cursor:pointer; <?=$text_decoration?>" id="<?=$toggleHeadId?>"><?= htmlspecialchars($row['uraian'] ?? '') ?></td>
                    <td onclick="toggleDetail('<?=$toggleId?>', '<?=$toggleHeadId?>', '<?=$barisKe?>')" style="cursor:pointer; <?=$text_decoration?>" id="<?=$toggleHeadId?>" class="text-right"> <?= isset($komitmen) ? number_format($komitmen, 0, ',', '.') : '' ?></td>
                    <td>
                        <!--<button class="btn btn-info btn-xs view-catatan" data-id="<?=$row['id']?>" data-toggle="modal" data-target="#modal-catatan">View</button>-->
                        <button class="btn btn-info btn-xs fetch-logs" data-nomor_pengajuan="<?=$row['nomor_pengajuan']?>" data-no_pp="<?=$row['no_pp']?>" data-toggle="modal" data-target="#modal-catatan">View</button>
                    </td>
                    <td onclick="toggleDetail('<?=$toggleId?>', '<?=$toggleHeadId?>', '<?=$barisKe?>')" style="cursor:pointer; <?=$text_decoration?>" id="<?=$toggleHeadId?>"><?= nama_status($row['kode_status']) ?? $row['kode_status'] ?></td>
                    <td>
                        
                        <?php if ($row['kode_status'] == 21): ?>
                            <button class="btn btn-primary btn-xs approval" data-id_pengajuan_pemohon="<?=$row['id_pengajuan_pemohon']?>" data-id_monitoring="<?=$row['id']?>" data-kode_dpsj="<?=$row['kode_dpsj']?>" data-nama_form="<?=$row['form']?>" data-toggle="modal" data-target="#modal-approval" >Approval</button>
                        <?php else: ?>
                            <button class="btn btn-info btn-xs detail" data-id_pengajuan_pemohon="<?= $row['id_pengajuan_pemohon'] ?>" data-id_monitoring="<?= $row['id'] ?>" data-kode_dpsj="<?=$row['kode_dpsj']?>" data-toggle="modal" data-target="#modal-approval" >Detail</button>
                        <?php endif; ?>
                    </td>
                </tr>
                
                <!-- rincian pengajuan -->
                <tr id="<?=$toggleId?>" style="display:none;">
                    <td colspan="11">
                        <table id="tabel-rincian" style="width:100%; background-color:#fff;">
                            <tr>
                                <th style="border-left:1px solid #ddd;">Kode Procost</th>
                                <th>Nama Procost</th>
                                <th>Kode Akun</th>
                                <th>Deskripsi Akun</th>
                                <th>Kode Dana</th>
                                <th>Keterangan</th>
                                <th style="border-right:1px solid #ddd;">Komitmen</th>
                                <th style="border-right:1px solid #ddd;">Realisasi</th>
                                <th style="border-right:1px solid #ddd;">Sisa</th>
                                <th style="border-right:1px solid #ddd;">Rincian Biaya</th>
                            </tr>

                            <?php
                            $n=1;  // $n untuk menunjukkan baris
                            $nominal_pengajuan = 0; // inisialisasi nominal pengajuan
                            $subtotal_realisasi = 0; // inisialisasi subtotal realisasi
                            $subtotal_sisa = 0; // inisialisasi subtotal sisa
                            $html = '';
                            foreach($array_rincian[$row['id_pengajuan_pemohon']] as $row){
                                //echo '<pre>';
                                $html.= '<tr>';
                                $html.= '<td class="text-center" style="border-left:1px solid #ddd;">'.$row['kode_kegiatan'].'</td>';
                                $html.= '<td class="text-left">'.$row['nama_kegiatan'].'</td>';
                                $html.= '<td class="text-center">'.$row['kode_akun'].'</td>';
                                $html.= '<td class="text-left">'.$row['deskripsi_akun'].'</td>';
                                $html.= '<td class="text-center">'.$row['kode_dana'].'</td>';
                                $html.= '<td class="text-left">'.$row['keterangan'].'</td>';
                                $html.= '<td class="text-right" style="border-right:1px solid #ddd;">'.number_format($row['komitmen']).'</td>';
                                $html.= '<td class="text-right" style="border-right:1px solid #ddd;">'.number_format($row['netto']).'</td>';
                                $html.= '<td class="text-right" style="border-right:1px solid #ddd;">'.number_format($row['sisa_komitmen']+$row['pph']).'</td>';

                                // jika status = 41 maka munculkan tombol view rincian biaya
                                /*if($posts[$key]['kode_status'] == 13 || $posts[$key]['kode_status'] == 41 || $posts[$key]['kode_status'] == 23 || $posts[$key]['kode_status'] == 51 || 
                                    $posts[$key]['kode_status'] == 51 || $posts[$key]['kode_status'] == 61 || $posts[$key]['kode_status'] == 62 || $posts[$key]['kode_status'] == 71            
                                )*/
                                //if(in_array($posts[$key]['kode_status'], array(13, 23, 41, 51, 61, 62, 71))) {
                                if($row['aktual_report'] > 0) {
                                    $html.= '<td><button class="btn btn-default btn-xs view-realisasi" data-id="'.$row['id'].'" data-nomor_pengajuan="'.$row['nomor_pengajuan'].'" data-toggle="modal" data-target="#modal-realisasi">View</button></td>';
                                } else {
                                    $html.= '<td></td>';
                                }

                                    // Check if this is the first row to add action buttons
                                    /*if($n > 1){
                                        $html.='';
                                    } else {
                                        $html.= '';	
                                    }*/

                                $html.= '</tr>';
                                //echo '</pre>';
                                $n++;
                                $nominal_pengajuan += $row['komitmen']; // jumlahkan komitmen
                                $subtotal_realisasi += $row['netto']; // jumlahkan realisasi
                                $subtotal_sisa += $row['sisa_komitmen']+$row['pph']; // jumlahkan sisa
                            }

                            // baris total
                            $html.= '<tr>'; // Separator row
                            $html.= '<td colspan="6" class="text-right" style="border-left:1px solid #ddd; color:#888"><b>Total: </b></td>'; // Empty row for spacing
                            $html.= '<td class="text-right" style="border-left:1px solid #fff; border-bottom:1px solid #fff">'.number_format($nominal_pengajuan).'</td>'; // Empty row for spacing
                            $html.= '<td class="text-right" style="border-right:1px solid #ddd; border-bottom:1px solid #fff">'.number_format($subtotal_realisasi).'</td>'; // Empty row for spacing
                            $html.= '<td class="text-right" style="border-right:1px solid #ddd; border-bottom:1px solid #fff">'.number_format($subtotal_sisa).'</td>'; // Empty row for spacing
                            //$html.= '<td style="border-right:1px solid #ddd; border-bottom:1px solid #fff"></td>'; // Empty row for spacing
                            $html.= '</tr>';
                            $html.= '<tr>'; // Separator row
                            $html.= '<td colspan="9" style="border-left:1px solid #fff; border-right:1px solid #fff;border-top:2px solid #ddd; border-bottom:1px solid #fff"></td>'; // Empty row for spacing
                            $html.= '</tr>';
                            echo $html;
                            ?>
                        </table>
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


<script>
function toggleDetail(id, headId, barisKe) {
    
    var el = document.getElementById(id);
    var headRow = document.getElementById(headId);

    if (el.style.display === "none") {
        fadeIn(el, 500);      
        headRow.style.color = "#c86744ff"; // biru
        headRow.style.fontWeight = "bold";
        //headRow.style.borderBottom = "1px solid #fff";        
        //el.style.border = "1px solid #ddd";
    } else {
        fadeOut(el, 500);      
        headRow.style.color = "#444"; // default 
        headRow.style.fontWeight = "normal";
        //headRow.style.borderBottom = "1px solid #ddd";
    }
    if(barisKe > 1){
        var el = document.getElementById(headId);
        if (el.style.display === "none") {
            fadeIn(el, 500);
        } else {
            fadeOut(el, 500);
        }
    }   
}
function fadeIn(element, duration) {
    element.style.opacity = 0;
    element.style.display = "";
    var last = +new Date();
    var tick = function() {
        element.style.opacity = +element.style.opacity + (new Date() - last) / duration;
        last = +new Date();
        if (+element.style.opacity < 1) {
            (window.requestAnimationFrame && requestAnimationFrame(tick)) || setTimeout(tick, 16);
        } else {
            element.style.opacity = 1;
        }
    };
    tick();
}
function fadeOut(element, duration) {
    element.style.opacity = 1;
    var last = +new Date();
    var tick = function() {
        element.style.opacity = +element.style.opacity - (new Date() - last) / duration;
        last = +new Date();
        if (+element.style.opacity > 0) {
            (window.requestAnimationFrame && requestAnimationFrame(tick)) || setTimeout(tick, 16);
        } else {
            element.style.opacity = 0;
            element.style.display = "none";
        }
    };
    tick();
}
</script>