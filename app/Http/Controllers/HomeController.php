<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Urun;

class HomeController extends Controller
{

    public function index()
{
    // Öne çıkan ürünler (son eklenen 8 ürün)
    $urunler = Urun::with('altKategori')
        ->latest()
        ->take(8)
        ->get();
    
    return view('home', compact('urunler'));
}



}
