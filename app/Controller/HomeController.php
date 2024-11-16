<?php

namespace Controller;

use App\View;

class HomeController
{

    public function home(): void
    {
        View::render("Home/index", [
            "title" => "Home",
        ]);
    }

    public function about(): void
    {
        View::render("Home/about", [
            "title" => "About",
        ]);
    }

    public function error(): void
    {
        View::render("error", [
            "title" => "404 Page Not Found",
        ]);
    }

}