@extends('layouts.admin')

@section('title', 'Kupon İstatistikleri - ' . $kupon->kupon_kodu)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                <i class='bx bx-bar-chart me-2'></i>Kupon İstatistikleri
            </h4>
            <p class="text-muted mb-0">{{ $kupon->kupon_kodu }} - {{ $kupon->baslik }}</p>
        </div>
        <a href="{{ route('admin.kuponlar.index') }}" class="btn btn-outline-secondary">
            <i class='bx bx-arrow-back me-1'></i> Geri Dön
        </a>
    </div>

    <!-- Özet Kartlar -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title text-nowrap mb-2">{{ $istatistikler['toplam_kullanim'] }}</h3>
                            <span class="badge bg-label-primary">Toplam Kullanım</span>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class='bx bx-cart fs-4'></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title text-nowrap mb-2">₺{{ number_format($istatistikler['toplam_indirim'], 2) }}</h3>
                            <span class="badge bg-label-success">Toplam İndirim</span>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class='bx bx-dollar fs-4'></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title text-nowrap mb-2">{{ $istatistikler['toplam_kullanan'] }}</h3>
                            <span class="badge bg-label-info">Kullanan Kişi</span>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class='bx bx-user fs-4'></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 class="card-title text-nowrap mb-2">₺{{ number_format($istatistikler['ortalama_siparis'], 2) }}</h3>
                            <span class="badge bg-label-warning">Ort. Sipariş</span>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class='bx bx-trending-up fs-4'></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kupon Detayları -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header border-bottom">
                    <h5 class="card-title mb-0">
                        <i class='bx bx-info-circle me-1'></i> Kupon Detayları
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td class="ps-0 fw-semibold">Kupon Kodu:</td>
                            <td class="text-end">
                                <span class="badge bg-primary font-monospace">{{ $kupon->kupon_kodu }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-0 fw-semibold">Kupon Türü:</td>
                            <td class="text-end">
                                @php
                                    $turText = match($kupon->kupon_turu) {
                                        'genel' => 'Genel',
                                        'kullanici_ozel' => 'Kullanıcıya Özel',
                                        'kural_bazli' => 'Kural Bazlı',
                                        default => 'Diğer'
                                    };
                                @endphp
                                {{ $turText }}
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-0 fw-semibold">İndirim:</td>
                            <td class="text-end text-success fw-bold">
                                {{ $kupon->indirim_tipi == 'yuzde' ? '%' : '₺' }}{{ number_format($kupon->indirim_miktari, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="ps-0 fw-semibold">Min. Sepet Tutarı:</td>
                            <td class="text-end">₺{{ number_format($kupon->minimum_tutar, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="ps-0 fw-semibold">Kullanım Limiti:</td>
                            <td class="text-end">{{ $kupon->kullanim_limiti ?? 'Sınırsız' }}</td>
                        </tr>
                        <tr>
                            <td class="ps-0 fw-semibold">Kişi Başı Limit:</td>
                            <td class="text-end">{{ $kupon->kullanici_basina_limit }}</td>
                        </tr>
                        <tr>
                            <td class="ps-0 fw-semibold">Başlangıç:</td>
                            <td class="text-end">{{ $kupon->baslangic_tarihi->format('d.m.Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="ps-0 fw-semibold">Bitiş:</td>
                            <td class="text-end">{{ $kupon->bitis_tarihi->format('d.m.Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="ps-0 fw-semibold">Durum:</td>
                            <td class="text-end">
                                @if($kupon->isActive())
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Pasif</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header border-bottom">
                    <h5 class="card-title mb-0">
                        <i class='bx bx-pie-chart me-1'></i> Performans Metrikleri
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Kullanım Oranı</span>
                            @if($kupon->kullanim_limiti)
                                @php
                                    $kullanimYuzde = ($kupon->kullanilan_adet / $kupon->kullanim_limiti) * 100;
                                @endphp
                                <span class="fw-bold">{{ number_format($kullanimYuzde, 1) }}%</span>
                            @else
                                <span class="fw-bold">∞</span>
                            @endif
                        </div>
                        @if($kupon->kullanim_limiti)
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar {{ $kullanimYuzde >= 100 ? 'bg-danger' : ($kullanimYuzde > 75 ? 'bg-warning' : 'bg-success') }}" 
                                     style="width: {{ min($kullanimYuzde, 100) }}%"></div>
                            </div>
                        @endif
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Ortalama İndirim Tutarı:</span>
                            <span class="fw-bold text-success">₺{{ number_format($istatistikler['ortalama_indirim'], 2) }}</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Toplam Sipariş Hacmi:</span>
                            <span class="fw-bold">₺{{ number_format($istatistikler['ortalama_siparis'] * $istatistikler['toplam_kullanim'], 2) }}</span>
                        </div>
                    </div>

                    @if($kupon->kupon_turu !== 'genel')
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Atanan Kullanıcı:</span>
                                <span class="fw-bold">{{ $kupon->kullanicilar->count() }}</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Dönüşüm Oranı:</span>
                                @php
                                    $donusumOrani = $kupon->kullanicilar->count() > 0 
                                        ? ($istatistikler['toplam_kullanan'] / $kupon->kullanicilar->count()) * 100 
                                        : 0;
                                @endphp
                                <span class="fw-bold">{{ number_format($donusumOrani, 1) }}%</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-info" style="width: {{ min($donusumOrani, 100) }}%"></div>
                            </div>
                        </div>
                    @endif

                    @if($kupon->son_atama_tarihi)
                        <div class="alert alert-info mb-0">
                            <small>
                                <i class='bx bx-time me-1'></i>
                                Son Otomatik Atama: {{ $kupon->son_atama_tarihi->diffForHumans() }}
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Kullanım Geçmişi -->
    <div class="card">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class='bx bx-history me-1'></i> Kullanım Geçmişi ({{ $kullanimlar->total() }})
            </h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Kullanıcı</th>
                        <th>Sipariş Tutarı</th>
                        <th>İndirim Tutarı</th>
                        <th>Ödenen Tutar</th>
                        <th>Sipariş No</th>
                        <th>IP Adresi</th>
                        <th>Tarih</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kullanimlar as $kullanim)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <span class="avatar-initial rounded bg-label-primary">
                                            {{ substr($kullanim->user->name, 0, 2) }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="fw-semibold d-block">{{ $kullanim->user->name }}</span>
                                        <small class="text-muted">{{ $kullanim->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-semibold">₺{{ number_format($kullanim->siparis_tutari, 2) }}</td>
                            <td class="text-success fw-semibold">-₺{{ number_format($kullanim->indirim_tutari, 2) }}</td>
                            <td class="fw-bold">₺{{ number_format($kullanim->siparis_tutari - $kullanim->indirim_tutari, 2) }}</td>
                            <td>
                                @if($kullanim->siparis_id)
                                    <a href="{{ route('admin.siparisler.show', $kullanim->siparis_id) }}" class="text-primary">
                                        #{{ $kullanim->siparis_id }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td><code>{{ $kullanim->ip_adresi ?? '-' }}</code></td>
                            <td>
                                <small>{{ $kullanim->created_at->format('d.m.Y H:i') }}</small>
                                <br>
                                <small class="text-muted">{{ $kullanim->created_at->diffForHumans() }}</small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class='bx bx-info-circle me-1'></i>
                                    Henüz kullanım geçmişi bulunmuyor.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kullanimlar->hasPages())
            <div class="card-footer border-top">
                {{ $kullanimlar->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection