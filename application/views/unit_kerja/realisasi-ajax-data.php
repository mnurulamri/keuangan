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
            <th>Nominal Cair</th>
            <th>Realisasi</th>
            <th>Sisa UMKO</th>
            <th>Status</th>
            <th colspan="1" width="185px"></th>
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
    // set nominal pengajuan
    $nominal_pengajuan = 0;
    foreach($value as $row){
        $nominal_pengajuan += $row['komitmen'];
    }
    
    // Tambahkan id unik untuk setiap detail toggle
    $toggleId = 'detail-' . $key;
    $toggleHeadId = 'head-' . $key;
        
	// set status
	if(($posts[$key]['kode_status'] == 41 or $posts[$key]['kode_status']==42 or $posts[$key]['kode_status']==52 or $posts[$key]['kode_status']==67) and isset($posts[$key]['korpum_realisasi_keterangan_pending'])){
		$status = '<b style="color:red">Retur - Pengisian SPJ</b>';
		$aksi_spj = 'Edit SPJ';
	} else {
		$status = nama_status($posts[$key]['kode_status']);
		$aksi_spj = 'Buat SPJ';
	}
    $html.= '<tr style="background-color:#fff; border:1px solid #ddd" id="'.$toggleHeadId.'">'; // No Pengajuan
    $html.= '
        <td colspan="1" class="text-info" style="border-top:1px solid #fff;border-left:1px solid #fff;font-size:15px;font-weight:bold;"><strong>'.$posts[$key]['nomor_pengajuan'].'</strong></td>
        <td>'.$posts[$key]['kode_dpsj'].'</td>
        <td>'.$posts[$key]['uraian'].'</td>
        <td>'.number_format($nominal_pengajuan).'</td>
        <td>'.number_format($posts[$key]['nominal_umko_cair']).'</td>
        <td>'.number_format($array_realisasi_total[$key]).'</td>
        <td>'.number_format($array_sisa_umko_total[$key]).'</td>
        <td colspan="1" class="text-danger text-center"><strong id="status_'.$posts[$key]['id_pengajuan_pemohon'].'">'.$status.'</strong></td>
        <td>';
        //jika array_realisasi_total lebih dari 0, tampilkan tombol ajukan
        //if($array_realisasi_total[$key] > 0 or $posts[$key]['kode_status']==51){
		if($posts[$key]['kode_status']==41 or $posts[$key]['kode_status']==42 or $posts[$key]['kode_status']==52 or $posts[$key]['kode_status']==67){
            $html.= '
            <button id="button-ajukan-'.$posts[$key]['id_pengajuan_pemohon'].'"  class="btn btn-primary btn-xs ajukan-realisasi" data-id_pengajuan_pemohon="'.$posts[$key]['id_pengajuan_pemohon'].'" data-toggle="modal" data-target="#modal-realisasi">Ajukan</button>          
			<button id="button-spj-'.$posts[$key]['id_pengajuan_pemohon'].'" onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" class="btn btn-success btn-xs" data-nomor_pengajuan="'.$posts[$key]['kode_status'].'" data-id="'.$posts[$key]['kode_status'].'">'.$aksi_spj.'</button>';
            
        } else {
            $html.= '
            <button class="btn btn-primary btn-xs ajukan-realisasi" disabled>ajukan</button>
            <button onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" class="btn btn-warning btn-xs" data-nomor_pengajuan="'.$posts[$key]['kode_status'].'" data-id="'.$posts[$key]['kode_status'].'" >Lihat SPJ</button>';

        }

        // jika realisasi lebih besar dari 0 maka tampilkan tombol cetak total biaya
        if($array_realisasi_total[$key] > 0){
            $html.= '
            <button class="btn btn-default btn-xs total-biaya-excel hovertext" data-hover="Rekap Total Biaya" data-id_pengajuan_pemohon="'.$posts[$key]['id_pengajuan_pemohon'].'"><i class="fa fa-file-excel-o text-success" style="font-size:18px; color:green;"></i></button>
            <button class="btn btn-default btn-xs rekap-realisasi-excel hovertext-realisasi" data-hover="Rekap Realisasi" data-id_pengajuan_pemohon="'.$posts[$key]['id_pengajuan_pemohon'].'"><i class="fa fa-file-excel-o text-success" style="font-size:18px; color:#C21E56;"></i></button>';
        }

    /*$html.= '
            <button onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" class="btn btn-success btn-xs" data-nomor_pengajuan="'.$posts[$key]['kode_status'].'" data-id="'.$posts[$key]['kode_status'].'">buat SPJ</button>
        </td>
    </tr>';*/
	$html.= '</td></tr>';
    $html.= '
    <tr id="'.$toggleId.'" style="display:none;">
        <td colspan="9" style="text-align:center">
            <table id="tabel">
                <tr id="'.$toggleId.'" style="background-color:#f7f7f7;color:#777">
                    <th class="subhead">Kode Procost</th><th>Nama Procost</th><th>Kode Akun</th><th>Deskripsi Akun</th><th>Jumlah UMKO</th><th>Realisasi</th><th>Sisa UMKO</th><th colspan="1">Rincian Biaya</th>
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
                    

                    $html.= '<tr>';
                    $html.= '<td>'.$row['kode_kegiatan'].'</td>';
                    $html.= '<td class="text-left">'.$row['nama_kegiatan'].'</td>';
                    $html.= '<td class="text-center">'.$row['kode_akun'].'</td>';
                    $html.= '<td class="text-left">'.$row['deskripsi_akun'].'</td>';
                    $html.= '<td style="border-right:1px solid #ddd; text-right">'.number_format($row['komitmen']).'</td>';
                    $html.= '<td style="border-right:1px solid #ddd; text-right">'.number_format($array_realisasi[$row['id']]['total_bruto']).'</td>';
                    $html.= '<td style="border-right:1px solid #ddd; text-right">'.number_format($row['komitmen'] - $array_realisasi[$row['id']]['total_bruto']).'</td>';

                    // tampilkan tombol create jika $array_realisasi[$row['id']]['total_netto'] = 0
                    if($array_realisasi[$row['id']]['total_netto'] == 0) {
						if($posts[$key]['kode_status']==41 or $posts[$key]['kode_status']==42 or $posts[$key]['kode_status']==52){
                            if($this->session->userdata('logged_anggaran')['username'] == 'user' || $this->session->userdata('logged_anggaran')['username'] == 'adeseptian'){
                                $html.= '<td colspan="1" style="border-right:1px solid #ddd;"><button class="btn btn-primary btn-xs buat-realisasi" data-nomor_pengajuan="'.$key.'" data-id="'.$row['id'].'" data-nominal_pengajuan="'.$posts[$key]['nominal_pengajuan'].'" data-keterangan="'.$row['keterangan'].'" data-toggle="modal" data-target="#modal-realisasi">Buat</button></td>';
                            } else {
                                $html.= '<td colspan="1" style="border-right:1px solid #ddd;"><button class="btn btn-success btn-xs edit-spj" data-nomor_pengajuan="'.$key.'" data-id="'.$row['id'].'" data-nominal_pengajuan="'.$posts[$key]['nominal_pengajuan'].'" data-toggle="modal" data-target="#modal-realisasi">Update</button></td>';
                            }
						} else {
							$html.= '<td colspan="1" style="border-right:1px solid #ddd;"><button class="btn btn-warning btn-xs view-realisasi" data-nomor_pengajuan="'.$key.'" data-id="'.$row['id'].'" data-toggle="modal" data-target="#modal-realisasi">view</button></td>';
						}
                    } else {
						if($posts[$key]['kode_status']==41 or $posts[$key]['kode_status']==42 or $posts[$key]['kode_status']==52){
                            if($this->session->userdata('logged_anggaran')['username'] == 'user' || $this->session->userdata('logged_anggaran')['username'] == 'adeseptian'){
                                $html.= '<td style="border-right:1px solid #ddd;"><button class="btn btn-success btn-xs edit-realisasi" data-nomor_pengajuan="'.$key.'" data-id="'.$row['id'].'" data-nominal_pengajuan="'.$posts[$key]['nominal_pengajuan'].'" data-toggle="modal" data-target="#modal-realisasi">Edit</button></td>';
                            } else {
                                $html.= '<td style="border-right:1px solid #ddd;"><button class="btn btn-success btn-xs edit-spj" data-nomor_pengajuan="'.$key.'" data-id="'.$row['id'].'" data-nominal_pengajuan="'.$posts[$key]['nominal_pengajuan'].'" data-toggle="modal" data-target="#modal-realisasi">Update</button></td>';
                            }                        	
                        	
						} else {
							$html.= '<td style="border-right:1px solid #ddd;"><button class="btn btn-warning btn-xs view-realisasi" data-nomor_pengajuan="'.$key.'" data-id="'.$row['id'].'" data-toggle="modal" data-target="#modal-realisasi">view</button></td>';
						}
                        $html.= '<td style="border-right:1px solid #ddd;"><button class="btn btn-default btn-xs rincian-biaya-excel hovertext" data-hover="Rekap Jenis Biaya" data-id="'.$row['id'].'" data-nomor-pengajuan="'.$key.'" data-id_pengajuan_pemohon="'.$posts[$key]['id_pengajuan_pemohon'].'"><i class="fa fa-file-excel-o text-default" style="font-size:18px;color:green;"></i> </button></td>';
                    }

                    //$html.= '<td style="border-right:1px solid #ddd;"><button class="btn btn-primary btn-xs buat-realisasi" data-nomor_pengajuan="'.$key.'" data-id="'.$row['id'].'" data-toggle="modal" data-target="#modal-realisasi">create'.$row['id'].'</button></td>';

                    // tampilkan button view jika $array_realisasi[$row['id']]['total_netto'] > 0
                    if($array_realisasi[$row['id']]['total_netto'] > 0) {
                        //$html.= '<td style="border-right:1px solid #ddd;"><button class="btn btn-warning btn-xs view-realisasi" data-nomor_pengajuan="'.$key.'" data-id="'.$row['id'].'" data-toggle="modal" data-target="#modal-realisasi">view</button></td>';
                    } else {
                        //$html.= '<td style="border-right:1px solid #ddd;"><button class="btn btn-default btn-xs view-realisasi" disabled>view</button></td>';
                    }

                    //$html.= '<td style="border-right:1px solid #ddd;"><button class="btn btn-warning btn-xs view-realisasi" data-nomor_pengajuan="'.$key.'" data-id="'.$row['id'].'" data-toggle="modal" data-target="#modal-realisasi">view</button></td>';
                    $html.= '</tr>';
                    $n++;

                    $total_komitmen += $row['komitmen'];
                    $total_realisasi += $array_realisasi[$row['id']]['total_bruto'];
                }
        

            $html.= '<tr style="background-color:#eee;font-weight:bold; border-top:2px solid #ddd;">
                <td colspan="4" style="text-align:right; border-right:2px solid #ddd;">TOTAL</td>
                <td style="border-right:1px solid #ddd; text-right">'.number_format($total_komitmen).'</td>
                <td style="border-right:1px solid #ddd; text-right">'.number_format($total_realisasi).'</td>
                <td style="border-right:1px solid #ddd; text-right">'.number_format($total_komitmen - $total_realisasi).'</td>
                <td colspan="2" style="border-right:1px solid #ddd;"></td>
            </tr>';

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
        el.style.border = "1px solid #ddd";
    } else {
        fadeOut(el, 500);      
        headRow.style.color = "#444"; // default 
        headRow.style.fontWeight = "normal";
        headRow.style.borderBottom = "1px solid #ddd";
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