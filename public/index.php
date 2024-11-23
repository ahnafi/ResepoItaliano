<?php
require_once '../autoload.php';

use Config\Database;
use App\Router;
use Controller\HomeController;
use Controller\UserController;
use \Middleware\MustLoginMiddleware;
use \Middleware\MustNotLoginMiddleware;
use Middleware\MustAdminMiddleware;
use Middleware\MustUserMiddleware;
use Controller\AdminController;

//set database to prod
Database::getConnection("prod");

//set timezone
date_default_timezone_set("Asia/jakarta");

//set session for flasher message
if (!session_id()) session_start();

// Home
Router::add("GET", "/", HomeController::class, 'home');
Router::add("GET", "/about", HomeController::class, 'about');
Router::add("GET", "/search", HomeController::class, 'search');
Router::add("GET", "/recipe/([0-9]*)", HomeController::class, 'detail');
Router::add("GET", "/recipe/add", HomeController::class, 'addRecipe', [MustLoginMiddleware::class]);
Router::add("GET", "/recipe/update/([0-9]*)", HomeController::class, 'updateRecipe', [MustLoginMiddleware::class]);

Router::add("POST", "/about/contact", HomeController::class, 'postEmail');
Router::add("POST", "/recipe/add", HomeController::class, 'postAddRecipe', [MustLoginMiddleware::class]);
Router::add("POST", "/recipe/remove", HomeController::class, 'postRemoveRecipe', [MustLoginMiddleware::class]);
Router::add("POST", "/recipe/update/([0-9]*)", HomeController::class, 'postUpdateRecipe', [MustLoginMiddleware::class]);
Router::add("POST", "/recipe/save/([0-9]*)", HomeController::class, 'postSaveRecipe', [MustLoginMiddleware::class]);
Router::add("POST", "/recipe/save/remove", HomeController::class, 'postRemoveSavedRecipe', [MustLoginMiddleware::class, MustUserMiddleware::class]);

// user
Router::add("GET", "/login", UserController::class, "login", [MustNotLoginMiddleware::class]);
Router::add("GET", "/logout", UserController::class, "logout", [MustLoginMiddleware::class]);
Router::add("GET", "/register", UserController::class, "register", [MustNotLoginMiddleware::class]);
Router::add("GET", "/user/profile", UserController::class, "profile", [MustLoginMiddleware::class, MustUserMiddleware::class]);
Router::add("GET", "/user/profile/password", UserController::class, "password", [MustLoginMiddleware::class, MustUserMiddleware::class]);
Router::add("GET", "/user/profile/saved-recipe", UserController::class, "savedRecipes", [MustLoginMiddleware::class, MustUserMiddleware::class]);
Router::add("GET", "/user/profile/manage-recipes", UserController::class, "manageRecipes", [MustLoginMiddleware::class, MustUserMiddleware::class]);

Router::add("POST", "/login", UserController::class, "postLogin", [MustNotLoginMiddleware::class]);
Router::add("POST", "/register", UserController::class, "postRegister", [MustNotLoginMiddleware::class]);
Router::add("POST", "/user/profile", UserController::class, "postUpdate", [MustLoginMiddleware::class]);
Router::add("POST", "/user/profile/password", UserController::class, "postPassword", [MustLoginMiddleware::class]);

//admin
Router::add("GET", "/admin/profile", AdminController::class, "profile", [MustLoginMiddleware::class, MustAdminMiddleware::class]);
Router::add("GET", "/admin/profile/password", AdminController::class, "password", [MustLoginMiddleware::class, MustAdminMiddleware::class]);
Router::add("GET", "/admin/profile/manage-recipes", AdminController::class, "manageRecipes", [MustLoginMiddleware::class, MustAdminMiddleware::class]);
Router::add("GET", "/admin/profile/manage-users", AdminController::class, "manageUsers", [MustLoginMiddleware::class, MustAdminMiddleware::class]);
Router::add("GET", "/admin/profile/register-admin", AdminController::class, "registerAdmin", [MustLoginMiddleware::class, MustAdminMiddleware::class]);

Router::add("POST", "/admin/profile/register-admin", AdminController::class, "postRegisterAdmin", [MustLoginMiddleware::class, MustAdminMiddleware::class]);

Router::add("GET", "/error", HomeController::class, "error");

Router::run();
