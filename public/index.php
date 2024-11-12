<?php
require_once '../autoload.php';

use Config\Database;
use App\Router;
use Controller\Home;

//set database to prod
Database::getConnection("prod");

//set timezone
date_default_timezone_set("Asia/jakarta");

//set session for flasher message
if(!session_id()) session_start();

Router::add("GET", "/", Home::class, 'home' );
Router::add("GET", "/about", Home::class, 'about' );

Router::run();