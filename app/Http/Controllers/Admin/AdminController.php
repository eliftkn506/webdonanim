<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Siparis;
use App\Models\Urun;
use App\Models\User;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // 1. Yetki Kontrolü
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            if (Auth::check() && Auth::user()->role === 'user') {
                return redirect()->route('profil')->with('error', 'Erişim yetkiniz yok.');
            }
            return redirect()->route('login')->with('error', 'Admin girişi gerekli.');
        }

        // 2. Temel İstatistikler
        $totalOrders = Siparis::count();
        $totalProducts = Urun::count();
        $totalUsers = User::where('role', 'user')->count();
        
        // Toplam Gelir
        $totalRevenue = Siparis::whereIn('durum', ['onaylandi', 'kargoda', 'teslim_edildi'])
            ->sum(DB::raw('toplam_tutar + kdv_tutari - indirim_tutari'));

        // 3. Sipariş Durumları
        $orderStatusCounts = Siparis::select('durum', DB::raw('count(*) as total'))
            ->groupBy('durum')
            ->pluck('total', 'durum')
            ->toArray();

        // 4. Son 7 Günlük Satış Grafiği
        $salesData = [];
        $dates = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dates[] = Carbon::now()->subDays($i)->format('d M');
            
            $dailySum = Siparis::whereDate('created_at', $date)
                ->whereIn('durum', ['onaylandi', 'kargoda', 'teslim_edildi'])
                ->sum(DB::raw('toplam_tutar + kdv_tutari - indirim_tutari'));
                
            $salesData[] = $dailySum;
        }

        // 5. Son Siparişler Tablosu
        $recentOrders = Siparis::with('user')
            ->latest()
            ->take(5)
            ->get();

        // 6. Kritik Stok Ürünleri (HATA BURADAYDI, DÜZELTİLDİ)
        // 'stok_adedi' yerine modelinizdeki gerçek isim olan 'stok' kullanıldı.
        $lowStockProducts = Urun::where('stok', '<', 10)
            ->orderBy('stok', 'asc')
            ->take(5)
            ->get();

        return view('admin.index', compact(
            'totalOrders', 
            'totalProducts', 
            'totalUsers', 
            'totalRevenue',
            'orderStatusCounts',
            'dates',
            'salesData',
            'recentOrders',
            'lowStockProducts'
        ));
    }
}