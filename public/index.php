<?php
require_once '../autoload.php';

use Config\Database;
use App\Router;
use Controller\HomeController;
use Controller\UserController;

//set database to prod
Database::getConnection("prod");

//set timezone
date_default_timezone_set("Asia/jakarta");

//set session for flasher message
if (!session_id()) session_start();

Router::add("GET", "/", HomeController::class, 'home');
Router::add("GET", "/about", HomeController::class, 'about');

Router::add("GET", "/login", UserController::class, "login");
Router::add("GET", "/logout", UserController::class, "logout");
Router::add("GET", "/register", UserController::class, "register");
Router::add("GET", "/user/profile", UserController::class, "update");

Router::add("POST", "/login", UserController::class, "postLogin");
Router::add("POST", "/register", UserController::class, "postRegister");
Router::add("POST", "/user/profile", UserController::class, "postUpdate");
Router::add("POST", "/user/profile/password", UserController::class, "postPassword");

Router::add("GET","/error", HomeController::class, "error");

Router::run();
