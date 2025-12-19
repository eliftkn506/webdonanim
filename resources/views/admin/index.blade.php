@extends('layouts.admin')

@section('title', 'Gösterge Paneli - Admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    
    <div class="row mb-4">
        <div class="col-lg-8 mb-4 order-0">
            <div class="card h-100">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Hoşgeldin {{ Auth::user()->name }}! 🎉</h5>
                            <p class="mb-4">
                                Bugün mağazanızda <span class="fw-bold">{{ $recentOrders->where('created_at', '>=', \Carbon\Carbon::today())->count() }}</span> yeni sipariş var. 
                                Yönetim panelinden tüm detayları kontrol edebilirsiniz.
                            </p>
                            <a href="{{ route('admin.siparisler.index') }}" class="btn btn-sm btn-outline-primary">Siparişleri Gör</a>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="{{ asset('sneat/assets/img/illustrations/man-with-laptop-light.png') }}" height="140" alt="View Badge User" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-4 order-1">
            <div class="row">
                <div class="col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <span class="avatar-initial rounded bg-label-success"><i class="bx bx-dollar"></i></span>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Toplam Gelir</span>
                            <h3 class="card-title mb-2">₺{{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                            <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> Artışta</small>
                        </div>
                    </div>
                </div>
                <div class="col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <span class="avatar-initial rounded bg-label-info"><i class="bx bx-shopping-bag"></i></span>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Siparişler</span>
                            <h3 class="card-title text-nowrap mb-1">{{ $totalOrders }}</h3>
                            <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +2.5%</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <div>
                        <h5 class="card-title mb-0">Haftalık Satış Analizi</h5>
                        <small class="text-muted">Son 7 günlük gelir tablosu</small>
                    </div>
                </div>
                <div class="card-body px-0">
                    <div id="incomeChart"></div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
            <div class="row">
                <div class="col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-package"></i></span>
                                </div>
                            </div>
                            <span class="d-block mb-1">Ürünler</span>
                            <h3 class="card-title text-nowrap mb-2">{{ $totalProducts }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <span class="avatar-initial rounded bg-label-secondary"><i class="bx bx-user"></i></span>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Müşteriler</span>
                            <h3 class="card-title mb-2">{{ $totalUsers }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex flex-column align-items-start gap-1">
                            <h5 class="mb-0">Sipariş Durumu</h5>
                            <small class="text-muted">Genel Dağılım</small>
                        </div>
                        <div id="orderStatusChart"></div>
                    </div>
                    <ul class="p-0 m-0">
                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-time"></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Bekleyen</h6>
                                    <small class="text-muted">Onay bekliyor</small>
                                </div>
                                <div class="user-progress">
                                    <small class="fw-semibold">{{ $orderStatusCounts['beklemede'] ?? 0 }}</small>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-success"><i class="bx bx-check"></i></span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Tamamlanan</h6>
                                    <small class="text-muted">Teslim edildi</small>
                                </div>
                                <div class="user-progress">
                                    <small class="fw-semibold">{{ $orderStatusCounts['teslim_edildi'] ?? 0 }}</small>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-lg-8 order-0 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Son Siparişler</h5>
                    <a href="{{ route('admin.siparisler.index') }}" class="small">Tümünü Gör</a>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>Sipariş No</th>
                                <th>Müşteri</th>
                                <th>Tutar</th>
                                <th>Durum</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td><a href="{{ route('admin.siparisler.show', $order->id) }}">#{{ $order->siparis_no }}</a></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-xs me-2">
                                            <span class="avatar-initial rounded-circle bg-label-primary">{{ substr($order->user->name ?? 'M', 0, 1) }}</span>
                                        </div>
                                        <span>{{ $order->user->name ?? 'Misafir' }}</span>
                                    </div>
                                </td>
                                <td class="fw-bold">₺{{ number_format($order->toplam_tutar, 2) }}</td>
                                <td>
                                    @php
                                        $badge = match($order->durum) {
                                            'beklemede' => 'bg-label-warning',
                                            'onaylandi' => 'bg-label-info',
                                            'teslim_edildi' => 'bg-label-success',
                                            'iptal_edildi' => 'bg-label-danger',
                                            default => 'bg-label-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badge }}">{{ ucfirst(str_replace('_', ' ', $order->durum)) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted">Henüz sipariş yok.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 order-2 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Kritik Stok</h5>
                    <span class="badge bg-label-danger rounded-pill">Düşük</span>
                </div>
                <div class="card-body">
                    <ul class="p-0 m-0">
                        @forelse($lowStockProducts as $product)
                        <li class="d-flex mb-4 pb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                @if($product->resim_url)
                                    <img src="{{ asset($product->resim_url) }}" class="rounded">
                                @else
                                    <span class="avatar-initial rounded bg-label-secondary"><i class="bx bx-box"></i></span>
                                @endif
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <small class="text-muted d-block mb-1">{{ $product->marka }}</small>
                                    <h6 class="mb-0 text-truncate" style="max-width: 150px;">{{ $product->urun_ad }}</h6>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-1">
                                    <h6 class="mb-0 text-danger">{{ $product->stok_adedi }}</h6>
                                    <span class="text-muted">Adet</span>
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="text-center text-success mt-4">
                            <i class="bx bx-check-circle fs-1"></i>
                            <p class="mt-2">Tüm stoklar yeterli seviyede.</p>
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Gelir Grafiği (Area Chart)
    const incomeChartEl = document.querySelector('#incomeChart');
    const incomeChartOptions = {
        series: [{
            name: 'Gelir',
            data: @json($salesData)
        }],
        chart: {
            height: 250,
            type: 'area',
            toolbar: { show: false }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2 },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.9,
                stops: [0, 90, 100]
            }
        },
        xaxis: {
            categories: @json($dates),
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            labels: {
                formatter: function (value) {
                    return "₺" + value;
                }
            }
        },
        colors: ['#696cff'], // Primary color
        grid: {
            strokeDashArray: 4,
            borderColor: '#f0f0f0'
        }
    };
    if (incomeChartEl) {
        new ApexCharts(incomeChartEl, incomeChartOptions).render();
    }

    // 2. Sipariş Durum Grafiği (Donut Chart)
    const orderStatusChartEl = document.querySelector('#orderStatusChart');
    const orderStatusData = {
        bekleyen: {{ $orderStatusCounts['beklemede'] ?? 0 }},
        onaylanan: {{ $orderStatusCounts['onaylandi'] ?? 0 }},
        kargoda: {{ $orderStatusCounts['kargoda'] ?? 0 }},
        teslim: {{ $orderStatusCounts['teslim_edildi'] ?? 0 }},
        iptal: {{ $orderStatusCounts['iptal_edildi'] ?? 0 }}
    };

    const orderStatusChartOptions = {
        series: Object.values(orderStatusData),
        labels: ['Bekleyen', 'Onaylanan', 'Kargoda', 'Teslim', 'İptal'],
        chart: {
            type: 'donut',
            width: 130,
            height: 130
        },
        colors: ['#ffab00', '#03c3ec', '#696cff', '#71dd37', '#ff3e1d'],
        dataLabels: { enabled: false },
        legend: { show: false },
        plotOptions: {
            pie: {
                donut: {
                    size: '65%',
                    labels: {
                        show: false
                    }
                }
            }
        }
    };
    if (orderStatusChartEl) {
        new ApexCharts(orderStatusChartEl, orderStatusChartOptions).render();
    }
});
</script>
@endsection