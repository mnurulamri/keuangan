<?php
//echo '<pre style="background-color:#fff;">';
//print_r($sql); 
//print_r($posts); 
//print_r($array_rincian);
//$nama_status = nama_status(11); print_r($nama_status); exit();

if(!isset($posts) or empty($posts)){
    echo 'belum ada data pengajuan';
}

$array = array(); // inisialisasi array untuk menyimpan rincian

// set array status
//$array_nama_status = array(0=>'Belum Diajulan', 1=>'Menunggu Verifikasi Anggaran', 2=>'disetujui', 3=>'ditolak', 4=>'dibatalkan', 5=>'diterima', 6=>'selesai');


$html= '<table class="table table-bordered table-stripedx" id="examplex">';
$html.= '<tbody>';

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

    $html.= '<tr>'; // No Pengajuan
    $html.= '
        <td colspan="5" class="text-info" style="border-top:1px solid:#fff; border-bottom:1px solid #ddd;border-left:1px solid #fff;border-right:1px solid #fff;" >
            <i><strong>Nomor Pengajuan: '.$nomor_pengajuan.'</strong></i>&nbsp;&nbsp;
            <!--<strong class="text-warning"><i>Status: '.$status.'</i></strong>-->
            <p class="text-muted" style="font-size:12px;margin:0px;padding:0px;">
                <i>Pengajuan dibuat pada: '.date('d-m-Y H:i:s', strtotime($value['created_at'])).'</i>
            </p>
            <p class="text-muted" style="font-size:12px;margin:0px;padding:0px;">
                <i>Atas Nama: '.$value['deskripsi_dpsj'].'</i>
            </p>
        </td>
        <td colspan="4" class="text-info text-center" style="border-top:1px solid:#fff; border-bottom:1px solid #ddd;border-left:1px solid #fff;border-right:1px solid #fff;">            
            <!--<div class="text-warning"><i>Keterangan: '.$keterangan.'</i></div>-->
            <strong class="text-warning"><i>Status: '.$status.'</i></strong>
        </td>';
    $html.='</tr>';
	$html.= '<tr><td colspan="9" class="text-info" style="border-top:1px solid:#fff; border-bottom:1px solid #ddd;border-left:1px solid #ddd;border-right:1px solid #ddd;" >Untuk: '.$untuk.'</td></tr>';
    $html.= '<tr style="background-color:#f7f7f7;color:#777">';
    $html.= '<th style="border-left:1px solid #ddd;">Kode Procost</th>';
    $html.= '<th>Nama Procost</th>';
    $html.= '<th>Kode Akun</th>';
    $html.= '<th>Deskripsi Akun</th>';
    $html.= '<th>Kode Dana</th>';
    $html.= '<th>Keterangan</th>';
    $html.= '<th>Komitmen</th>';
	$html.= '<th style="border-right:1px solid #ddd;"></th>';
    $html.= '</tr>';
    // masukkan array rincian ke dalam array daftar pengajuan berdasarkan id_pengajuan_pemohon

    foreach($array_rincian as $id_pengajuan_pemohon => $rincian) {
        if($key == $id_pengajuan_pemohon) {
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
                        $html.= '
                        <td rowspan="'.count($array_rincian[$key]).'" style="border-right:1px solid #ddd;">
                            <button class="btn btn-primary btn-xs ajukan" data-id_pengajuan_pemohon="'.$key.'" data-kode_dpsj="'.$row['kode_dpsj'].'" data-deskripsi_dpsj="'.$row['deskripsi_dpsj'].'" data-toggle="modal" data-target="#modal-ajukan" '.$disabled.' >ajukan</button>
                            <button class="btn btn-success btn-xs edit" data-id_pengajuan_pemohon="'.$key.'" data-kode_dpsj="'.$row['kode_dpsj'].'" data-deskripsi_dpsj="'.$row['deskripsi_dpsj'].'" data-toggle="modal" data-target="#modal-ajukan" '.$disabled.'>edit</button>
                            <button class="btn btn-danger btn-xs delete" data-id_pengajuan_pemohon="'.$key.'" '.$disabled.'>delete</button>
                        </td>';	
                    }

                $html.= '</tr>';
                //echo '</pre>';
                $n++;
                $nominal_pengajuan += $row['komitmen']; // jumlahkan komitmen
            }

            // baris total
            $html.= '<tr>'; // Separator row
            $html.= '<td colspan="6" class="text-right" style="border-left:1px solid #ddd; color:#888"><b>Total: </b></td>'; // Empty row for spacing
            $html.= '<td style="border-left:1px solid #fff; border-bottom:1px solid #fff">'.number_format($nominal_pengajuan).'</td>'; // Empty row for spacing
            $html.= '<td style="border-right:1px solid #ddd; border-bottom:1px solid #fff"></td>'; // Empty row for spacing
            $html.= '</tr>';
            $html.= '<tr>'; // Separator row
            $html.= '<td colspan="9" style="border-left:1px solid #fff; border-right:1px solid #fff;border-top:2px solid #ddd; border-bottom:1px solid #fff"></td>'; // Empty row for spacing
            $html.= '</tr>';
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