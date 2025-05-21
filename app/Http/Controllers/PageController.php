<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
{
    return inertia('Home');
}

public function search()
{
    return inertia('Search');
}

public function room()
{
    return inertia('Room');
}
public function profilee()
{
    return inertia('Profilee');
}
}
