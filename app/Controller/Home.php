<?php

namespace Controller;

use App\View;

class Home {

    public function home() {
        View::render("index",[]);
    }
    public function about() {
        View::render("about",[]);
    }

}