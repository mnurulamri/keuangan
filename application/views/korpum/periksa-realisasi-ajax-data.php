<?php
//cho '<pre>';
//print_r($sql2);
//print_r($array_realisasi);
//print_r($posts);
//echo '</pre>';
$array_monitoring = array();

foreach($posts as $row) {
    // masukkan array rincian ke dalam array daftar
    //$array_monitoring[$row['id']][] = $row['id'];
    foreach($array_rincian as $key => $value) {
        if($row['id_pengajuan_pemohon'] == $key) {
            $array_monitoring[$row['id']] = $value;
        }
    }
}
$array_realisasi_total = array();
$array_sisa_umko_total = array();
// hitung nilai realisasi per data pengajuan
foreach($array_monitoring as $keys => $values) {
    
    $total_realisasi = 0;
    $total_sisa_umko = 0;
    $total_komitmen = 0;
    foreach($values as $rows) {
        // inisialisasi total_realiasi
        
        // hitung total realisasi
        if(isset($array_realisasi[$rows['id']]['total_bruto'])) {
            $total_komitmen += $rows['komitmen'];
            $total_realisasi += $array_realisasi[$rows['id']]['total_bruto'];
            // hitung sisa umko dan simpan ke dalam array
            $total_sisa_umko += $rows['komitmen'] - $array_realisasi[$rows['id']]['total_bruto'];
        } else {
            $total_komitmen += $rows['komitmen'];
            $total_realisasi += 0;
            // hitung sisa umko dan simpan ke dalam array
            $total_sisa_umko += $rows['komitmen'] - 0;
        }

        // simpan total realisasi ke dalam array
        $array_realisasi_total[$keys] = $total_realisasi;
        
    }
    $array_sisa_umko_total[$keys] = $total_komitmen - $total_realisasi ?? 0;
}

//print_r($array_realisasi_total);
//print_r($array_sisa_umko_total);

if(!isset($posts) or empty($posts)){
    echo 'belum ada data pengajuan';
}

$html= '
<table class="styled-table" id="examplex" style="width:100%; border-collapse:collapse; border:1px solid #ddd;">
    <thead>
        <tr>
            <th>Tanggal Terima</th>
            <th>Nomor Pengajuan</th>
            <th>Unit</th>
            <th>Form</th>
            <th>Uraian</th>
            <th>Komitmen</th>
            <th>Nominal Cair</th>
            <th>Realisasi</th>
            <th>Sisa UMKO</th>
            <th>Catatan</th>
            <th>Status</th>
            <th colspan="2"></th>
        </tr>
    </thead>
    <tbody>';

// jika $array_monitoring belum ada, set ke array kosong
/*if(!isset($array_monitoring) or empty($array_monitoring)){
    $html.= '<thead><tr><th colspan="10" class="text-center">Belum ada data pengajuan</th></tr></thead>';
    $html.= '<tbody><tr><td colspan="10" class="text-center">Silakan buat pengajuan terlebih dahulu</td></tr></tbody>';
    $html.= '</table>';
    echo $html;
    return;
}*/

$barisKe = 1;

foreach($array_monitoring as $key => $value)
{
    // hitung total komitmen per pengajuan
    $total_komitmen_perpengajuan = 0;
    foreach($value as $rows) {
        $total_komitmen_perpengajuan += $rows['komitmen'];
    }
    
    $tgl_terima = isset($posts[$key]['anggaran_tgl_disetujui']) ? dateTimeToTanggal($posts[$key]['anggaran_tgl_disetujui']) : '';
    // Tambahkan id unik untuk setiap detail toggle
    $toggleId = 'detail-' . $key;
    $toggleHeadId = 'head-' . $key;
        
    $html.= '<tr style="background-color:#f9f9f9; border:1px solid #ddd" id="'.$toggleHeadId.'">'; // No Pengajuan
    $html.= '
        <td>'.$tgl_terima.'</td>
        <td class="text-info">'.$posts[$key]['nomor_pengajuan'].'</td>
        <td>'.$array_deskripsi_dpsj[$posts[$key]['kode_dpsj']].'</td>
        <td>'.$posts[$key]['form'].'</td>
        <td>'.$posts[$key]['uraian'].'</td>
        <td class="text-right">'.number_format($total_komitmen_perpengajuan).'</td>
        <td class="text-right">'.number_format($posts[$key]['nominal_umko_cair']).'</td>
        <td class="text-right">'.number_format($array_realisasi_total[$key]).'</td>
        <td class="text-right">'.number_format($array_sisa_umko_total[$key]).'</td>
        <td>
            <button class="btn btn-info btn-xs fetch-logs" data-id="'.$posts[$key]['id'].'" data-nomor_pengajuan="'.$posts[$key]['nomor_pengajuan'].'" data-toggle="modal" data-target="#modal-catatan">View</button>
        </td>
        <td colspan="1" class="text-danger text-center"><strong id="status_'.$posts[$key]['id_pengajuan_pemohon'].'">'.nama_status($posts[$key]['kode_status']).'</strong></td>
        
        <td>';
        
        if($posts[$key]['kode_status'] == '61') {
            // jika nama_form adalah D02, maka jangan tampilkan tombol Periksa SPJ
            if($posts[$key]['form'] != 'D02') {
                $html.= '
                <button class="btn btn-success btn-xs" onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')">Periksa SPJ</button>';
            } else {
                $html.= '';
            }
        }
        
        if($posts[$key]['kode_status'] == '13' or $posts[$key]['kode_status'] == '61') {
    $html.='
            <button id="button_verifikasi_'.$posts[$key]['id'].'" class="btn btn-primary btn-xs lanjut-proses" data-id_monitoring="'.$posts[$key]['id'].'" data-id_pengajuan_pemohon="'.$posts[$key]['id_pengajuan_pemohon'].'" data-form="'.$posts[$key]['form'].'" data-toggle="modal" data-target="#modal-catatan">Verifikasi</button>
        </td>';
        }


    $html.= '
    </tr>';

    $html.= '
    <tr id="'.$toggleId.'" style="display:none;" class="detail-row">
        <td colspan="11" style="text-align:center" class="detail-row-rincian">
            <table id="tabel" class="detail-row-rincian-tabel" style="margin:auto; width:100%; border-collapse:collapse; border:1px solid #ddd; margin-top:10px;">
                <tr id="'.$toggleId.'" style="background-color:#f7f7f7;color:#777">
                    <th style="padding:5px; text-align:center; border:1px solid #ddd">Kode Procost</th>
                    <th style="padding:5px; text-align:center; border:1px solid #ddd">Nama Procost</th><th style="padding:5px; text-align:center; border:1px solid #ddd">Kode Akun</th>
                    <th style="padding:5px; text-align:center; border:1px solid #ddd">Deskripsi Akun</th><th style="padding:5px; text-align:center; border:1px solid #ddd">Jumlah UMKO</th>
                    <th style="padding:5px; text-align:center; border:1px solid #ddd">Realisasi</th><th style="padding:5px; text-align:center; border:1px solid #ddd">Sisa UMKO</th>
                    <th style="padding:5px; text-align:center; border:1px solid #ddd">Rincian Biaya</th>
                </tr>';

                $total_komitmen = 0;
                $total_realisasi = 0;
                $n=1;
                foreach($value as $row) {
                    // jika nomor_pengajuan belum ada di posts, set nomor_pengajuan ke -
                    if(!isset($posts[$key]['nomor_pengajuan']) or $posts[$key]['nomor_pengajuan'] == '') {
                        $posts[$key]['nomor_pengajuan'] = '-';
                    }
                    
                    // jika uraian belum ada di posts, set uraian ke -
                    if(!isset($posts[$key]['uraian']) or $posts[$key]['uraian'] == '') {
                        $posts[$key]['uraian'] = '-';
                    }

                    // jika kode_status belum ada di posts, set kode_status ke 0 (belum diajukan)
                    if(!isset($posts[$key]['kode_status']) or $posts[$key]['kode_status'] == '') {
                        $posts[$key]['kode_status'] = 0; // set kode_status ke 0 (belum diajukan)
                    }
                    // jika kode_status ada di posts, set kode_status ke kode_status
                    else {
                        $posts[$key]['kode_status'] = $posts[$key]['kode_status'];
                    }
                    // jika kode_status ada di posts, set kode_status ke kode_status
                    if(!isset($posts[$key]['id_pengajuan_pemohon']) or $posts[$key]['id_pengajuan_pemohon'] == '') {
                        $posts[$key]['id_pengajuan_pemohon'] = 0; // set id_pengajuan_pemohon ke 0
                    } else {
                        $posts[$key]['id_pengajuan_pemohon'] = $posts[$key]['id_pengajuan_pemohon'];
                    }
                    // jika nomor_pengajuan ada di posts, set nomor_pengajuan ke nomor_pengajuan
                    if(!isset($posts[$key]['nomor_pengajuan']) or $posts[$key]['nomor_pengajuan'] == '') {
                        $posts[$key]['nomor_pengajuan'] = '-'; // set nomor_pengajuan ke -
                    } else {
                        $posts[$key]['nomor_pengajuan'] = $posts[$key]['nomor_pengajuan'];
                    }
                    // jika uraian ada di posts, set uraian ke uraian
                    if(!isset($posts[$key]['uraian']) or $posts[$key]['uraian'] == '') {
                        $posts[$key]['uraian'] = '-'; // set uraian ke -
                    } else {
                        $posts[$key]['uraian'] = $posts[$key]['uraian'];
                    }

                    // jika $array_realisasi[$row['id']]['total_netto'] belum ada, set ke 0
                    if(!isset($array_realisasi[$row['id']]['total_netto']) or $array_realisasi[$row['id']]['total_netto'] == '') {
                        $array_realisasi[$row['id']]['total_netto'] = 0; // set total_netto ke 0
                    } else {
                        $array_realisasi[$row['id']]['total_netto'] = $array_realisasi[$row['id']]['total_netto'];
                    }

                    // jika $array_realisasi[$row['id']]['total_bruto'] belum ada, set ke 0
                    if(!isset($array_realisasi[$row['id']]['total_bruto']) or $array_realisasi[$row['id']]['total_bruto'] == '') {
                        $array_realisasi[$row['id']]['total_bruto'] = 0; // set total_netto ke 0
                    } else {
                        $array_realisasi[$row['id']]['total_bruto'] = $array_realisasi[$row['id']]['total_bruto'];
                    }
                    

                    $html.= '<tr class="data-row-realisasi">';
                    $html.= '<td class="kode_kegiatan" style="background-color:#fff;padding:5px; border:1px solid #ddd">'.$row['kode_kegiatan'].'</td>';
                    $html.= '<td style="background-color:#fff;padding:5px; border:1px solid #ddd">'.$row['nama_kegiatan'].'</td>';
                    $html.= '<td style="background-color:#fff;padding:5px; border:1px solid #ddd">'.$row['kode_akun'].'</td>';
                    $html.= '<td style="background-color:#fff;padding:5px;">'.$row['deskripsi_akun'].'</td>';
                    $html.= '<td style="background-color:#fff;padding:5px;border-right:1px solid #ddd;" class="text-right">'.number_format($row['komitmen']).'</td>';
                    $html.= '<td id="realisasi_'.$row['id'].'" data-id="'.$row['id'].'" class="realisasi text-right" style="background-color:#fff;padding:5px;border-right:1px solid #ddd;">'.number_format($array_realisasi[$row['id']]['total_bruto']).'</td>';
                    $html.= '<td id="sisa_umko_'.$row['id'].'" class="sisa_umko text-right" style="background-color:#fff;padding:5px;border-right:1px solid #ddd;">'.number_format($row['komitmen'] - $array_realisasi[$row['id']]['total_bruto']).'</td>';
                    $html.= '<td style="background-color:#fff;padding:5px;border-right:1px solid #ddd;"><button class="btn btn-success btn-xs periksa-realisasi" data-nomor_pengajuan="'.$key.'" data-id="'.$row['id'].'" data-id_monitoring="'.$posts[$key]['id'].'" data-toggle="modal" data-target="#modal-realisasi">Periksa</button></td>';
                    $html.= '<td style="background-color:#fff;padding:5px;border-right:1px solid #ddd;"><button class="btn btn-warning btn-xs view-realisasi" data-nomor_pengajuan="'.$key.'" data-id="'.$row['id'].'" data-toggle="modal" data-target="#modal-realisasi">view</button></td>';
        
                    //$html.= '<td style="border-right:1px solid #ddd;"><button class="btn btn-warning btn-xs view-realisasi" data-nomor_pengajuan="'.$key.'" data-id="'.$row['id'].'" data-toggle="modal" data-target="#modal-realisasi">view</button></td>';
                    $html.= '</tr>';
                    $n++;

                    $total_komitmen += $row['komitmen'];
                    $total_realisasi += $array_realisasi[$row['id']]['total_bruto'];
                }

            $html.= '<tr style="background-color:#eee;font-weight:bold; border-top:2px solid #ddd;">
                <td colspan="4" style="text-align:right; border-right:2px solid #ddd;">TOTAL</td>
                <td style="border-right:1px solid #ddd;padding:5px;" class="text-right">'.number_format($total_komitmen).'</td>
                <td style="border-right:1px solid #ddd;padding:5px;" class="text-right">'.number_format($total_realisasi).'</td>
                <td style="border-right:1px solid #ddd;padding:5px;" class="text-right">'.number_format($total_komitmen - $total_realisasi).'</td>
            </tr>';
        
        $html.= 
            '</table>
        </td>
    </tr>';

    //$html.= '<tr>'; // Separator row
    //$html.= '<td colspan="5" style="border-left:1px solid #fff; border-right:1px solid #fff;border-top:1px solid #ddd; "></td>'; // Empty row for spacing
    //$html.= '</tr>';
}

$html.= '</tbody></table>';
    echo $html;

/*-------------------------------------------------------------------------*/



echo $this->ajax_pagination_korpum->create_links();
?>


<script>
function toggleDetail(id, headId, barisKe) {
    
    var el = document.getElementById(id);
    var headRow = document.getElementById(headId);

    if (el.style.display === "none") {
        fadeIn(el, 500);      
        headRow.style.color = "#c86744ff"; // biru
        headRow.style.fontWeight = "bold";
        headRow.style.borderBottom = "2px solid #fff";        
        el.style.border = "2px solid #ddd";
    } else {
        fadeOut(el, 500);      
        headRow.style.color = "#444"; // default 
        headRow.style.fontWeight = "normal";
        headRow.style.borderBottom = "2px solid #ddd";
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