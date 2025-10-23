<?php
//echo '<pre>';
//print_r($sql);
//print_r($array_realisasi);
//print_r($posts);

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

//echo '</pre>';

if(!isset($posts) or empty($posts)){
    echo 'belum ada data pengajuan';
}

$html= '
<table class="table table-bordered table-striped" id="examplex" >
    <thead>
        <tr style="background-color:#ddd;color:#555">
            <th style="border-left:2px solid #ddd;">Nomor Pengajuan</th>
            <th>Atas Nama</th>
            <th>Untuk</th>
            <th>Komitmen</th>
            <th>Status</th>
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
    
    // Tambahkan id unik untuk setiap detail toggle
    $toggleId = 'detail-' . $key;
    $toggleHeadId = 'head-' . $key;
        
    $html.= '<tr onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer; background-color:#f9f9f9; border:2px solid #ddd" id="'.$toggleHeadId.'">'; // No Pengajuan
    $html.= '
        <td colspan="1" class="text-info" style="border-top:1px solid #fff;border-left:1px solid #fff;font-size:15px;font-weight:bold;"><strong>'.$posts[$key]['nomor_pengajuan'].'</strong></td>
        <td>'.$posts[$key]['kode_dpsj'].'</td>
        <td>'.$posts[$key]['uraian'].'</td>
        <td>'.number_format($posts[$key]['nominal_pengajuan']).'</td>
        <td colspan="1" class="text-danger text-center"><strong>'.nama_status($posts[$key]['kode_status']).'</strong></td>';
    $html.='</tr>';

    $html.= '
    <tr id="'.$toggleId.'" style="display:none;">
        <td colspan="5" style="text-align:center">
            <table id="tabel">
                <tr id="'.$toggleId.'" style="background-color:#f7f7f7;color:#777">
                    <th class="subhead">Kode Procost</th><th>Nama Procost</th><th>Kode Akun</th><th>Deskripsi Akun</th><th>Jumlah UMKO</th><th>Realisasi</th><th>Sisa UMKO</th><th colspan="2">Rincian Biaya</th>
                </tr>';

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
                    

                    $html.= '<tr>';
                    $html.= '<td>'.$row['kode_kegiatan'].'</td>';
                    $html.= '<td>'.$row['nama_kegiatan'].'</td>';
                    $html.= '<td>'.$row['kode_akun'].'</td>';
                    $html.= '<td>'.$row['deskripsi_akun'].'</td>';
                    $html.= '<td style="border-right:1px solid #ddd;">'.number_format($row['komitmen']).'</td>';
                    $html.= '<td style="border-right:1px solid #ddd;">'.number_format($array_realisasi[$row['id']]['total_bruto']).'</td>';
                    $html.= '<td style="border-right:1px solid #ddd;">'.number_format($row['komitmen'] - $array_realisasi[$row['id']]['total_bruto']).'</td>';

                    // tampilkan tombol create jika $array_realisasi[$row['id']]['total_netto'] = 0
                    if($array_realisasi[$row['id']]['total_netto'] == 0) {
                        $html.= '<td style="border-right:1px solid #ddd;"><button class="btn btn-primary btn-xs buat-realisasi" data-nomor_pengajuan="'.$key.'" data-id="'.$row['id'].'" data-nominal_pengajuan="'.$posts[$key]['nominal_pengajuan'].'" data-toggle="modal" data-target="#modal-realisasi">buat</button></td>';
                    } else {
                        $html.= '<td style="border-right:1px solid #ddd;"><button class="btn btn-success btn-xs edit-realisasi" data-nomor_pengajuan="'.$key.'" data-id="'.$row['id'].'" data-nominal_pengajuan="'.$posts[$key]['nominal_pengajuan'].'" data-toggle="modal" data-target="#modal-realisasi">edit</button></td>';
                    }

                    //$html.= '<td style="border-right:1px solid #ddd;"><button class="btn btn-primary btn-xs buat-realisasi" data-nomor_pengajuan="'.$key.'" data-id="'.$row['id'].'" data-toggle="modal" data-target="#modal-realisasi">create'.$row['id'].'</button></td>';

                    // tampilkan button view jika $array_realisasi[$row['id']]['total_netto'] > 0
                    if($array_realisasi[$row['id']]['total_netto'] > 0) {
                        $html.= '<td style="border-right:1px solid #ddd;"><button class="btn btn-warning btn-xs view-realisasi" data-nomor_pengajuan="'.$key.'" data-id="'.$row['id'].'" data-toggle="modal" data-target="#modal-realisasi">view</button></td>';
                    } else {
                        $html.= '<td style="border-right:1px solid #ddd;"><button class="btn btn-default btn-xs view-realisasi" disabled>view</button></td>';
                    }

                    //$html.= '<td style="border-right:1px solid #ddd;"><button class="btn btn-warning btn-xs view-realisasi" data-nomor_pengajuan="'.$key.'" data-id="'.$row['id'].'" data-toggle="modal" data-target="#modal-realisasi">view</button></td>';
                    $html.= '</tr>';
                    $n++;
                }
        
        $html.= 
            '</table>
        </td>
    </tr>';

    $html.= '<tr>'; // Separator row
    $html.= '<td colspan="5" style="border-left:1px solid #fff; border-right:1px solid #fff;border-top:1px solid #ddd; "></td>'; // Empty row for spacing
    $html.= '</tr>';
}

$html.= '</tbody></table>';
    echo $html;

/*-------------------------------------------------------------------------*/



echo $this->ajax_pagination_realisasi->create_links();
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