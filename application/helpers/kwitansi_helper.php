<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (! function_exists('terbilang'))
{    
    function terbilang($number)
    {
        $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        if ($number < 12)
            return "" . $huruf[$number];
        elseif ($number < 20)
            return $this->terbilang($number - 10) . " Belas";
        elseif ($number < 100)
            return $this->terbilang($number / 10) . " Puluh" . $this->terbilang($number % 10);
        elseif ($number < 200)
            return " Seratus" . $this->terbilang($number - 100);
        elseif ($number < 1000)
            return $this->terbilang($number / 100) . " Ratus" . $this->terbilang($number % 100);
        elseif ($number < 2000)
            return " Seribu" . $this->terbilang($number - 1000);
        elseif ($number < 1000000)
            return $this->terbilang($number / 1000) . " Ribu" . $this->terbilang($number % 1000);
        elseif ($number < 1000000000)
            return $this->terbilang($number / 1000000) . " Juta" . $this->terbilang($number % 1000000);
        elseif ($number < 1000000000000)
            return $this->terbilang($number / 1000000000) . " Milyar" . $this->terbilang($number % 1000000000);
    }
}