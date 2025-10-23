<?php

if(!isset($posts) or empty($posts)){
    echo 'belum ada data pengajuan';
}

$array = array(); // inisialisasi array untuk menyimpan rincian

// set array status
//$array_nama_status = array(0=>'Belum Diajulan', 1=>'Menunggu Verifikasi Anggaran', 2=>'disetujui', 3=>'ditolak', 4=>'dibatalkan', 5=>'diterima', 6=>'selesai');


$html= '
<table class="styled-table" id="examplex"  width="100%">
    <thead>
        <tr>
            <th style="border-left:2px solid #ddd;">Nomor Pengajuan</th>
            <th>Atas Nama</th>
            <th>Untuk</th>
            <th>Komitmen</th>
            <th>Status</th>
            <th>Catatan</th>
            <th></th>
        </tr>
    <tbody>';

$barisKe = 1;
foreach($posts as $key => $value) {

    // jika $key nomor_pengajuan tidak ada di $posts, berarti belum ada $nomor_pengajuan
    if(!isset($posts[$key]['nomor_pengajuan']) or $posts[$key]['nomor_pengajuan'] == '') {
        $nomor_pengajuan = '-'; // set nomor pengajuan ke -
    }   
    else {
        $nomor_pengajuan = $posts[$key]['nomor_pengajuan']; // ambil nomor pengajuan dari posts
    }

    // jika $key untuk tidak ada di $posts, berarti belum ada $untuk
    if(!isset($posts[$key]['untuk']) or $posts[$key]['untuk'] == '') {
        $untuk = '-'; // set untuk sama dengan kosong
    }   
    else {
        $untuk = $posts[$key]['untuk']; // ambil untuk dari posts
    }

    // jika $array_monitoring[$key] tidak ada, berarti belum ada pengajuan
    if(!isset($array_monitoring[$key])) {
        $array_monitoring[$key] = 0; // set status ke 0 (belum diajukan)
    }

    // set nama status berdasarkan $array_nama_status
    if(nama_status($array_monitoring[$key])) {
        // jika ada status, ambil nama statusnya
        $status = nama_status($array_monitoring[$key]);

        // jika statusnya belum diajukan, set disabled
        if($array_monitoring[$key] === 0) {
            $disabled = '';
        } else {
            $disabled = 'disabled';
        }
    } else {
        $status = 'Tidak Diketahui';
    }
    $status = nama_status($array_monitoring[$key]);

    if(!isset($array_monitoring_keterangan[$key]) or empty($array_monitoring_keterangan[$key])) {
        
        $keterangan = '-';
    } else {
        $keterangan = $array_monitoring_keterangan[$key];
    }

    // masukkan array rincian ke dalam array daftar pengajuan berdasarkan id_pengajuan_pemohon
    foreach($array_rincian as $id_pengajuan_pemohon => $rincian) {

        // Tambahkan id unik untuk setiap detail toggle
        $toggleId = 'detail-' . $key;
        $toggleHeadId = 'head-' . $key;
        
            if($key == $id_pengajuan_pemohon) {

                // hitung nomial pengajuan
                $nominal_pengajuan = 0;
                foreach($rincian as $row){
                    $nominal_pengajuan += $row['komitmen']; // jumlahkan komitmen
                }
                // baris untuk nomor pengajuan
                $html.= '
                <tr>
                    <td onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer;" id="'.$toggleHeadId.'">'.$nomor_pengajuan.'</td>
                    <td onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer;" id="'.$toggleHeadId.'">'.$value['deskripsi_dpsj'].'</td>
                    <td onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer;" id="'.$toggleHeadId.'">'.$untuk.'</td>
                    <td onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer;" id="'.$toggleHeadId.'">'.number_format($nominal_pengajuan).'</td>
                    <td onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer;" id="'.$toggleHeadId.'">'.$status.'</td>
                    <td>
                        <button class="btn btn-info btn-xs view-catatan" data-id="'.$row['id'].'" data-toggle="modal" data-target="#modal-catatan">View</button>
                    </td>
                    <td>
                        <button class="btn btn-primary btn-xs ajukan" data-id_pengajuan_pemohon="'.$key.'" data-kode_dpsj="'.$row['kode_dpsj'].'" data-deskripsi_dpsj="'.$row['deskripsi_dpsj'].'" data-toggle="modal" data-target="#modal-ajukan" '.$disabled.' >ajukan</button>
                        <button class="btn btn-success btn-xs edit" data-id_pengajuan_pemohon="'.$key.'" data-kode_dpsj="'.$row['kode_dpsj'].'" data-deskripsi_dpsj="'.$row['deskripsi_dpsj'].'" data-toggle="modal" data-target="#modal-ajukan" '.$disabled.'>edit</button>
                        <button class="btn btn-danger btn-xs delete" data-id_pengajuan_pemohon="'.$key.'" '.$disabled.'>delete</button>
                    </td>
                </tr>'; // Separator row

                $html.= '
                <tr id="'.$toggleId.'" style="display:none;">
                    <td colspan="6" class="text-center" style="border:1px solid #f9f9f9">
                        <table id="tabel" class="table table-bordered table-striped" border="1">
                            <tr style="background-color:#f7f7f7;color:#777">
                                <th style="border-left:1px solid #ddd;">Kode Procost</th>
                                <th>Nama Procost</th>
                                <th>Kode Akun</th>
                                <th>Deskripsi Akun</th>
                                <th>Kode Dana</th>
                                <th>Keterangan</th>
                                <th style="border-right:1px solid #ddd;">Komitmen</th>
                            </tr>';
            
                $n=1;  // $n untuk menunjukkan baris
                $nominal_pengajuan = 0; // inisialisasi nominal pengajuan
                foreach($rincian as $row){
                    //echo '<pre>';
                    $html.= '<tr>';
                    $html.= '<td style="border-left:1px solid #ddd;">'.$row['kode_kegiatan'].'</td>';
                    $html.= '<td>'.$row['nama_kegiatan'].'</td>';
                    $html.= '<td>'.$row['kode_akun'].'</td>';
                    $html.= '<td>'.$row['deskripsi_akun'].'</td>';
                    $html.= '<td>'.$row['kode_dana'].'</td>';
                    $html.= '<td>'.$row['keterangan'].'</td>';
                    $html.= '<td style="border-right:1px solid #ddd;">'.number_format($row['komitmen']).'</td>';

                        // Check if this is the first row to add action buttons
                        if($n > 1){
                            $html.='';
                        } else {
                            $html.= '';	
                        }

                    $html.= '</tr>';
                    //echo '</pre>';
                    $n++;
                    $nominal_pengajuan += $row['komitmen']; // jumlahkan komitmen
                }

                // baris total
                $html.= '
                <tr>
                    <td colspan="5" class="text-right" style="border-left:1px solid #ddd; color:#888"><b>Total: </b></td>
                    <td style="border-left:1px solid #fff; border-bottom:1px solid #fff">'.number_format($nominal_pengajuan).'</td>
                    <td style="border-right:1px solid #ddd; border-bottom:1px solid #fff"></td>
                </tr>
                <tr>
                    <td colspan="9" style="border-left:1px solid #fff; border-right:1px solid #fff;border-top:2px solid #ddd; border-bottom:1px solid #fff"></td>
                </tr>
                </table></td></tr>'; // end Separator row
                break; // keluar dari loop setelah menemukan id_pengajuan_pemohon yang sesuai
            }

        
    }

    // jika tidak ada rincian untuk id_pengajuan_pemohon, tampilkan pesan
    if(!isset($array_rincian[$key]) or empty($array_rincian[$key])) {
        $html.= '<tr>';
        $html.= '<td colspan="7" class="text-center" style="border-left:1px solid #ddd; border-right:1px solid #ddd; color:#888">Tidak ada rincian untuk pengajuan ini</td>';
        $html.= '<td>
                <button class="btn btn-success btn-xs edit" data-id_pengajuan_pemohon="'.$key.'" data-toggle="modal" data-target="#modal-ajukan" '.$disabled.'>edit</button>
                <button class="btn btn-danger btn-xs delete" data-id_pengajuan_pemohon="'.$key.'" '.$disabled.'>delete</button></td>';
        $html.= '</tr>';
        $html.= '</tr>';
        $html.= '<tr>'; // Separator row
        $html.= '<td colspan="9" style="border-left:1px solid #fff; border-right:1px solid #fff;border-top:2px solid #ddd; border-bottom:1px solid #fff"></td>'; // Empty row for spacing
        $html.= '</tr>';
    }
}


$html.= '</tbody></table>';
echo $html;
echo $this->ajax_pagination_pengajuan->create_links();

?>

<script>
function toggleDetail(id, headId, barisKe) {
    
    var el = document.getElementById(id);
    var headRow = document.getElementById(headId);

    if (el.style.display === "none") {
        fadeIn(el, 500);      
        headRow.style.color = "#018369ff"; // biru
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
