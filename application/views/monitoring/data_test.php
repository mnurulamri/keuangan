<?php
echo '<pre>'; print_r($data); echo '</pre>';
//$array_keys = array_keys($data[0]); print_r($array_keys);
$role_anggaran = array(
    'tanggal'=>'Tgl Terima',
    'nomor_pengajuan'=>'No Pengajuan',
    'kode_unit'=>'Unit',
    'form'=>'Form',
    'uraian'=>'Uraian',
    'nominal_pengajuan'=>'Nominal Pengajuan',
    'anggaran_keterangan_disetujui'=>'Keterangan',
    'tgl_penyerahan'=>'Tgl Penyerahan',
    'verifikasi_anggaran'=>'Verifikasi',
    'tgl_pemberian_dokumen_ke_junior_akuntan'=>'Tgl Pemberian Dokumen ke Junior Akuntan',
    'no_invoice_pp'=>'No Invoice PP',
    );
$array_kasir = array(
    'tgl_umko_cair'=>'Tgl Terima',
    'nominal_umko_cair'=>'No Pengajuan',
    'realisasi_umko'=>'Unit',
    'sisa_umko'=>'Form',
    'tgl_penyerahan_spj'=>'Uraian',
    'tgl_penyerahan_reimburse'=>'Tgl Penyerahan Reimburse',
    'catatan'=>'Catatan'
    );
$array_verifikator = array(
    'keterangan_retur'=>'Keterangan Retur',
    'tgl_retur_fakultas'=>'Tgl Retur Fakultas',
    'tgl_selesai_verifikasi'=>'Tgl Selesai Verifikasi'
    );
$array_korpum = array(
    'tgl_verifikasi_korpum'=>'Tgl Verifikasi Korpum',
    'catatan_korpum'=>'Catatan Korpum',
    'tgl_verifikasi_pp_koord_pum'=>'Tgl Verifikasi PP Koord PUM'
    );
$array_yunior_akuntan = array(
    'no_invoice_pp'=>'No Invoice PP',
    'tgl_pp'=>'Tgl PP',
    'no_pp'=>'No PP',
    'pph_21'=>'PPH 21',
    'pph_23'=>'PPH 23',
    'netto'=>'Netto'
    );
$array_paf = array(
    'tgl_pengiriman_dokumen_ke_pau'=>'Tgl Pengiriman Dokumen ke PAU'
);
$array_pau = array(
    'tgl_retur_dari_pau'=>'Tgl Retur dari PAU',
    'keterangan_retur_pau'=>'Keterangan Retur PAU'
);
$array_ls = array(
    'tgl_transfer_ke_cashcard_ls'=>'Tgl Transfer ke Cashcard LS',
    'nominal'=>'Nominal'
);

// buat table dinamis sesuai isian data pada $data
?>
<div class="table-container" id="tableContainer">
    <table class="styled-table">
        <thead
            <tr>
                <?php
                foreach($data[0] as $key => $value){
                    // set background header sesuai dengan role dan ubah ke warna kuning jika sesuai
                    if(in_array($key, array_keys($role_anggaran))){
                        $background = '#FF00FF';
                        $color = '#fff';
                    } elseif(in_array($key, array_keys($array_kasir))){
                        $background = '#00FF00';
                        $color = '#444';
                    } else if(in_array($key, array_keys($array_verifikator))){
                        $background = '#26A69A';
                        $color = '#fff';
                    } else if(in_array($key, array_keys($array_korpum))){
                        $background = '#1155CC';
                        $color = '#fff';
                    } else if(in_array($key, array_keys($array_yunior_akuntan))){
                        $background = '#FFC000';
                        $color = '#444';
                    } else if(in_array($key, array_keys($array_paf))){
                        $background = '#6AA84F';
                        $color = '#fff';
                    } else if(in_array($key, array_keys($array_pau))){
                        $background = '#9CC2E5';
                        $color = '#444';
                    } else if(in_array($key, array_keys($array_ls))){
                        $background = '#CC0000';
                        $color = '#fff';
                    } else {
                        $background = '#444';
                        $color = '#fff';
                    }
                    echo "<th style='background-color:".$background."; color:".$color."'>".$key."</th>";
                }
                ?>
            </tr>
        </thead>
        <tbody id="tableBody">
        <?php
        foreach($data as $row){
            $class = '';

            echo "<tr>";
            $kolom = 1;
            foreach($row as $value){
                // tentukan class untuk kolom 1 sampai 6
                if($kolom >= 1 && $kolom <= 6){
                    $class = 'class="col-'.$kolom.'" style="border:1px solid #d7dad6ff;"';
                } else {
                    $class = 'style="border:1px solid #d7dad6ff;"';
                }
                echo '<td '.$class.'>'.$value.'</td>';
                $kolom++;
            }
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
</div>
<?php
?>