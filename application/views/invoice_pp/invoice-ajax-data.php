<?php

foreach($result as $row){
    $periode[$row['tahun']][$row['bulan']][$row['tgl']][$row['no_tiket']][] = $row;
}

// hilangkan bruto = 0
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
//echo '<pre>';print_r($array_periode);echo '</pre>'; exit();
foreach($array_segmen as $row){
	$segmen[$row['kode_dpsj']] = $row['segmen'];
}
?>
<h4>Daftar Invoice PP (<i style="color:red">Under Construction</i>)</h4>

<input type="text" id="searchInvoice" placeholder="Cari berdasarkan No Invoice">
<input type="text" id="searchPengajuan" placeholder="Cari berdasarkan No Pengajuan">

<table id="invoiceTable"  class="styled-table" width="100%">

    <?php
    // Cek role user
    $role = $this->session->userdata('logged_anggaran')['role'];

    // jika role manajer sembunyikan kolom aksi untuk verifikator, yunior akuntan, dan korpum
    $hideColumns = [];
    if($role == 'manajer'){
        $hideColumns = ['aksi_verifikator', 'aksi_yunior', 'tgl_pp_korpum', 'no_pp_korpum', 'aksi_korpum'];
    }

    // jika role verfikator sembunyikan kolom aksi untuk yunior akuntan dan korpum dan udab tombol approval untuk manajer menjadi disabled
    if($role == 'verifikator'){
        $hideColumns = ['aksi_yunior', 'tgl_pp_korpum', 'no_pp_korpum', 'aksi_korpum'];
    }

    // jika role yunior akuntan sembunyikan kolom aksi untuk verifikator dan korpum
    if($role == 'yunior_akuntan'){
        $hideColumns = ['aksi_verifikator', 'tgl_pp_korpum', 'no_pp_korpum', 'aksi_korpum'];
    }

    // jika role korpum sembunyikan kolom aksi untuk verifikator dan yunior akuntan
    if($role == 'korpum'){
        $hideColumns = ['aksi_verifikator', 'aksi_yunior', 'no_pp_yunior', 'tgl_pp_yunior'];
    }
    
    // render kolom berdasarkan role
    $tableHeaders = [
        'tanggal_invoice' => 'TANGGAL INVOICE',
        'no_invoice_pp' => 'NO INVOICE PP',
        'uraian' => 'URAIAN',
        'bruto' => 'BRUTO',
        'pajak' => 'PAJAK',
        'netto' => 'NETTO',
        'persetujuan' => 'Persetujuan',
        'no_mdk' => 'NO MDK',
        'tgl_mdk' => 'TANGGAL MDK',
        'aksi_verifikator' => 'AKSI',
        'no_pp_yunior' => 'NO PP',
        'tgl_pp_yunior' => 'TANGGAL PP',
        'aksi_yunior' => 'AKSI',
        'no_pp_korpum' => 'NO PP',
        'tgl_pp_korpum' => 'TANGGAL PP',
        'tgl_transfer_korpum' => 'TANGGAL TRANSFER',
        'aksi_korpum' => 'AKSI'
    ];

    // sembunyikan kolom yang tidak diperlukan berdasarkan role
    $filteredHeaders = array_filter($tableHeaders, function($key) use ($hideColumns) {
        return !in_array($key, $hideColumns);
    }, ARRAY_FILTER_USE_KEY);
    
    $jumlahKolom = count($filteredHeaders);

    //echo '<pre>';print_r($filteredHeaders);echo '</pre>';

    // Render header tabel
    $renderedHeaders = '';
    foreach($filteredHeaders as $header) {
        $renderedHeaders .= "<th>{$header}</th>";
    }
    echo "<thead><tr style='background-color:#43A5BE; color:#fff'>{$renderedHeaders}</tr></thead>";
    
    // jika manajer maka sembunyikan kolom dengan class aksi_verifikator, aksi_yunior, tgl_pp_korpum, no_pp_korpum, aksi_korpum
    if($role == 'manajer'){
        echo '<style>
            .aksi_verifikator, .aksi_yunior, .korpum, .aksi_korpum {
                display: none;
            }
        </style>';
    }

    // jika verifikator maka sembunyikan kolom dengan class aksi_yunior, tgl_pp_korpum, no_pp_korpum, aksi_korpum
    if($role == 'verifikator'){
        echo '<style>
            .aksi_yunior, .korpum, .aksi_korpum {
                display: none;
            }
        </style>';
    }

    // jika yunior akuntan maka sembunyikan kolom dengan class aksi_verifikator, tgl_pp_korpum, no_pp_korpum, aksi_korpum
    if($role == 'yunior_akuntan'){
        echo '<style>
            .aksi_verifikator, .korpum, .aksi_korpum {
                display: none;
            }
        </style>';
    }

    // jika korpum maka sembunyikan kolom dengan class aksi_verifikator, aksi_yunior, tgl_pp_korpum, no_pp_korpum, aksi_korpum
    if($role == 'korpum'){
        echo '<style>
            .aksi_verifikator, .yunior, .aksi_yunior {
                display: none;
            }
        </style>';
    }
    ?>

    <thead>
        <?php
        $ref = '<tr style="background-color:#43A5BE; color:#fff">
            <th>TANGGAL INVOICE</th>
            <th>NO INVOICE PP</th>
            <th>URAIAN</th>
            <th>BRUTO</th>
            <th>PAJAK</th>
			<th>NETTO</th>
            <!-- untuk manajer -->
			<th>Persetujuan</th>
            <!-- untuk verifikator -->
            <th>NO MDK</th>
            <th>TANGGAL MDK</th>
            <th>AKSI</th>
            <!-- untuk yunior akuntan -->
            <th>NO PP</th>
            <th>TANGGAL PP</th>
            <th>AKSI</th>
            <!-- untuk korpum -->
            <th>NO PP</th>
            <th>TANGGAL PP</th>
            <th>TANGGAL TRANSFER</th>
            <th>AKSI</th>'; ?>
        </tr>
    </thead>
    <tbody>
    <?php
    if(isset($array_periode)){
        $barisKe = 1;
        foreach($array_periode as $thn => $bulan_data){
            foreach($bulan_data as $bln => $tgl_data){     
                foreach($tgl_data as $tgl => $no_tiket_data){
					$no = 1;
                    foreach($no_tiket_data as $no_tiket => $data_row){

	                    // Tambahkan id unik untuk setiap detail toggle
	                    $toggleId = 'detail-' . $no_tiket;
	                    $toggleHeadId = 'head-' . $no_tiket;
                    
						// set nilai id_pengajuan_pemohon

					    $id_pengajuan_pemohon_value = '';

                        foreach($data_row as $value){
                            $id_pengajuan_pemohon_value .= $value['id_pengajuan_pemohon'].',';
                        }

                        $id_pengajuan_pemohon_value = substr($id_pengajuan_pemohon_value, 0, -1);
                        $tgl_invoice_pp = str_pad($tgl, 2, "0", STR_PAD_LEFT) . '-' . str_pad($bln, 2, "0", STR_PAD_LEFT) . '-' . $thn;

                        // jika invoice status belum disetujui oleh manajer, maka tombol approval untuk manajer aktif dan tombol untuk verifikator, yunior akuntan, dan korpum diganti menjadi text "Menunggu Persetujuan"
                        if($data_row[0]['invoice_status'] == 0){
                            if($role == 'manajer'){
                                $button_send = '<button class="btn btn-warning btn-xs send_to_akuntan_konfirmasi" data-no_tiket="'.$no_tiket.'" data-id_pengajuan_pemohon="'.$id_pengajuan_pemohon_value.'" data-toggle="modal" data-target="#modal-ajukan" >Approval</button>';
                            } else {
                                $button_send = '<span class="text-warning">Menunggu Persetujuan</span>';
                            }
                        } else if($data_row[0]['invoice_status'] > 0 ){
                            // jika role manajer maka buat tombol disetujui untuk memfasilitasi manajer membatalkan persetujuan invoice yang sudah disetujui
                            if($role == 'manajer'){
                                $button_send = '<span class="btn bg-aqua btn-xs send_to_akuntan_konfirmasi_batalkan" data-no_tiket="'.$no_tiket.'" data-id_pengajuan_pemohon="'.$id_pengajuan_pemohon_value.'" data-toggle="modal" data-target="#modal-ajukan" >Disetujui</span>';
                            } else {
                                $button_send = '<span class="label bg bg-aqua text-bold">Disetujui</span>';
                            }
                            //$button_send = '<span class="btn bg-aqua btn-xs send_to_akuntan_konfirmasi_batalkan" data-no_tiket="'.$no_tiket.'" data-id_pengajuan_pemohon="'.$id_pengajuan_pemohon_value.'" data-toggle="modal" data-target="#modal-ajukan" >Disetujui</span>';
                        } else {
                            $button_send = '<span class="text-warning">...</span>';
                        }
						
                        // set disabled tombol edit PP
						if( is_null($data_row[0]['no_mdk']) or $data_row[0]['tgl_mdk']=='0000-00-00' or $data_row[0]['invoice_status'] == 0){
	                        $button_pp = '';
                        } else {
							$button_pp = '<button class="btn btn-info btn-xs edit_pp" data-no_tiket="'.$no_tiket.'" data-id_pengajuan_pemohon="'.$id_pengajuan_pemohon_value.'" data-aksi_pp="pp" data-no_invoice_pp="' . $data_row[0]['no_invoice_pp'] . '" data-tgl_invoice_pp="' . $tgl_invoice_pp . '">Edit PP</button> ';
						}
                        // set disabled tombol edit MDK
						if( $data_row[0]['invoice_status'] == 0){
	                        $button_mdk = '';
                        } else {
							$button_mdk = '<button class="btn btn-success btn-xs edit_mdk" data-no_tiket="'.$no_tiket.'" data-id_pengajuan_pemohon="'.$id_pengajuan_pemohon_value.'" data-aksi_pp="mdk" data-no_invoice_pp="' . $data_row[0]['no_invoice_pp'] . '" data-tgl_invoice_pp="' . $tgl_invoice_pp . '">Edit MDK</button> ';
						}
						
                        // set disabled tombol korpum
						if( is_null($data_row[0]['no_pp']) or $data_row[0]['tgl_pp']=='0000-00-00' or $data_row[0]['invoice_status'] == 0){
	                        $button_korpum = '';
                        } else {
							$button_korpum = '<button class="btn btn-primary btn-xs konfirmasi" data-no_tiket="'.$no_tiket.'" data-id_pengajuan_pemohon="'.$data_row[0]['id_pengajuan_pemohon'].'"> Status Pengajuan </button>';
						}
						
						$tgl_mdk = ($data_row[0]['tgl_mdk'] == '0000-00-00' ? '' : $data_row[0]['tgl_mdk']);
							
						$tgl_pp = ($data_row[0]['tgl_pp'] == '0000-00-00' ? '' : $data_row[0]['tgl_pp']);
						
                        // Cek apakah user adalah manajer
                        //$is_manajer = ($this->session->userdata('logged_anggaran')['role'] == 'manajer');
                        
                        // Logika tampilan berdasarkan role
                        /*$aksi_verifikator = $is_manajer ? '' : '<button class="btn btn-info btn-xs edit_mdk" data-no_tiket="'.$no_tiket.'" data-id_pengajuan_pemohon="'.$id_pengajuan_pemohon_value.'" data-aksi_pp="mdk" data-no_invoice_pp="' . $data_row[0]['no_invoice_pp'] . '" data-tgl_invoice_pp="' . $tgl_invoice_pp . '">Edit MDK</button>';
                        $no_pp_juju = $is_manajer ? '' : $data_row[0]['no_pp'];
                        $tgl_pp_juju = $is_manajer ? '' : $tgl_pp;
                        $aksi_juju = $is_manajer ? '' : $button_pp;*/
                        
                        // Logika tampilan untuk kolom Operator MDK
                        /*$data_no_mdk = $is_manajer ? '' : $data_row[0]['no_mdk'];
                        $data_tgl_mdk = $is_manajer ? '' : $tgl_mdk;
                        $aksi_mdk = $is_manajer ? '' : '<button class="btn btn-info btn-xs edit_mdk" data-no_tiket="'.$no_tiket.'" data-id_pengajuan_pemohon="'.$id_pengajuan_pemohon_value.'" data-aksi_pp="mdk" data-no_invoice_pp="' . $data_row[0]['no_invoice_pp'] . '" data-tgl_invoice_pp="' . $tgl_invoice_pp . '">Edit MDK</button>';
*/
                        // Untuk Korpum (Jika manajer, dikosongkan)
                        /*$no_pp_korpum = $is_manajer ? '' : $data_row[0]['no_pp'];
                        $tgl_pp_korpum = $is_manajer ? '' : $tgl_pp;
                        $aksi_korpum = $is_manajer ? '' : $button_korpum;
    */
                        // set aksi toggleDetail
                        $aksi_toggle = ' onclick="toggleDetail(\''.$toggleId.'\', \''.$toggleHeadId.'\', \''.$barisKe.'\')" ';
                        
                        // hitung pph_d01_d02
                        $total_pph_main = 0;
                        foreach($data_row as $row){
                            $pph_d01_d02_main = ($row['form']=='D02' ? $row['pph_d02'] : $row['pph']);
                            $total_pph_main += $pph_d01_d02_main;
                        }
                        // tampilkan no_invoice_pp hanya sekali untuk setiap no_tiket
                        echo '
                        <tr id="'.$toggleHeadId.'" style="display:nonex;">
                            <td '.$aksi_toggle.' style="cursor:pointer;" id="'.$toggleHeadId.'">' . str_pad($tgl, 2, "0", STR_PAD_LEFT) . '-' . str_pad($bln, 2, "0", STR_PAD_LEFT) . '-' . $thn . '</td>
                            <td '.$aksi_toggle.' style="cursor:pointer;" id="'.$toggleHeadId.'">' . $data_row[0]['no_invoice_pp'] . '</td>
                            <td '.$aksi_toggle.' style="cursor:pointer;" id="'.$toggleHeadId.'">' . $data_row[0]['uraian'] . '</td>
                            <td '.$aksi_toggle.' style="cursor:pointer;" id="'.$toggleHeadId.'" class="text-right">' . number_format(array_sum(array_column($data_row, 'aktual'))) . '</td>
                            <!--<td '.$aksi_toggle.' style="cursor:pointer;" id="'.$toggleHeadId.'" class="text-right">' . number_format(array_sum(array_column($data_row, 'pph'))) . '</td>-->
                            <td '.$aksi_toggle.' style="cursor:pointer;" id="'.$toggleHeadId.'" class="text-right">' . number_format($total_pph_main) . '</td>
                            <td '.$aksi_toggle.' style="cursor:pointer;" id="'.$toggleHeadId.'" class="text-right">' . number_format(array_sum(array_column($data_row, 'netto_d01_d02'))) . '</td>
                            <!-- untuk manajer --> 
							<td>
                                '.$button_send.'
                            </td>
                            <!-- untuk verifikator -->
                            <td '.$aksi_toggle.' class="verifikator" id="input_no_mdk_'.$no_tiket.'">'.($data_row[0]['no_mdk'] ? $data_row[0]['no_mdk'] : '-').'</td>
                            <td '.$aksi_toggle.' class="verifikator" id="input_tgl_mdk_'.$no_tiket.'">'.($tgl_mdk ? $tgl_mdk : '-').'</td>
                            <td class="aksi_verifikator">
                                '.$button_mdk.'
                            </td>
                            <td '.$aksi_toggle.' class="yunior" id="input_no_pp_'.$no_tiket.'">'.($data_row[0]['no_pp'] ? $data_row[0]['no_pp'] : '-').'</td>
                            <td '.$aksi_toggle.' class="yunior" id="input_tgl_pp_'.$no_tiket.'">'.($tgl_pp ? $tgl_pp : '-').'</td>
                            <td class="aksi_yunior">
                                '.$button_pp.'
                            </td>
                            <!-- untuk korpum -->
                            <td '.$aksi_toggle.' class="korpum" id="input_no_korpum_'.$no_tiket.'">'.($data_row[0]['no_pp'] ? $data_row[0]['no_pp'] : '-').'</td>
                            <td '.$aksi_toggle.' class="korpum">'.($tgl_pp ? $tgl_pp : '-').'</td>
                            <td '.$aksi_toggle.' class="korpum"></td>
                             <td class="aksi_korpum">
                                '.$button_korpum.'
                            </td>
                        </tr>';

                        // Detail yang di-toggle
                        echo '
                        <tr id="'.$toggleId.'" style="display:none;" class="detail">
                            <td colspan="'.$jumlahKolom.'">
                                <table class="table table-bordered" width="100%" style="font-size:0.99em;border-collapse:collapse;">
                                    <tr style="background-color:#d9edf7; font-weight:bold; color:#31708f">
                                        <th style="display:none;" style="display:none;">No</th>  
                                        <th style="display:none;">PERIODE</th>
                                        <th style="display:none;">NO INVOICE PP</th>
                                        <th style="display:none;">URAIAN</th>  
                                        <th>NOMOR PENGAJUAN</th>
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
                                        $style = 'style="color:#fff; display:none;"';
                                    } else {
                                        $style = 'style="color:#444; display:none;"';
                                    }
                                    echo '<tr style="background-color:#fff; color:#2c6a7a;">';
                                    echo '<td '.$style.'>' . $no . '</td>';
                                    echo '<td '.$style.'>' . $row['tgl'].'-'.$row['bulan'] . '-' . $row['tahun'] . '</td>';
                                    echo '<td '.$style.'>' . $row['no_invoice_pp'] . '</td>';
                                    echo '<td '.$style.'>' . $row['uraian'] . '</td>';
                                    echo '<td>' . $row['nomor_pengajuan'] . '</td>';
                                    echo '<td>' . $row['kode_kegiatan'] . '</td>';
                                    echo '<td>' . $row['kode_akun'] . '</td>';
                                    echo '<td>' . $row['kode_dana'] . '</td>';
                                    echo '<td>' . $segmen[$row['kode_dpsj']] . '</td>';
                                    echo '<td class="text-right">' . number_format($row['aktual']) . '</td>';    
                                    echo '<td class="text-right">' . ($row['form']=='D02' ? number_format($row['pph_d02']) : number_format($row['pph'])) . '</td>';      
                                    echo '<td class="text-right">' . number_format($row['netto_d01_d02']) . '</td>'; 
                                    echo '</tr>';   
                                    $j++;
                                    $pph_d01_d02 = ($row['form']=='D02' ? $row['pph_d02'] : $row['pph']);
                                    $total_aktual += $row['aktual'];
                                    $total_pph += $pph_d01_d02;
                                    $total_netto += $row['netto_d01_d02'];                            
                                }
                                $no++;
                        echo '<tr style="font-weight:bold; background-color:#f0f0f0; color:#2c6a7a">';
                        //echo '<td colspan="8" class="text-center">TOTAL PERIODE ' . str_pad($tgl, 2, "0", STR_PAD_LEFT) . '-' . str_pad($bln, 2, "0", STR_PAD_LEFT) . '-' . $thn . ' (No Tiket: ' . $no_tiket . ')</td>';
                        echo '<td colspan="5" class="text-center">TOTAL </td>';
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

    // Jika detail sedang tersembunyi (karena pencarian atau sudah di-close)
    if (el.style.display === "none") {
        el.style.display = "table-row"; // Paksa ubah dulu ke table-row
        el.style.opacity = 0;           // Reset untuk animasi fade
        fadeIn(el, 500);      
        headRow.style.color = "#c86744ff"; 
        headRow.style.fontWeight = "bold";
    } else {
        fadeOut(el, 500);      
        headRow.style.color = "#444"; 
        headRow.style.fontWeight = "normal";
    }
}


    
    /*if(barisKe > 1){
        var el = document.getElementById(headId);
        if (el.style.display === "none") {
            fadeIn(el, 500);
        } else {
            fadeOut(el, 500);
        }
    }   
}*/

function fadeIn(element, duration) {
    element.style.opacity = 0;
    element.style.display = "table-row"; // Ubah dari "" menjadi "table-row"
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

document.getElementById('searchInvoice').addEventListener('keyup', function() {
    let input = this.value.toLowerCase();
    let table = document.getElementById('invoiceTable');
    let rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        let row = rows[i];
        // Hanya proses baris utama (yang punya ID head-...)
        if (row.id && row.id.startsWith('head-')) {
            let noInvoiceCell = row.getElementsByTagName('td')[1];
            let text = noInvoiceCell ? noInvoiceCell.textContent.toLowerCase() : "";
            let detailId = row.id.replace('head-', 'detail-');
            let detailRow = document.getElementById(detailId);

            if (text.indexOf(input) > -1) {
                row.style.display = "table-row"; // Paksa tampil sebagai row tabel
            } else {
                row.style.display = "none"; // Sembunyikan baris
                if (detailRow) detailRow.style.display = "none"; // Sembunyikan detailnya juga
            }
        }
    }
});

// Fungsi pembantu untuk melakukan filter
function filterTable() {
    let invInput = document.getElementById('searchInvoice').value.toLowerCase();
    let pgnInput = document.getElementById('searchPengajuan').value.toLowerCase();
    let table = document.getElementById('invoiceTable');
    let rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        let row = rows[i];
        
        if (row.id && row.id.startsWith('head-')) {
            let detailId = row.id.replace('head-', 'detail-');
            let detailRow = document.getElementById(detailId);
            
            // Ambil data untuk pengecekan
            let noInvoice = row.getElementsByTagName('td')[1].textContent.toLowerCase();
            let detailContent = detailRow ? detailRow.textContent.toLowerCase() : "";

            // Logika: Baris tampil jika No Invoice cocok DAN Nomor Pengajuan cocok
            let matchInvoice = noInvoice.indexOf(invInput) > -1;
            let matchPengajuan = detailContent.indexOf(pgnInput) > -1;

            if (matchInvoice && matchPengajuan) {
                row.style.display = "table-row";
                // Jika sedang mengetik di pencarian pengajuan, otomatis buka detailnya
                if (pgnInput !== "") {
                    detailRow.style.display = "table-row";
                    detailRow.style.opacity = 1;
                } else {
                    detailRow.style.display = "none";
                }
            } else {
                row.style.display = "none";
                if (detailRow) detailRow.style.display = "none";
            }
        }
    }
}

// Pasang event listener ke kedua input
document.getElementById('searchInvoice').addEventListener('keyup', filterTable);
document.getElementById('searchPengajuan').addEventListener('keyup', filterTable);


</script>
<style>
.hidden {
    display: none !important;
}

</style>