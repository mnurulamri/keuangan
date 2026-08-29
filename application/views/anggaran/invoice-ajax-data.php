<?php

//$periode = array();
$periode = array();
foreach($result as $row){
    $periode[$row['tahun']][$row['bulan']][$row['tgl']][$row['no_tiket']][] = $row;
}

foreach($array_segmen as $row){
	$segmen[$row['kode_dpsj']] = $row['segmen'];
}
//$periode = array();
foreach($periode as $thn => $bulan_data){
    foreach($bulan_data as $bln => $tgl_data){     
        foreach($tgl_data as $tgl => $no_tiket_data){
            foreach($no_tiket_data as $no_tiket => $data_row){
                foreach($data_row as $row){
                    //hanya tampilkan yang ada nilai brutonya
                    if($row['aktual'] > 0){
                        $array_periode[$thn][$bln][$tgl][$no_tiket][] = $row;
                    }
                }
            }
        }
    }
}
//echo '<pre>';print_r($periode);echo '</pre>';
?>

<?php include(APPPATH.'views/modal.php') ?>
<table class="styled-table" id="invoice-table" width="100%">
    <thead>
        <tr style="background-color:#43A5BE; color:#fff">
            <th width="2px">No</th>  
            <th>PERIODE</th>  
            <th>NO INVOICE PP</th>
            <th>URAIAN</th>
            <th>NOMOR PENGAJUAN</th>
            <th>PROCOST</th>
            <th>AKUN</th>
            <th>KODE DANA</th>
            <th>SEGMEN</th>
            <th>BRUTO</th>
            <th>PAJAK</th>
			<th>NETTO</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php
    if(isset($array_periode)){

        foreach($array_periode as $thn => $bulan_data){
            foreach($bulan_data as $bln => $tgl_data){     
                foreach($tgl_data as $tgl => $no_tiket_data){
					$no = 1;
                    foreach($no_tiket_data as $no_tiket => $data_row){
                        $total_aktual = 0;
                        $total_pph = 0;
                        $total_netto = 0;
						$j=1;
                        foreach($data_row as $row){
                            //hanya tampilkan yang ada nilai brutonya
                            if($row['aktual'] > 0){
                                
    							if($j > 1){
    								$style = 'style="color:#fff; border-bottom:1px solid #fff;"';
                                    $style_invoice = 'style="color:#fff; border-bottom:1px solid #fff;"';
    								$button_edit = '';
    								$button_delete = '';
    								$button_send = '';
    								$button_tambah ='';
                                    $row_nomor_pengajuan = '';
                                    $row_no_invoice_pp = '';
                                    $row_uraian = '';
                                    $row_no = '';
                                    $row_tanggal = '';
    							} else {
    								$style = 'style="color:#444; border-bottom:1px solid #fff;"';
                                    $style_invoice = 'style="color:#367fa9; font-weight:bold; border-bottom:1px solid #fff;"';
    								$button_edit = '<button class="btn btn-success btn-xs edit" data-no_tiket="'.$no_tiket.'" data-toggle="modal" data-target="#modal-invoice">Edit</button>';
    								$button_delete = '<button class="btn btn-danger btn-xs delete" data-no_tiket="'.$no_tiket.'">Delete</button>';
    								$button_send = '<button class="btn btn-primary btn-xs send_to_akuntan_konfirmasi" data-no_tiket="'.$no_tiket.'" data-id_pengajuan_pemohon="'.$row['id_pengajuan_pemohon'].'" data-toggle="modal" data-target="#modal-ajukan" >Send to Akuntan</button>';
                                    //$button_tambah = '<button class="btn btn-success btn-xs tambah" data-no_tiket="'.$no_tiket.'" data-toggle="modal" data-target="#modal-invoice">Tambah</button>';
                                    $button_tambah = '<button class="btn btn-success btn-xs tambah-data-invoice" 
                                            data-no_tiket="'.$no_tiket.'" 
                                            data-no_invoice_pp="'.$row['no_invoice_pp'].'"
                                            data-uraian="'.$row['uraian'].'"
                                            data-tahun="'.$row['tahun'].'"
                                            data-bulan="'.$row['bulan'].'"
                                            data-tgl="'.$row['tgl'].'"
                                            onclick="document.getElementById(\'myModal\').style.display=\'block\'" >Tambah</button>';

                                    $row_nomor_pengajuan = $row['nomor_pengajuan'];
                                    $row_no_invoice_pp = $row['no_invoice_pp'];
                                    $row_uraian = $row['uraian'];
                                    $row_no = $no;
                                    $row_tanggal = $row['tgl'].'-'.$row['bulan'].'-'.$row['tahun'];
    							}
                                echo '<tr>';
                                echo '<td>' . $row_no . '</td>';
                                echo '<td>' . $row_tanggal . '</td>';
                                echo '<td id="no_invoice_pp_'.$no_tiket.'">' . $row_no_invoice_pp . '</td>';
                                echo '<td id="uraian_'.$no_tiket.'">' . $row_uraian . '</td>';
                                echo '<td>' . $row['nomor_pengajuan'] . '</td>';
                                echo '<td>' . $row['kode_kegiatan'] . '</td>';
                                echo '<td>' . $row['kode_akun'] . '</td>';
                                echo '<td>' . $row['kode_dana'] . '</td>';
                                echo '<td>' . $segmen[$row['kode_dpsj']] . '</td>';
                                echo '<td class="text-right">' . number_format($row['aktual']) . '</td>';   
                                
                                // jika form = D02 maka gunakan pph_d02 dan netto_d02
                                if($row['form'] == 'D02'){
                                    echo '<td class="text-right">' . $row['pph_d02'] . '</td>';      
                                    echo '<td class="text-right">' . number_format($row['netto_d02']) . '</td>';   
                                } else {
                                    echo '<td class="text-right">' . $row['pph'] . '</td>';      
                                    echo '<td class="text-right">' . number_format($row['netto']) . '</td>';   
                                }
    
                                // nonaktifkan tombol edit, delete dan send jika sudah dikirim ke akuntan
                                if($row['invoice_status'] == 1){
                                    // tampilkan hanya pada baris pertama
                                    if($j > 1){
                                        $button_edit = '';
                                        $button_delete = '';
                                        $button_send = '';
                                        $button_tambah ='';
                                    } else {
                                        $button_edit = '<button class="btn btn-secondary btn-sm edit" disabled>Edit</button>';
                                        $button_delete = '<button class="btn btn-secondary btn-sm" disabled>Delete</button>';
                                        $button_send = '<button class="btn btn-secondary btn-sm" disabled>Sent</button>';
                                        $button_tambah = '<button class="btn btn-success btn-xs" data-no_tiket="'.$no_tiket.'" disabled>Tambah</button>';
                                    }    
                                }                            
                                //echo '<td>' . $button_edit . '</td>';
                                echo '<td '.$style.'>' . $button_edit .' '. $button_tambah . ' '. $button_delete.'</td>';
                                echo '</tr>';   
                                $j++;
                                $pph = ($row['form'] == 'D02') ? $row['pph_d02'] : $row['pph'];
                                $netto = ($row['form'] == 'D02') ? $row['netto_d02'] : $row['netto'];
                                $total_aktual += $row['aktual'];
                                $total_pph += $pph;
                                $total_netto += $netto;                           
                            }
                        }
                        
                        if($row['aktual'] > 0){
    						$no++;
                            echo '<tr style="font-weight:bold; background-color:#f0f0f0; color:#2c6a7a">';
                            //echo '<td colspan="8" class="text-center">TOTAL PERIODE ' . str_pad($tgl, 2, "0", STR_PAD_LEFT) . '-' . str_pad($bln, 2, "0", STR_PAD_LEFT) . '-' . $thn . ' (No Tiket: ' . $no_tiket . ')</td>';
                            echo '<td colspan="9" class="text-center total">TOTAL </td>';
                            echo '<td class="text-right total">' . number_format($total_aktual) . '</td>';
                            echo '<td class="text-right total">' . number_format($total_pph) . '</td>';
                            echo '<td class="text-right total">' . number_format($total_netto) . '</td>';
                            echo '</tr>';
                        }
                    }
                }
            }
        }        
    } else {
        echo '<tr><td colspan="11" class="text-center">Data tidak ditemukan</td></tr>';
    }
    ?>
    </tbody>
</table>

<script>
function filterTable() {
    const filterInvoice = document.getElementById("filterNoInvoice").value.toUpperCase();
    const filterPengajuan = document.getElementById("filterNoPengajuan").value.toUpperCase();
    
    const table = document.querySelector("table");
    const rows = Array.from(table.querySelectorAll("tr"));
    
    let currentGroupRows = [];
    let isGroupMatch = false;

    // Lewati header (index 0)
    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const tds = row.getElementsByTagName("td");
        if (tds.length < 2) continue;

        const cellInvoice = tds[2] ? tds[2].textContent.trim().toUpperCase() : "";
        const cellPengajuan = tds[4] ? tds[4].textContent.trim().toUpperCase() : "";
        const isTotalRow = tds[0].textContent.includes("TOTAL");

        // 1. Jika baris baru (punya No Invoice), proses grup sebelumnya
        if (cellInvoice !== "" && !isTotalRow) {
            applyVisibility(currentGroupRows, isGroupMatch);
            
            // Reset grup
            currentGroupRows = [row];
            isGroupMatch = cellInvoice.includes(filterInvoice) && cellPengajuan.includes(filterPengajuan);
        } 
        // 2. Jika baris detail atau baris TOTAL, tambahkan ke grup
        else {
            currentGroupRows.push(row);
            // Jika salah satu baris di dalam grup mengandung nomor pengajuan, tandai grup sebagai MATCH
            if (cellPengajuan.includes(filterPengajuan) && filterPengajuan !== "") {
                isGroupMatch = true;
            }
            // Khusus filter invoice, dia hanya akan cocok jika di header-nya cocok
            // (karena detail tidak punya no invoice)
        }
    }
    // Proses grup terakhir
    applyVisibility(currentGroupRows, isGroupMatch);
}

function applyVisibility(rows, match) {
    rows.forEach(row => {
        row.style.display = match ? "" : "none";
    });
}

// Event listener
document.getElementById("filterNoInvoice").addEventListener("input", filterTable);
document.getElementById("filterNoPengajuan").addEventListener("input", filterTable);

function clearFilter(id) {
    // 1. Kosongkan nilai input
    document.getElementById(id).value = "";
    
    // 2. Panggil fungsi filter (agar tabel di-update secara otomatis)
    filterTable();
}
</script>

<style>
#invoice-table tr td {
    border: 1px solid #ddd;
}
.total{
    background-color:#edf7fa;
    color:#2E7385;
    font-weight:bold;
}
</style>