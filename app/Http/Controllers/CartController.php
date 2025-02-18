<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        return view('cart.index');
    }
}
