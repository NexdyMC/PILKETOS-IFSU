<?php

if (! function_exists('random_token')) {
    function random_token(int $length = 4): string
    {
        // Tanpa huruf/angka yang mirip (0, O, 1, I) supaya mudah diketik siswa
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $token = '';

        for ($i = 0; $i < $length; $i++) {
            $token .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $token;
    }
}