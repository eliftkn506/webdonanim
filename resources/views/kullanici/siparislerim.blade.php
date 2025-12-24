@extends('layouts.app')

@section('title', 'Siparişlerim - Avantaj Bilişim')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10">
            <div class="d-flex align-items-end justify-content-between border-bottom pb-3">
                <div>
                    <h2 class="fw-800 text-dark mb-1" style="letter-spacing: -0.5px;">Siparişlerim</h2>
                    <p class="text-muted mb-0">Geçmiş siparişlerinizi ve güncel durumlarını buradan takip edebilirsiniz.</p>
                </div>
                <div class="d-none d-md-block">
                    <span class="badge bg-white border text-secondary px-3 py-2 rounded-pill shadow-sm">
                        <i class="fas fa-receipt me-2 text-primary"></i>
                        Toplam {{ isset($siparisler) ? $siparisler->total() : 0 }} Sipariş
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">

            @if(isset($siparisler) && $siparisler->count() > 0)
                <div class="d-flex flex-column gap-3">
                    @foreach($siparisler as $siparis)
                        @php
                            // Durum Renk ve İkon Ayarlamaları
                            $durumConfig = match($siparis->durum) {
                                'beklemede' => ['class' => 'warning', 'icon' => 'fa-clock', 'text' => 'Ödeme Bekleniyor'],
                                'onaylandi' => ['class' => 'info', 'icon' => 'fa-clipboard-check', 'text' => 'Sipariş Onaylandı'],
                                'hazirlaniyor' => ['class' => 'primary', 'icon' => 'fa-box-open', 'text' => 'Hazırlanıyor'],
                                'kargoda' => ['class' => 'primary', 'icon' => 'fa-shipping-fast', 'text' => 'Kargoya Verildi'],
                                'tamamlandi' => ['class' => 'success', 'icon' => 'fa-check-circle', 'text' => 'Teslim Edildi'],
                                'iptal' => ['class' => 'danger', 'icon' => 'fa-times-circle', 'text' => 'İptal Edildi'],
                                default => ['class' => 'secondary', 'icon' => 'fa-circle', 'text' => ucfirst($siparis->durum)]
                            };
                        @endphp

                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden hover-shadow transition-all group-item">
                            <div class="card-body p-4">
                                <div class="row align-items-center gy-3">
                                    
                                    <div class="col-12 col-md-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle bg-light flex-shrink-0" style="width: 50px; height: 50px;">
                                                <i class="fas fa-shopping-bag fs-5 text-secondary opacity-50"></i>
                                            </div>
                                            <div>
                                                <div class="text-uppercase text-muted x-small fw-bold ls-1">Sipariş No</div>
                                                <div class="fw-800 text-dark fs-5 font-monospace">#{{ $siparis->siparis_no ?? $siparis->id }}</div>
                                                <div class="d-md-none mt-1">
                                                     <span class="text-muted small"><i class="far fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($siparis->created_at)->format('d.m.Y H:i') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3 d-none d-md-block border-start ps-md-4">
                                        <div class="mb-1">
                                            <span class="text-muted small fw-500"><i class="far fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($siparis->created_at)->format('d.m.Y H:i') }}</span>
                                        </div>
                                        <div class="fw-800 text-dark fs-5">{{ number_format($siparis->toplam_tutar, 2) }} ₺</div>
                                    </div>

                                    <div class="col-6 col-md-3">
                                        <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill bg-{{ $durumConfig['class'] }} bg-opacity-10 border border-{{ $durumConfig['class'] }} border-opacity-10">
                                            <i class="fas {{ $durumConfig['icon'] }} text-{{ $durumConfig['class'] }}"></i>
                                            <span class="fw-bold text-{{ $durumConfig['class'] }} small">{{ $durumConfig['text'] }}</span>
                                        </div>
                                        <div class="d-md-none mt-2 fw-800 text-dark">{{ number_format($siparis->toplam_tutar, 2) }} ₺</div>
                                    </div>

                                    <div class="col-6 col-md-2 text-end">
                                        <a href="{{ route('siparis.detay', $siparis->id) }}" class="btn btn-light btn-sm rounded-3 fw-bold text-secondary w-100 py-2 stretched-link" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                            Detay <i class="fas fa-arrow-right ms-1 opacity-50"></i>
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($siparisler->hasPages())
                    <div class="mt-5 d-flex justify-content-center">
                        {{ $siparisler->links('pagination::bootstrap-5') }}
                    </div>
                @endif

            @else
                <div class="text-center py-5 bg-white rounded-4 shadow-sm border border-light">
                    <div class="mb-4 d-inline-flex align-items-center justify-content-center bg-light rounded-circle" style="width: 120px; height: 120px;">
                        <i class="fas fa-box-open fa-3x text-muted opacity-25"></i>
                    </div>
                    <h3 class="fw-800 text-dark mb-2">Henüz Siparişiniz Yok</h3>
                    <p class="text-muted mb-4 mx-auto" style="max-width: 400px;">
                        Sipariş geçmişiniz boş görünüyor. İhtiyacınız olan ürünleri hemen keşfedin.
                    </p>
                    <a href="{{ route('urun.index') }}" class="btn btn-primary rounded-pill px-5 py-3 fw-bold text-white shadow-lg hover-scale">
                        Alışverişe Başla
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Özel CSS stilleri */
    .ls-1 { letter-spacing: 1px; }
    .x-small { font-size: 0.7rem; }
    
    .hover-shadow:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.08) !important;
        border-color: var(--primary-color) !important;
    }
    .hover-shadow {
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }
    
    .hover-scale:hover {
        transform: scale(1.05);
    }
    
    /* Buton üzerine gelince ikon hareketi */
    .group-item:hover .fa-arrow-right {
        transform: translateX(3px);
        transition: transform 0.2s;
    }
</style>
@endsection