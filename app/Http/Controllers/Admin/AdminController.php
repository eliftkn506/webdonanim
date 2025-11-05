<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Admin kontrolü
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            if (Auth::check() && Auth::user()->role === 'user') {
                return redirect()->route('profil')->with('error', 'Bu sayfaya erişim yetkiniz yok.');
            }
            return redirect()->route('login')->with('error', 'Bu sayfaya erişmek için admin olarak giriş yapmanız gerekiyor.');
        }

        return view('admin.index'); // resources/views/admin/index.blade.php
    }
}