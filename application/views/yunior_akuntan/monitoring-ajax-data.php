<?php
//echo '<pre>';
//print_r($sql);
//print_r($array_realisasi);
//print_r($posts);

$array_monitoring = array(); // inisialisasi array monitoring

foreach($posts as $row) {
    // masukkan array rincian ke dalam array daftar
    //$array_monitoring[$row['id']][] = $row['id'];
    foreach($array_rincian as $key => $value) {
        if($row['id_pengajuan_pemohon'] == $key) {
            $array_monitoring[$row['id']] = $value;
        }
    }
}

//echo '</pre>';

if(!isset($posts) or empty($posts)){
    echo 'belum ada data pengajuan';
}

$html= 
'<table class="table table-bordered table-striped" id="examplex border="1">';

// jika $array_monitoring belum ada, set ke array kosong
/*if(!isset($array_monitoring) or empty($array_monitoring)){
    $html.= '<thead><tr><th colspan="10" class="text-center">Belum ada data pengajuan</th></tr></thead>';
    $html.= '<tbody><tr><td colspan="10" class="text-center">Silakan buat pengajuan terlebih dahulu</td></tr></tbody>';
    $html.= '</table>';
    echo $html;
    return;
}*/

    $html.='<tr>
                <th class="text-warning">Nomor Pengajuan</th>
                <th class="text-warning">Unit</th>
                <th class="text-warning">Form</th>
                <th class="text-warning">Uraian</th>
                <th class="text-warning">Nominal Pengajuan</th>
                <th class="text-warning">Nominal Disetujui</th>
                <th class="text-warning">Nominal Cair</th>
                <th class="text-success">TGL PP</th>
                <th class="text-success" width="150px">NO PP</th>
                <th class="text-success" width="100px">Bruto</th>
                <th class="text-success" width="100px">PPH 21</th>
                <th class="text-success" width="100px">PPH 23</th>
                <th class="text-success" width="100px">NETTO</th>
                <th>Aksi</th>
                <th>Catatan</th>
                <th>Status</th>
            </tr>';
$barisKe = 1;        
foreach($array_monitoring as $key => $value){
    
    /*$html.= '<tr>'; // No Pengajuan
    $html.= '
        <td colspan="3" class="text-info" style="border-top:1px solid #fff;border-left:1px solid #fff;font-size:15px;font-weight:bold;"><strong>'.$posts[$key]['nomor_pengajuan'].' '.$posts[$key]['uraian'].'</strong></td>
        <td colspan="5" class="text-danger text-center"><strong>'.nama_status($posts[$key]['kode_status']).'</strong></td>
        <td colspan="2" class="text-center">';
    $html.='</tr>';*/

    /*
    $html.='<tr>
                <td>'.htmlspecialchars($posts[$key]['nomor_pengajuan'] ?? '').'</td>
                <td>'.htmlspecialchars($posts[$key]['nama_unit'] ?? '').'</td>
                <td>'.htmlspecialchars($posts[$key]['form'] ?? '').'</td>
                <td>'.htmlspecialchars($posts[$key]['uraian'] ?? '').'</td>
                <td>'.number_format($posts[$key]['nominal_pengajuan'] ?? '').'</td>
                <td>'.number_format($posts[$key]['nominal_disetujui_umko'] ?? '').'</td>
                <td>'.number_format($posts[$key]['nominal_umko_cair'] ?? '').'</td>
                <td>
                    <ul>
                        <li>'.$posts[$key]['anggaran_keterangan_disetujui'].'</li>
                        <li>'.$posts[$key]['korpum_keterangan_disetujui'].'</li>
                        <li>'.$posts[$key]['manajer_keterangan_disetujui'].'</li>
                        <li>'.$posts[$key]['kasir_keterangan_disetujui'].'</li>
                    </ul>
                </td>
                <td>'.htmlspecialchars($posts[$key]['kode_status'] ?? '').'</td>
                <td><button class="btn btn-primary btn-xs">Detail</button></td>';
    $html.='</tr>';
    */

    // Tambahkan id unik untuk setiap detail toggle
    $toggleId = 'detail-' . $key;
    $toggleHeadId = 'head-' . $key;

    // Ubah tombol Detail menjadi tombol toggle
    $html .= '<script>
        function toggleDetail(id, headId, barisKe) {
            var el = document.getElementById(id);
            if (el.style.display === "none") {
                fadeIn(el, 500);
            } else {
                fadeOut(el, 500);
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
    </script>';
    

    // Head toggle
    $html .= '<tr id="'.$toggleHeadId.'" style="display:none;">
                <th>Nomor Pengajuan</th>
                <th>Unit</th>
                <th>Form</th>
                <th>Uraian</th>
                <th>Nominal Pengajuan</th>
                <th>Nominal Disetujui</th>
                <th>Nominal Cair</th>
                <th>Catatan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>';

    $html .= '<tr style="border-top:1px solid #aaa;">
                <td>'.htmlspecialchars($posts[$key]['nomor_pengajuan'] ?? '').'</td>
                <td>'.htmlspecialchars($posts[$key]['nama_unit'] ?? '').'</td>
                <td>'.htmlspecialchars($posts[$key]['form'] ?? '').'</td>
                <td>'.htmlspecialchars($posts[$key]['uraian'] ?? '').'</td>
                <td>'.number_format((float)($posts[$key]['nominal_pengajuan'] ?? 0)).'</td>
                <td>'.number_format((float)($posts[$key]['nominal_disetujui_umko'] ?? 0)).'</td>
                <td>'.number_format((float)($posts[$key]['nominal_umko_cair'] ?? 0)).'</td> 
                <td class="tgl-pp">'.dbToTanggal($posts[$key]['tgl_pp'] ?? '').'</td>
                <td class="no-pp">'.htmlspecialchars($posts[$key]['no_pp'] ?? '').'</td>
                <td class="bruto">'.number_format((float)($posts[$key]['realisasi_umko'] ?? 0)).'</td>
                <td class="pph21">'.number_format((float)($posts[$key]['pph_21'] ?? 0)).'</td>
                <td class="pph23">'.number_format((float)($posts[$key]['pph_23'] ?? 0)).'</td>
                <td class="netto">'.number_format((float)($posts[$key]['netto'] ?? 0)).'</td>
                <td>
                    <button class="btn btn-success btn-xs edit-pp">Edit PP</button>
                    <button class="btn btn-primary btn-xs simpan-pp" style="display:none" data-id="'.$posts[$key]['id'].'">Simpan PP</button>
                    <button class="btn btn-danger btn-xs cancel" style="display:none" data-id="'.$posts[$key]['id'].'">Cancel</button>
                </td>               
                <td>
                    <button class="btn btn-info btn-xs view-catatan" data-id="'.$posts[$key]['id'].'" data-toggle="modal" data-target="#modal-catatan">View</button>
                </td>
                <td>'.nama_status($posts[$key]['kode_status'] ?? '').'</td>
                <!--
                <td>
                    <button class="btn btn-primary btn-xs" onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')">Detail</button>
                </td>
                !-->
            </tr>';

    // Konten detail, disembunyikan secara default
    $html .= '
    <tr id="'.$toggleId.'" style="display:none;">
        <td colspan="10" style="padding:5;0; background-color:#f9f9f9; border-bottom:15px solid #ddd;">
            <strong>Detail Pengajuan:</strong>
            <br>
            <span class="text-muted">Klik tombol "Periksa" untuk melihat rincian biaya.</span>';
    //$html.= '<pre style="margin:0; padding:5px; background-color:#f9f9f9; border:1px solid #ddd;">';
    // Mulai isi detail (rincian biaya)
    $html.= '
    <table class="table table-bordered" style="margin-bottom:0;">
        <tr style="background-color:#f7f7f7;color:#3c8dbc;font-size:14px;">
            <th class="subhead">Kode Procost</th><th>Nama Procost</th>
            <th>Kode Akun</th>
            <th>Deskripsi Akun</th>
            <th>Kode Dana</th>
            <th>Jumlah UMKO</th>
            <th>Realisasi</th>
            <th>Sisa UMKO</th>
            <th colspan="2">Rincian Biaya</th>
        </tr>';

    $n=1;
    foreach($value as $row) {
        if(!isset($posts[$key]['nomor_pengajuan']) or $posts[$key]['nomor_pengajuan'] == '') {
            $posts[$key]['nomor_pengajuan'] = '-';
        }
        if(!isset($posts[$key]['uraian']) or $posts[$key]['uraian'] == '') {
            $posts[$key]['uraian'] = '-';
        }
        if(!isset($posts[$key]['kode_status']) or $posts[$key]['kode_status'] == '') {
            $posts[$key]['kode_status'] = 0;
        }
        if(!isset($posts[$key]['id_pengajuan_pemohon']) or $posts[$key]['id_pengajuan_pemohon'] == '') {
            $posts[$key]['id_pengajuan_pemohon'] = 0;
        }
        if(!isset($array_realisasi[$row['id']]['total_netto']) or $array_realisasi[$row['id']]['total_netto'] == '') {
            $array_realisasi[$row['id']]['total_netto'] = 0;
        }
        if(!isset($array_realisasi[$row['id']]['total_bruto']) or $array_realisasi[$row['id']]['total_bruto'] == '') {
            $array_realisasi[$row['id']]['total_bruto'] = 0;
        }

        $html.= '<tr style="color:#D81B60;font-size:14px;">';
        $html.= '<td>'.$row['kode_kegiatan'].'</td>';
        $html.= '<td>'.$row['nama_kegiatan'].'</td>';
        $html.= '<td>'.$row['kode_akun'].'</td>';
        $html.= '<td>'.$row['deskripsi_akun'].'</td>';        
        $html.= '<td>'.$row['kode_dana'].'</td>';
        $html.= '<td style="border-right:1px solid #ddd;">'.number_format($row['komitmen']).'</td>';
        $html.= '<td style="border-right:1px solid #ddd;">'.number_format($array_realisasi[$row['id']]['total_bruto']).'</td>';
        $html.= '<td style="border-right:1px solid #ddd;">0</td>';

        if($array_realisasi[$row['id']]['total_bruto'] > 0) {
            $html.= '<td style="border-right:1px solid #ddd;"><button class="btn btn-success btn-xs periksa-realisasi" data-nomor_pengajuan="'.$key.'" data-id="'.$row['id'].'" data-id_monitoring="'.$posts[$key]['id'].'" data-toggle="modal" data-target="#modal-realisasi">Periksa</button></td>';
            $html.= '<td style="border-right:1px solid #ddd;"><button class="btn btn-warning btn-xs view-realisasi" data-nomor_pengajuan="'.$key.'" data-id="'.$row['id'].'" data-toggle="modal" data-target="#modal-realisasi">view</button></td>';
        } else {
            $html.= '<td style="border-right:1px solid #ddd;"></td>';
        }
        $html.= '</tr>';
        $n++;
    }
    $html.= '<tr><td colspan="8" style="border-left:1px solid #fff; border-right:1px solid #fff;border-top:1px solid #ddd;"></td></tr>';
    $html.= '</table>';
    //$html.= '</pre>';
    $html.= '</td></tr>';
    $barisKe++;
}

$html.= '</tbody></table>';
    echo $html;

/*-------------------------------------------------------------------------*/



echo $this->ajax_pagination_realisasi->create_links();

?>