<?php
function truncateText($text, $length = 100, $suffix = '...')
{
    $text = strip_tags($text);
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    $truncated = mb_substr($text, 0, $length);
    $lastSpace = mb_strrpos($truncated, ' ');
    if ($lastSpace !== false) {
        $truncated = mb_substr($truncated, 0, $lastSpace);
    }
    return $truncated . $suffix;
}

function timeAgo($datetime, $full = false)
{
    // Mengubah string menjadi objek DateTime
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    // Menentukan unit waktu
    $units = [
        'tahun' => $diff->y,
        'bulan' => $diff->m,
        'minggu' => floor($diff->d / 7),
        'hari' => $diff->d,
        'jam' => $diff->h,
        'menit' => $diff->i,
        'detik' => $diff->s,
    ];

    // Mencari unit waktu yang pertama kali tidak nol
    foreach ($units as $unit => $value) {
        if ($value > 0) {
            $timeString = $value . ' ' . $unit . ($value > 1 ? ' yang lalu' : ' yang lalu');
            return $timeString;
        }
    }

    return 'baru saja'; // Jika tidak ada perbedaan waktu
}