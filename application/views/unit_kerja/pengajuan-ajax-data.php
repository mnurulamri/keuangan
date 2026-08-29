<?php

if(!isset($posts) or empty($posts)){
    echo 'belum ada data pengajuan';
}

$array = array(); // inisialisasi array untuk menyimpan rincian

// set array status
//$array_nama_status = array(0=>'Belum Diajulan', 1=>'Menunggu Verifikasi Anggaran', 2=>'disetujui', 3=>'ditolak', 4=>'dibatalkan', 5=>'diterima', 6=>'selesai');
//echo '<pre>';print_r($sql);echo '</pre>';

$html= '
<table class="styled-table" id="examplex" width="100%" >
    <thead>
        <tr>
            <th style="border-left:2px solid #ddd;">Tgl Pengajuan</th>
            <th>Nomor Pengajuan</th>
            <th>Atas Nama</th>
            <th>Untuk</th>
            <th>Form</th>
            <th>Komitmen</th>
            <th>Nominal Cair</th>
            <th>Realisasi</th>
            <th>Status</th>
            <th>Catatan</th>
            <th width="250"></th>
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
        if($array_monitoring[$key] == 0) {
            $disabled = '';
            $disabled_delete = '';
            $text_ajukan = 'ajukan';
            $text_decoration = '';
        } else if($array_monitoring[$key] == 12 || $array_monitoring[$key] == 33 || $array_monitoring[$key] == 40 || $array_monitoring[$key] == 52 || $array_monitoring[$key] == 63 || $array_monitoring[$key] == 65) {
            $disabled = '';
            $disabled_delete = '';
            $text_ajukan = 'ajukan ulang';
            $text_decoration = '';
        } else if($array_monitoring[$key] == 14 || $array_monitoring[$key] == 43 || $array_monitoring[$key] == 53 || $array_monitoring[$key] == 64) {
            $disabled = 'disabled';            
            $disabled_delete = '';
            $text_ajukan = 'ajukan';
            $text_decoration = 'color: red;';
        } else {
            $disabled = 'disabled';            
            $text_ajukan = 'ajukan';
            $text_decoration = '';
            $disabled_delete = 'disabled';
        }
    } else {
        $status = 'Tidak Diketahui';
    }
    $status = nama_status($array_monitoring[$key]);

    // jika ada keterangan korpum_realisasi_keterangan_pending maka status = Retur
    /*if(isset($posts[$key]['korpum_realisasi_keterangan_pending'])){
        if($posts[$key]['kode_status']==63){
            $status = 'Retur oleh Kor PUM ';
        } else if($posts[$key]['kode_status']==13) {
            $status = 'Membuat Procost';
        } else {
            $status = 'Retur - Pengisian SPJ';
        }
    } else {

    }*/

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
                    <td conclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer; '.$text_decoration.'" id="'.$toggleHeadId.'">'.dbToTanggal($posts[$key]['tanggal']).'</td>
                    <td conclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer; '.$text_decoration.'" id="'.$toggleHeadId.'">'.$nomor_pengajuan.'</td>
                    <td onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer; '.$text_decoration.'" id="'.$toggleHeadId.'">'.$value['deskripsi_dpsj'].'</td>
                    <td onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer; '.$text_decoration.'" id="'.$toggleHeadId.'">'.$untuk.'</td>
                    <td onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer; '.$text_decoration.'" id="'.$toggleHeadId.'">'.$posts[$key]['form'].'</td>
                    <td class="text-right" onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer; '.$text_decoration.'" id="'.$toggleHeadId.'">'.number_format($nominal_pengajuan).'</td>
                    <td class="text-right" onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer; '.$text_decoration.'" id="'.$toggleHeadId.'">'.number_format($posts[$key]['nominal_umko_cair']).'</td>
                    <td class="text-right" onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer; '.$text_decoration.'" id="'.$toggleHeadId.'">'.number_format($posts[$key]['realisasi']).'</td>
                    <td onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer;" id="'.$toggleHeadId.'" class="status_'.$key.'">'.$status.'</td>
                    <td>
                        <!--<button class="btn btn-info btn-xs view-catatan" data-id="'.$row['id'].'" data-toggle="modal" data-target="#modal-catatan">View</button>-->
                        <button class="btn btn-info btn-xs fetch-logs" data-nomor_pengajuan="'.$nomor_pengajuan.'" data-toggle="modal" data-target="#modal-catatan">View</button>
                    </td>
                    <td class="button_'.$posts[$key]['id'].'">
                        <button class="btn btn-primary btn-xs ajukan" data-id_pengajuan_pemohon="'.$key.'" data-kode_dpsj="'.$row['kode_dpsj'].'" data-deskripsi_dpsj="'.$row['deskripsi_dpsj'].'" data-nama_form="'.$posts[$key]['form'].'" data-toggle="modal" data-target="#modal-ajukan" '.$disabled.' >'.$text_ajukan.'</button>
                        <button class="btn btn-success btn-xs edit" data-id_pengajuan_pemohon="'.$key.'" data-kode_dpsj="'.$row['kode_dpsj'].'" data-deskripsi_dpsj="'.$row['deskripsi_dpsj'].'" data-toggle="modal" data-target="#modal-ajukan" '.$disabled.'>edit</button>
                        <button class="btn btn-danger btn-xs delete" data-id_pengajuan_pemohon="'.$key.'" '.$disabled_delete.'>delete</button>
                        <button class="btn btn-default btn-xs cetak hovertext" data-hover="Cetak Pengajuan" data-id_pengajuan_pemohon="'.$key.'" data-nama_form="'.$posts[$key]['form'].'" ><i class="fa fa-file-word-o text-success" style="font-size:18px; color:blue;"></i></button>';
                if($posts[$key]['realisasi'] > 0) {
                    $html.= '        
                        <button class="btn btn-default btn-xs total-biaya-excel hovertext" data-hover="Rekap Total Biaya" data-id="'.$row['id'].'" data-id_pengajuan_pemohon="'.$key.'"><i class="fa fa-file-excel-o text-success" style="font-size:18px; color:green;"></i></button>       
                        <button class="btn btn-default btn-xs rekap-realisasi-excel hovertext-realisasi" data-hover="Rekap Realisasi" data-id="'.$row['id'].'" data-id_pengajuan_pemohon="'.$key.'"><i class="fa fa-file-excel-o text-success" style="font-size:18px; color:#C21E56;"></i></button>';
                }

                $html.= '        
                    </td>
                </tr>'; // Separator row

                $html.= '
                <tr id="'.$toggleId.'" style="display:none;">
                    <td colspan="11" class="text-center" style="border:1px solid #f9f9f9">
                        <table id="tabel" class="table table-bordered table-striped" border="1">
                            <tr style="background-color:#f7f7f7;color:#777">
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
                            </tr>';
            
                $n=1;  // $n untuk menunjukkan baris
                $nominal_pengajuan = 0; // inisialisasi nominal pengajuan
                $subtotal_realisasi = 0; // inisialisasi subtotal realisasi
                $subtotal_sisa = 0; // inisialisasi subtotal sisa
                foreach($rincian as $row){
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
                        $html.= '<td><button class="btn btn-default btn-xs rincian-biaya-excel hovertext" data-hover="Rincian Biaya" data-id="'.$row['id'].'" data-nomor_pengajuan="'.$row['nomor_pengajuan'].'"><i class="fa fa-file-excel-o text-success" style="font-size:16px;"></i></button></td>';
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
                $html.= '</table></td></tr>'; // end Separator row
                break; // keluar dari loop setelah menemukan id_pengajuan_pemohon yang sesuai
            }

        
    }

    // jika tidak ada rincian untuk id_pengajuan_pemohon, tampilkan pesan
    if(!isset($array_rincian[$key]) or empty($array_rincian[$key])) {
        // cek dulu apakah pengajuan ini sudah pernah di ajukan sebelumnya
        
        if(isset($key) and !empty($key)){
            $disabled = '';
            $html.= '
                <tr>
                    <td>'.dbToTanggal($posts[$key]['tanggal']).'</td>
                    <td>'.$nomor_pengajuan.'</td>
                    <td>'.$value['deskripsi_dpsj'].'</td>
                    <td>'.$untuk.'</td>
                    <td>'.$posts[$key]['form'].'</td>
                    <td class="text-right">0</td>
                    <td class="text-right">0</td>
                    <td class="text-right">0</td>
                    <td>'.$status.'</td>
                    <td></td>
                    <td>
                        <button class="btn btn-success btn-xs edit" data-id_pengajuan_pemohon="'.$key.'" data-toggle="modal" data-target="#modal-ajukan" '.$disabled.'>edit</button>
                        <button class="btn btn-danger btn-xs delete" data-id_pengajuan_pemohon="'.$key.'" '.$disabled.'>delete</button>
                    </td>
                </tr>';
        }else{
            $disabled = 'disabled';
            $html.= '<tr>';
            $html.= '<td colspan="7" class="text-center" style="border-left:1px solid #ddd; border-right:1px solid #ddd; color:#888">'.$key.' - Tidak ada rincian untuk pengajuan ini</td>';
            $html.= '<td>
                    <button class="btn btn-success btn-xs edit" data-id_pengajuan_pemohon="'.$key.'" data-toggle="modal" data-target="#modal-ajukan" '.$disabled.'>edit</button>
                    <button class="btn btn-danger btn-xs delete" data-id_pengajuan_pemohon="'.$key.'" '.$disabled.'>delete</button></td>';
            $html.= '</tr>';
            $html.= '<tr>'; // Separator row
            $html.= '<td colspan="9" style="border-left:1px solid #fff; border-right:1px solid #fff;border-top:2px solid #ddd; border-bottom:1px solid #fff"></td>'; // Empty row for spacing
            $html.= '</tr>';
        }

        /*$html.= '<tr>';
        $html.= '<td colspan="7" class="text-center" style="border-left:1px solid #ddd; border-right:1px solid #ddd; color:#888">Tidak ada rincian untuk pengajuan ini</td>';
        $html.= '<td>
                <button class="btn btn-success btn-xs edit" data-id_pengajuan_pemohon="'.$key.'" data-toggle="modal" data-target="#modal-ajukan" '.$disabled.'>edit</button>
                <button class="btn btn-danger btn-xs delete" data-id_pengajuan_pemohon="'.$key.'" '.$disabled.'>delete</button></td>';
        $html.= '</tr>';
        $html.= '</tr>';
        $html.= '<tr>'; // Separator row
        $html.= '<td colspan="9" style="border-left:1px solid #fff; border-right:1px solid #fff;border-top:2px solid #ddd; border-bottom:1px solid #fff"></td>'; // Empty row for spacing
        $html.= '</tr>';*/
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