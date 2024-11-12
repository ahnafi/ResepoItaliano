<?php
require_once '../autoload.php';

use Config\Database;
use App\Router;
use Controller\Home;

Database::getConnection("prod");

Router::add("GET", "/", Home::class, 'home' );
Router::add("GET", "/about", Home::class, 'about' );

Router::run();