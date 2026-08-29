<?php
//echo '<pre>';print_r($result);echo '</pre>';
//exit();
//$periode = array();
foreach($result as $row){
    $periode[$row['tahun']][$row['bulan']][$row['tgl']][$row['no_tiket']][] = $row;
}

?>

<table class="table" width="100%">
    <thead>
        <tr style="background-color:#43A5BE; color:#fff">
            <th>TANGGAL INVOICE</th>
            <th>NO INVOICE PP</th>
            <th>URAIAN</th>
            <th>BRUTO</th>
            <th>PAJAK</th>
			<th>NETTO</th>
            <th>NO PP</th>
            <th>TANGGAL PP</th>
            <th>AKSI</th>
        </tr>
    </thead>
    <tbody>
    <?php
    if(isset($periode)){
        $barisKe = 1;
        foreach($periode as $thn => $bulan_data){
            foreach($bulan_data as $bln => $tgl_data){     
                foreach($tgl_data as $tgl => $no_tiket_data){
					$no = 1;
                    foreach($no_tiket_data as $no_tiket => $data_row){

                    // Tambahkan id unik untuk setiap detail toggle
                    $toggleId = 'detail-' . $no_tiket;
                    $toggleHeadId = 'head-' . $no_tiket;

                        // tampilkan no_invoice_pp hanya sekali untuk setiap no_tiket
                        echo '<tr id="'.$toggleHeadId.'">';
                        echo '<td onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer;" id="'.$toggleHeadId.'">' . str_pad($tgl, 2, "0", STR_PAD_LEFT) . '-' . str_pad($bln, 2, "0", STR_PAD_LEFT) . '-' . $thn . '</td>';
                        //echo '<td>'; echo '<pre>';print_r($data_row);echo '</pre>';echo'</td>';
                        echo '<td onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer;" id="'.$toggleHeadId.'">' . $data_row[0]['no_invoice_pp'] . '</td>';
                        echo '<td onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer;" id="'.$toggleHeadId.'">' . $data_row[0]['uraian'] . '</td>';
                        echo '<td onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer;" id="'.$toggleHeadId.'" class="text-right">' . number_format(array_sum(array_column($data_row, 'aktual'))) . '</td>';
                        echo '<td onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer;" id="'.$toggleHeadId.'" class="text-right">' . number_format(array_sum(array_column($data_row, 'pph'))) . '</td>';
                        echo '<td onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" style="cursor:pointer;" id="'.$toggleHeadId.'" class="text-right">' . number_format(array_sum(array_column($data_row, 'netto'))) . '</td>';
                         echo '<td id="input_no_pp_'.$no_tiket.'">'.$data_row[0]['no_pp'].'</td>';
                        echo '<td id="input_tgl_pp_'.$no_tiket.'">'.$data_row[0]['tgl_pp'].'</td>';                        
                        echo '<td>';
                        echo '<button class="btn btn-info btn-xs edit_pp" data-no_tiket="'.$no_tiket.'" data-id_pengajuan_pemohon="'.$data_row[0]['id_pengajuan_pemohon'].'">Buat PP</button>';
                        echo '</td>';
                        echo '</tr>';
                        // Detail yang di-toggle
                        echo '<tr id="'.$toggleId.'" style="display:none;">';
                        echo '
                        <td colspan="10">
                            <table class="table table-bordered" width="100%" style="font-size:0.99em;border-collapse:collapse;">';
                        echo '
                                <tr style="background-color:#d9edf7; font-weight:bold; color:#31708f">
                                    <th>No</th>  
                                    <th>PERIODE</th>  
                                    <th>NOMOR PENGAJUAN</th>
                                    <th>NO INVOICE PP</th>
                                    <th>URAIAN</th>
                                    <th>PROCOST</th>
                                    <th>AKUN</th>
                                    <th>SUMBER DANA</th>
                                    <th>SEGMEN</th>
                                    <th>BRUTO</th>
                                    <th>PAJAK</th>
                                    <th>NETTO</th>
                                </tr>';                       
                        $total_aktual = 0;
                        $total_pph = 0;
                        $total_netto = 0;
						$j=1;
                        foreach($data_row as $row){


							if($j > 1){
								$style = 'style="color:#fff;"';
							} else {
								$style = 'style="color:#444;"';
							}
                            echo '<tr>';
                            echo '<td '.$style.'>' . $no . '</td>';
                            echo '<td '.$style.'>' . $row['tgl'].'-'.$row['bulan'] . '-' . $row['tahun'] . '</td>';
                            echo '<td>' . $row['nomor_pengajuan'] . '</td>';
                            echo '<td '.$style.'>' . $row['no_invoice_pp'] . '</td>';
                            echo '<td '.$style.'>' . $row['uraian'] . '</td>';
                            echo '<td>' . $row['kode_kegiatan'] . '</td>';
                            echo '<td>' . $row['kode_akun'] . '</td>';
                            echo '<td>' . $row['kode_dana'] . '</td>';
                            echo '<td>' . $row['deskripsi_dpsj'] . '</td>';
                            echo '<td class="text-right">' . number_format($row['aktual']) . '</td>';    
                            echo '<td class="text-right">' . $row['pph'] . '</td>';      
                            echo '<td class="text-right">' . number_format($row['netto']) . '</td>'; 
                            echo '</tr>';   
                            $j++;
                            $total_aktual += $row['aktual'];
                            $total_pph += $row['pph'];
                            $total_netto += $row['netto'];                            
                        }
						$no++;
                        echo '<tr style="font-weight:bold; background-color:#f0f0f0; color:#2c6a7a">';
                        //echo '<td colspan="8" class="text-center">TOTAL PERIODE ' . str_pad($tgl, 2, "0", STR_PAD_LEFT) . '-' . str_pad($bln, 2, "0", STR_PAD_LEFT) . '-' . $thn . ' (No Tiket: ' . $no_tiket . ')</td>';
                        echo '<td colspan="8" class="text-center">TOTAL </td>';
                        echo '<td class="text-right">' . number_format($total_aktual) . '</td>';
                        echo '<td class="text-right">' . number_format($total_pph) . '</td>';
                        echo '<td class="text-right">' . number_format($total_netto) . '</td>';
                        echo '</tr>
                                </table>
                            </td>
                        </tr>';
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
function toggleDetail(id, headId, barisKe) {
    
    var el = document.getElementById(id);
    var headRow = document.getElementById(headId);
    //var headRow = document.getElementByClassName("table").rows[barisKe - 1];

    if (el.style.display === "none") {
        fadeIn(el, 500);      
        headRow.style.color = "#c86744ff"; 
        headRow.style.fontWeight = "bold";
        headRow.style.borderBottom = "2px solid #fff";        
        //el.style.border = "2px solid #ddd";
    } else {
        fadeOut(el, 500);      
        headRow.style.color = "#444"; // default 
        headRow.style.fontWeight = "normal";
        //headRow.style.borderBottom = "2px solid #ddd";
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