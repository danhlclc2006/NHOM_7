<?php

namespace App\Controllers;

use App\Models\Tour;

class HomeController extends BaseController
{
    public function index()
    {
        $this->view('home');
    }
    public function tours()
    {
        $m = new Tour();
        $tours = $m->all();
        $this->view('tours/index', ['tours' => $tours]);
    }
    public function show()
    {
        $id = $_GET['id'] ?? null;
        $m = new Tour();
        $tour = $m->find((int)$id);
        $this->view('tours/show', ['tour' => $tour]);
    }
}
