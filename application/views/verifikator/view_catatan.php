<?php
//echo '<pre>';
//print_r($result);
//echo '</pre>';
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

echo '
    <table class="table table-bordered table-striped" style="color:#444">
        <thead>
            <tr class="bg-primary">
                <th>UNIT</th>
                <th>TANGGAL</th>
                <th>CATATAN</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-info text-bold">Anggaran</td>
                <td>
                    <!--<u><i>Diterima:</i></u><br>'.($result[0]['tgl_terima'] ?? '-').'<br>-->
                    <!--<u><i>Disetujui:</i></u><br>-->'.$result[0]['anggaran_tgl_disetujui'].'<br>                    
                </td>
                <td>
                    '.$result[0]['anggaran_keterangan_disetujui'].'
                </td>
            </tr>
            <tr>
                <td class="text-info text-bold">Koordinator PUM</td>
                <td>
                    <!--<u><i>Disetujui:</i></u><br>-->'.$result[0]['korpum_tgl_disetujui'].'<br>                    
                </td>
                <td>
                    '.stripslashes($result[0]['korpum_keterangan_disetujui']).'
                </td>
            </tr>
            <tr>
                <td class="text-info text-bold">Manajer Keuangan</td>
                <td>
                    <!--<u><i>Disetujui:</i></u><br>-->'.$result[0]['manajer_tgl_disetujui'].'<br>                    
                </td>
                <td>
                    '.stripslashes($result[0]['manajer_keterangan_disetujui']).'
                </td>
            </tr>
            <tr>
                <td class="text-info text-bold">Kasir</td>
                <td>
                    <!--<u><i>Disetujui:</i></u><br>-->'.$result[0]['kasir_tgl_disetujui'].'<br>
                </td>
                <td>
                    '.stripslashes($result[0]['kasir_keterangan_disetujui']).'
                </td>
            </tr>
            <tr>
                <td class="text-info text-bold">Verifikator</td>
                <td>
                    <!--<u><i>Selesai Verifikasi:</i></u><br>-->'.$result[0]['tgl_selesai_verifikasi'].'<br>                    
                    <!--<u><i>Retur:</i></u><br>-->'.$result[0]['tgl_retur_fakultas'].'<br>                    
                </td>
                <td>
                    '.stripslashes($result[0]['verifikator_keterangan_disetujui']).'<br>
                    '.stripslashes($result[0]['keterangan_retur']).'
                </td>
            </tr>
        </tbody>
    </table>';

?>