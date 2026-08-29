<?php
//echo '<pre>';
//print_r($posts);
//print_r($array_value_kode_grup);
//print_r($array_rincian);
//print_r($sql);
/*foreach($posts as $key => $value) {
    print_r($value);
}*/
//echo '</pre>';

$totalMutasiNegatif = 0;
$totalMutasiPositif = 0;
$html='
<div class="table-container" id="tableContainer">
<table class="styled-table" id="examplex" width="100%">    
    <thead>
        <tr>
            <th>NOMOR PENGAJUAN</th>
            <th>KODE DPSJ</th>
            <th>DESKRIPSI DPSJ</th>
            <th>TGL PENGAJUAN</th>
            <th>MUTASI (-)</th>
            <th>MUTASI (+)</th>
            <th>STATUS</th>
            <th></th>
        </tr>
    </thead>
    <tbody>';
        $barisKe = 1;
        foreach($posts as $key => $value) {
            // set disabled button
            if($value['kode_status']==0){
                $disabled = '';
            } else if($value['kode_status']==1){
                // jika sudah diajiukan, maka tidak bisa ajukan edit delete
                $disabled = 'disabled';
            } else if($value['kode_status']==2){
                // jika sudah disetujui, maka tidak bisa ajukan edit delete
                $disabled = 'disabled';
            } else if($value['kode_status']==3){
                // jika sudah dikembalikan, maka bisa ajukan edit delete
                $disabled = '';
            } else if($value['kode_status']==4){
                // jika sudah dibatalkan, maka tidak bisa ajukan edit delete
                $disabled = 'disabled';
            } else {
                $disabled = 'disabled';
            }

			if($value['tgl_pengajuan'] == '0000-00-00'){
				$value['tgl_pengajuan'] = '';
			} 
            
            // hitung total mutasi negatif dan positif 
            $total_mutasi_negatif = 0;
            $total_mutasi_positif = 0;
            foreach($array_rincian[$key] as $row){
                if($row['mutasi'] < 0){
                    $total_mutasi_negatif += $row['mutasi'];
                    $totalMutasiNegatif += $row['mutasi'];
                } else {
                    $total_mutasi_positif += $row['mutasi'];
                    $totalMutasiPositif += $row['mutasi'];
                }
            }

            // Tambahkan id unik untuk setiap detail toggle
            $toggleId = 'detail-' . $key;
            $toggleHeadId = 'head-' . $key;
            
            $html.= '
            <tr id="'.$toggleHeadId.'">
                <td onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer;" id="'.$toggleHeadId.'">'.$value['nomor_pengajuan'].'</td>
                <td onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer;" id="'.$toggleHeadId.'">'.$value['kode_dpsj'].'</td>
                <td onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer;" id="'.$toggleHeadId.'">'.$value['deskripsi_dpsj'].'</td>
                <td onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer;" id="'.$toggleHeadId.'">'.$value['tgl_pengajuan'].'</td>
                <td onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer;" id="'.$toggleHeadId.'" class="text-right">'.number_format($total_mutasi_negatif).'</td>
                <td onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer;" id="'.$toggleHeadId.'" class="text-right">'.number_format($total_mutasi_positif).'</td>
                <td onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer;" id="'.$toggleHeadId.'">'.nama_status_mutasi($value['kode_status']).'</td>
                <td>
                    <button class="btn btn-primary btn-xs ajukan" data-kode_grup="'.$value['kode_grup'].'" data-kode_dpsj="'.$value['kode_dpsj'].'" data-id_pengajuan_pemohon="'.$value['id_pengajuan_pemohon'].'" data-tahun="'.$value['tahun'].'" data-bulan="'.$value['bulan'].'" data-kode_unit="'.$value['kode_unit'].'" data-toggle="modal" data-target="#modal-ajukan" '.$disabled.' >ajukan</button>
                    <button class="btn btn-success btn-xs edit" data-kode_grup="'.$value['kode_grup'].'" data-kode_dpsj="'.$value['kode_dpsj'].'" data-id_pengajuan_pemohon="'.$value['id_pengajuan_pemohon'].'" data-toggle="modal" data-target="#modal-ajukan" '.$disabled.'>edit</button>
                    <button class="btn btn-danger btn-xs delete" data-kode_grup="'.$value['kode_grup'].'" data-kode_dpsj="'.$value['kode_dpsj'].'" data-id_pengajuan_pemohon="'.$value['id_pengajuan_pemohon'].'" '.$disabled.'>delete</button>
                    <button class="btn btn-warning btn-xs cetak" data-kode_grup="'.$value['kode_grup'].'" data-kode_dpsj="'.$value['kode_dpsj'].'" data-id_pengajuan_pemohon="'.$value['id_pengajuan_pemohon'].'" >cetak</button>
                </td>
            </tr>
            <tr id="'.$toggleId.'" style="display:none;">
                <td colspan="8">
                    <table id="tabel" class="table table-bordered table-striped" border="1">
                        <tr>
                            <th>KODE DPSJ</th>
                            <th>KODE KEGIATAN</th>
                            <th>NAMA KEGIATAN</th>
                            <th>KODE AKUN</th>
                            <th>DESKRIPSI AKUN</th>
                            <th>KODE DANA</th>
                            <th>MUTASI (-)</th>
                            <th>MUTASI (+)</th>
                        </tr>
                    ';
                    $total_mutasi = 0;
                    $total_mutasi_negatif = 0;
                    $total_mutasi_positif = 0;
                    foreach($array_rincian[$key] as $row){
                        $html.= '
                        <tr>
                            <td class="text-center">'.$row['kode_dpsj_rka'].'</td>
                            <td class="text-center">'.$row['kode_kegiatan'].'</td>
                            <td>'.$row['nama_kegiatan'].'</td>
                            <td class="text-center">'.$row['kode_akun'].'</td>
                            <td>'.$row['deskripsi_akun'].'</td>
                            <td class="text-center">'.$row['kode_dana'].'</td>';

                            if($row['mutasi'] < 0){
                                $mutasi_negatif = $row['mutasi'];
                                $html.= '<td class="text-right">'.number_format($row['mutasi']).'</td>';
                            } else {
                                $mutasi_negatif = 0;
                                $html.= '<td class="text-right">0</td>';
                            }
                            if($row['mutasi'] > 0){
                                $mutasi_positif = $row['mutasi'];
                                $html.= '<td class="text-right">'.number_format($row['mutasi']).'</td>';
                            } else {
                                $mutasi_positif = 0;
                                $html.= '<td class="text-right">0</td>';
                            }
                            $total_mutasi_negatif += $mutasi_negatif;
                            $total_mutasi_positif += $mutasi_positif;

                        $html.= '</tr>';
                        $total_mutasi += $row['mutasi'];            
                    }
                    $html.= '
                        <tfoot>
                            <tr>
                                <td class="text-right" colspan="6">Total</td>
                                <td class="text-right" id="total-mutasi-negatif">'.number_format($total_mutasi_negatif).'</td>
                                <td class="text-right" id="total-mutasi-positif">'.number_format($total_mutasi_positif).'</td>
                            </tr>
                        </tfoot>
                        </tbody>
                    </table>
                </td>
            </tr>';
        }
        
    $html.= '
    </tbody>
    <tfoot>
        <tr>
            <td class="text-right" colspan="4">Total</td>
            <td class="text-right" id="total-mutasi-negatif">'.number_format($totalMutasiNegatif).'</td>
            <td class="text-right" id="total-mutasi-positif">'.number_format($totalMutasiPositif).'</td>
        </tr>
    </tfoot>
</table>
</div>';
echo $html;

//echo $this->ajax_pagination_mutasi->create_links();
?>


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
/*
var input_nomor_pengajuan = document.getElementById("nomor_pengajuan-search");
var table = document.getElementById("examplex");
var totalMutasiNegatifElement = document.getElementById("total-mutasi-negatif");
var totalMutasiPositifElement = document.getElementById("total-mutasi-positif");

search();

function search() {
    
    let filter_nomor_pengajuan = input_nomor_pengajuan.value.toUpperCase();
    let tr = table.rows;
    
    let totalMutasiNegatif = 0;
    let totalMutasiPositif = 0;
    
    for (let i = 0; i < tr.length; i++) {
        // Lewati baris header (baris 0 dan 1) dan baris footer
        if (i < 1 || tr[i].parentNode.nodeName === 'TFOOT') continue;
        
        let td = tr[i].cells;
        let td_nomor_pengajuan = td[0].innerText;
        
        if (td_nomor_pengajuan.toUpperCase().indexOf(filter_nomor_pengajuan) > -1) {
            
            tr[i].style.display = "";
            
            // Hanya tambahkan total jika baris ditampilkan
            // Ambil nilai dari kolom MUTASI NEGATIF dan MUTASI POSITIF
            let mutasiNegatifText = td[8].innerText.replace(/,/g, ''); // Hapus koma dari format angka
            let mutasiPositifText = td[9].innerText.replace(/,/g, '');
            
            // Konversi ke number dan tambahkan ke total
            totalMutasiNegatif += parseFloat(mutasiNegatifText) || 0;
            totalMutasiPositif += parseFloat(mutasiPositifText) || 0;
            
        } else {
            tr[i].style.display = "none";
        }
    }
    
    // Update tampilan total dengan format angka
    totalMutasiNegatifElement.textContent = formatNumber(totalMutasiNegatif);
    totalMutasiPositifElement.textContent = formatNumber(totalMutasiPositif);
}

// Fungsi untuk memformat angka dengan pemisah ribuan
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Tunggu sampai DOM sepenuhnya dimuat
document.addEventListener('DOMContentLoaded', function() {
    search();
});
*/
</script>