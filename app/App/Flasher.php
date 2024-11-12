<?php

namespace App;

class Flasher
{
    public static function setFlash(string $message): void
    {
        $_SESSION['flash'] = [
            'message' => $message
        ];
    }

    public static function flash(): void
    {
        if (isset($_SESSION['flash'])) {
            echo '<script> alert("' . $_SESSION['flash']['message'] . '"); </script>';

            unset($_SESSION['flash']);
        }
    }

}