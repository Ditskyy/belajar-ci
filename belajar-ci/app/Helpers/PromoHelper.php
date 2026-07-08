<?php

if (!function_exists('hitung_biaya_jasa')) {
    function hitung_biaya_jasa($total_harga) {
        if ($total_harga <= 10000000) {
            return $total_harga * 0.01;
        } else {
            return $total_harga * 0.02;
        }
    }
}

if (!function_exists('hitung_diskon_voucher')) {
    function hitung_diskon_voucher($total_harga, $voucher_code) {
        $persen = 0;
        
        $kode = strtoupper(trim($voucher_code));
        
        if ($kode == 'PROMO2025') {
            $persen = 0.10; // 10%
        } elseif ($kode == 'PROMO2026') {
            $persen = 0.15; // 15%
        } elseif ($kode == 'AKHIRTAHUN') {
            $persen = 0.25; // 25%
        }

        return $total_harga * $persen;
    }
}

if (!function_exists('hitung_free_mouse')) {
    function hitung_free_mouse($total_harga) {
        if ($total_harga >= 15000000) {
            return 150000;
        }
        return 0;
    }
}