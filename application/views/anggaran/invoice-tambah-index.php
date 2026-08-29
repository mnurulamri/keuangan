<?php
foreach($result as $row){
    $periode[$row['tahun']][$row['bulan']][$row['tgl']][$row['no_tiket']][] = $row;
    $head_invoice = $row['tgl'].'-'.$row['bulan'].'-'.$row['tahun'];
}

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
//echo '<pre>';
//print_r($result); exit();
?>
<h4 style="color:red">UNDER CONSTRUCTION 🚧</h4>
<table class="styled-table invoice-table" width="100%" style="display:nonex;">
    
        <tr style="background-color:#fff; color:#43A5BE">
            <th>PERIODE</th> 
            <th>NO INVOICE PP</th>
            <th>URAIAN</th>
        </tr>
        <tr style="color:#696969">
            <th><?=$head_invoice?></th>
            <th><?=$result[0]['no_invoice_pp']?></th>
            <th><?=$result[0]['uraian']?></th>
        </tr>
   
</table>
<table class="styled-table invoice-table" width="100%" style="display:nonex;">
    <thead>
        <tr style="background-color:#fff; color:#43A5BE">
            <th><input type="checkbox" id="selectAll" onclick="toggleAll(this)"></th>
            <th>NOMOR PENGAJUAN</th>
            <th>PROCOST</th>
            <th>AKUN</th>
            <th>KODE DANA</th>
            <th>SEGMEN</th>
            <th>BRUTO</th>
            <th>PAJAK</th>
			<th>NETTO</th>
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
                        
                                $row_nomor_pengajuan = $row['nomor_pengajuan'];
                                $row_no_invoice_pp = $row['no_invoice_pp'];
                                $row_uraian = $row['uraian'];
                                $row_no = $no;
                                $row_tanggal = $row['tgl'].'-'.$row['bulan'].'-'.$row['tahun'];
                            echo '<tr>';
                            
                            echo '<td class="text-center"><input type="checkbox" name="pilih[]" value="'.$row['nomor_pengajuan'].'" class="checkcetak"></td>';
                            
                            echo '<td>' . $row['nomor_pengajuan'] . '</td>';
                            echo '<td>' . $row['kode_kegiatan'] . '</td>';
                            echo '<td>' . $row['kode_akun'] . '</td>';
                            echo '<td>' . $row['kode_dana'] . '</td>';
                            echo '<td>' . $row['deskripsi_dpsj'] . '</td>';
                            echo '<td class="text-right">' . number_format($row['aktual']) . '</td>';   
                            
                            // jika form = D02 maka gunakan pph_d02 dan netto_d02
                            if($row['form'] == 'D02'){
                                echo '<td class="text-right">' . $row['pph_d02'] . '</td>';      
                                echo '<td class="text-right">' . number_format($row['netto_d02']) . '</td>';   
                            } else {
                                echo '<td class="text-right">' . $row['pph'] . '</td>';      
                                echo '<td class="text-right">' . number_format($row['netto']) . '</td>';   
                            }
                            
                            echo '<td><i class="btn btn-danger btn-sm fa fa-times"></i></td>';
                            
                            echo '</tr>';   
                            
                            $j++;
                            $pph = ($row['form'] == 'D02') ? $row['pph_d02'] : $row['pph'];
                            $netto = ($row['form'] == 'D02') ? $row['netto_d02'] : $row['netto'];
                            $total_aktual += $row['aktual'];
                            $total_pph += $pph;
                            $total_netto += $netto;                           
                        }
						$no++;
                        echo '<tr style="font-weight:bold; background-color:#f0f0f0; color:#2c6a7a">';
                        //echo '<td colspan="8" class="text-center">TOTAL PERIODE ' . str_pad($tgl, 2, "0", STR_PAD_LEFT) . '-' . str_pad($bln, 2, "0", STR_PAD_LEFT) . '-' . $thn . ' (No Tiket: ' . $no_tiket . ')</td>';
                        echo '<td colspan="5" class="text-center total">TOTAL </td>';
                        echo '<td class="text-right total">' . number_format($total_aktual) . '</td>';
                        echo '<td class="text-right total">' . number_format($total_pph) . '</td>';
                        echo '<td class="text-right total">' . number_format($total_netto) . '</td>';
                        echo '</tr>';
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

<button class="btn btn-primary btn-sm">Tambah</button> 

<script>

// Fungsi untuk memilih semua checkbox yang terlihat
function toggleAll(source) {
    var checkboxes = document.querySelectorAll('input[name="pilih[]"]');
    for (var i = 0; i < checkboxes.length; i++) {
        // Hanya centang yang barisnya terlihat (display bukan none)
        if (checkboxes[i].closest('tr').style.display !== 'none') {
            checkboxes[i].checked = source.checked;
        }
    }
}
</script>