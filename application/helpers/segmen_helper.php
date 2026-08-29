<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (! function_exists('segmen'))
{
    function segmen() {
        return array(
            '09000100' => 'Pimpinan',
            '09000200' => 'SAF',
            '09000300' => 'DGBF',
            '09000400' => 'UPMA',
            '09000500' => 'Sekpim',
            '09000600' => 'PPAA',
            '09000700' => 'Kemahasiswaan',
            '09000800' => 'Keuangan',
            '09000900' => 'OPF',
            '09001300' => 'SDM',
            '09002200' => 'Rispub',
            '09002400' => 'Ventura',
            '09004400' => 'MBRC',
            '09010161' => 'S1 Reg Kom',
            '09010162' => 'S1 KKI Kom',
            '09010164' => 'S1 Par Kom',
            '09010182' => 'S2 Kom',
            '09010192' => 'S3 Kom',
            '09019000' => 'Dept Kom',
            '09020161' => 'S1 Pol',
            '09020182' => 'S2 Pol',
            '09020192' => 'S3 Po',
            '09029000' => 'Dept Pol',
            '09030161' => 'S1 Sosio',
            '09030182' => 'S2 Sosio',
            '09030192' => 'S3 Sosio',
            '09039000' => 'Dept Sosio',
            '09040161' => 'S1 Krim',
            '09040182' => 'S2 Krim',
            '09040192' => 'S3 Krim',
            '09049000' => 'Dept Krim',
            '09050161' => 'S1 Kessos',
            '09050182' => 'S2 Kessos',
            '09050192' => 'S3 Kessos',
            '09059000' => 'Dept Kessos',
            '09060161' => 'S1 Antrop',
            '09060182' => 'S2 Antrop',
            '09060192' => 'S3 Antrop',
            '09069000' => 'Dept Antrop',
            '09070161' => 'S1 HI',
            '09070182' => 'S2 HI',
            '09070192' => 'S3 HI',
            '09079000' => 'Dept HI'
        );
    }
}
/* End of file MY_url_helper.php */
/* Location: ./application/helpers/MY_url_helper.php */