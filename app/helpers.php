<?php

if (!function_exists('rupiah')) {
    /**
     * Format angka menjadi format Rupiah Indonesia
     * 
     * @param float|int|string $amount Jumlah yang akan diformat
     * @param bool $withPrefix Apakah menampilkan prefix "Rp" (default: true)
     * @return string
     */
    function rupiah($amount, $withPrefix = true)
    {
        // Konversi ke float jika string
        $amount = (float) $amount;
        
        // Format dengan pemisah ribuan (titik) dan desimal (koma)
        $formatted = number_format($amount, 0, ',', '.');
        
        // Tambahkan prefix Rp jika diperlukan
        if ($withPrefix) {
            return 'Rp ' . $formatted;
        }
        
        return $formatted;
    }
}

