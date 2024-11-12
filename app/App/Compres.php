<?php

namespace App;

class Compres
{
    public function compress($data)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Tentukan direktori untuk menyimpan gambar
            $targetDir = "uploads/";
            $targetFile = $targetDir . basename($_FILES["file"]["name"]);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Validasi apakah file adalah gambar
            $check = getimagesize($_FILES["file"]["tmp_name"]);
            if ($check === false) {
                die("File yang diupload bukan gambar.");
            }

            // Validasi ukuran file (misalnya, maksimum 2MB)
            if ($_FILES["file"]["size"] > 2000000) {
                die("Maaf, ukuran file terlalu besar.");
            }

            // Validasi format gambar
            $allowedFormats = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array($imageFileType, $allowedFormats)) {
                die("Maaf, hanya file JPG, JPEG, PNG, dan GIF yang diperbolehkan.");
            }

            // Resize dan kompres gambar
            $newWidth = 800; // Lebar baru
            $newHeight = 600; // Tinggi baru

            // Buat gambar baru berdasarkan format
            switch ($imageFileType) {
                case 'jpg':
                case 'jpeg':
                    $srcImage = imagecreatefromjpeg($_FILES["file"]["tmp_name"]);
                    break;
                case 'png':
                    $srcImage = imagecreatefrompng($_FILES["file"]["tmp_name"]);
                    break;
                case 'gif':
                    $srcImage = imagecreatefromgif($_FILES["file"]["tmp_name"]);
                    break;
                default:
                    die("Format gambar tidak didukung.");
            }

            // Buat gambar baru dengan ukuran yang diinginkan
            $dstImage = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, imagesx($srcImage), imagesy($srcImage));

            // Simpan gambar yang telah diresize
            switch ($imageFileType) {
                case 'jpg':
                case 'jpeg':
                    imagejpeg($dstImage, $targetFile, 80); // 80 adalah kualitas
                    break;
                case 'png':
                    imagepng($dstImage, $targetFile, 8); // 8 adalah tingkat kompresi
                    break;
                case 'gif':
                    imagegif($dstImage, $targetFile);
                    break;
            }

            // Bersihkan memori
            imagedestroy($srcImage);
            imagedestroy($dstImage);

            echo "Gambar berhasil diupload dan dioptimalkan.";
        } else {
            echo "Tidak ada file yang diupload.";
        }

    }
}