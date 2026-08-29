<?php
//echo '<pre>';
//print_r($posts);
//print_r($array_value_kode_grup);
//print_r($array_rincian);
//print_r($sql);
//foreach($posts as $key => $value) {
    //print_r($value);
//}
//echo '</pre>';

$html='
<div class="table-container" id="tableContainer">
<table class="styled-table" id="examplex" width="100%">    
    <thead>
        <tr class="header">
            <th>NOMOR PENGAJUAN</th>
            <th>DPSJ PENGAJU</th>
            <th>KODE DPSJ</th>
            <th>KODE KEGIATAN</th>
            <th>NAMA KEGIATAN</th>
            <th>KODE AKUN</th>
            <th>DESKRIPSI AKUN</th>
            <th>KODE DANA</th>
            <th>MUTASI (-)</th>
            <th>MUTASI (+)</th>
        </tr>
    </thead>
    <tbody>';
    $total_mutasi = 0;
    $total_mutasi_negatif = 0;
    $total_mutasi_positif = 0;
    foreach($array_rincian as $row){
        //$sisa_anggaran = $row['anggaran'] + $row['mutasi'];
        $html.= '
        <tr class="item">
            <td class="text-center">'.$row['nomor_pengajuan'].'</td>
            <td class="text-center">'.$row['deskripsi_dpsj'].'</td>
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
            <td class="text-right" colspan="8">Total</td>
            <td class="text-right" id="total-mutasi-negatif">'.number_format($total_mutasi_negatif).'</td>
            <td class="text-right" id="total-mutasi-positif">'.number_format($total_mutasi_positif).'</td>
        </tr>
    </tfoot>
    </tbody>
</table>
</div>';
echo $html;
?>

<script>
var input_nomor_pengajuan = document.getElementById("nomor_pengajuan-search");
var input_dpsj_pengaju = document.getElementById("dpsj_pengaju-search");
var table = document.getElementById("examplex");
var totalMutasiNegatifElement = document.getElementById("total-mutasi-negatif");
var totalMutasiPositifElement = document.getElementById("total-mutasi-positif");

search();

function search() {
    
    let filter_nomor_pengajuan = input_nomor_pengajuan.value.toUpperCase();
    let filter_dpsj_pengaju = input_dpsj_pengaju.value.toUpperCase();
    let tr = table.rows;
    
    let totalMutasiNegatif = 0;
    let totalMutasiPositif = 0;
    
    for (let i = 0; i < tr.length; i++) {
        // Lewati baris header (baris 0 dan 1) dan baris footer
        if (i < 1 || tr[i].parentNode.nodeName === 'TFOOT') continue;
        
        let td = tr[i].cells;
        let td_nomor_pengajuan = td[0].innerText;
        let td_dpsj_pengaju = td[1].innerText;
        
        if (td_nomor_pengajuan.toUpperCase().indexOf(filter_nomor_pengajuan) > -1 && 
            td_dpsj_pengaju.toUpperCase().indexOf(filter_dpsj_pengaju) > -1) {
            
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
</script>