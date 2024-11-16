<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?= isset($title) ? "$title | ResepoItaliano" : "ResepoItaliano" ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap"
          rel="stylesheet"/>
    <link rel="stylesheet" href="/style/style.css"/>
    <link rel="stylesheet" href="/style/core.css"/>
</head>
<body class="normal-font-size">
<?php

use App\Flasher;

Flasher::flash();
?>
