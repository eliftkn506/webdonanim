@extends('layouts.app')

@section('title', 'Sipariş Detayı #' . $siparis->siparis_no . ' - Avantaj Bilişim')

@section('content')
<div class="container py-5">
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('siparislerim') }}" class="btn btn-light border btn-icon rounded-circle shadow-sm" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-arrow-left text-secondary"></i>
                </a>
                <div>
                    <h4 class="fw-800 text-dark mb-0">Sipariş Detayı</h4>
                    <span class="text-muted small">Sipariş No: #{{ $siparis->siparis_no ?? $siparis->id }}</span>
                </div>
                <div class="ms-auto d-none d-md-block">
                    <button onclick="window.print()" class="btn btn-light border fw-bold text-secondary rounded-pill px-4">
                        <i class="fas fa-print me-2"></i> Yazdır
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
        <div class="card-body p-4 p-lg-5">
            @php
                // Duruma göre adım belirleme (1-4 arası)
                $step = 1;
                if(in_array($siparis->durum, ['onaylandi', 'hazirlaniyor'])) $step = 2;
                if($siparis->durum == 'kargoda') $step = 3;
                if(in_array($siparis->durum, ['tamamlandi', 'teslim_edildi'])) $step = 4;
                if($siparis->durum == 'iptal' || $siparis->durum == 'iptal_edildi') $step = 0;
            @endphp

            @if($step > 0)
            <div class="steps-wrapper">
                <div class="progress" style="height: 4px;">
                    <div class="progress-bar" role="progressbar" style="width: {{ ($step - 1) * 33 }}%; background: var(--primary-gradient);" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex justify-content-between position-relative" style="top: -14px;">
                    <div class="step-item text-center {{ $step >= 1 ? 'active' : '' }}">
                        <div class="step-circle mx-auto mb-2">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="step-text fw-bold small text-dark">Sipariş Alındı</div>
                        <div class="step-date x-small text-muted">{{ $siparis->created_at->format('d.m.Y H:i') }}</div>
                    </div>
                    
                    <div class="step-item text-center {{ $step >= 2 ? 'active' : '' }}">
                        <div class="step-circle mx-auto mb-2">
                            <i class="fas fa-cog {{ $step == 2 ? 'fa-spin' : '' }}"></i>
                        </div>
                        <div class="step-text fw-bold small text-dark">Hazırlanıyor</div>
                    </div>

                    <div class="step-item text-center {{ $step >= 3 ? 'active' : '' }}">
                        <div class="step-circle mx-auto mb-2">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <div class="step-text fw-bold small text-dark">Kargoya Verildi</div>
                    </div>

                    <div class="step-item text-center {{ $step >= 4 ? 'active' : '' }}">
                        <div class="step-circle mx-auto mb-2">
                            <i class="fas fa-home"></i>
                        </div>
                        <div class="step-text fw-bold small text-dark">Teslim Edildi</div>
                        @if($step == 4)
                            <div class="step-date x-small text-muted">{{ $siparis->updated_at->format('d.m.Y') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            @else
                <div class="alert alert-danger d-flex align-items-center rounded-3 mb-0">
                    <i class="fas fa-times-circle fs-4 me-3"></i>
                    <div>
                        <h6 class="fw-bold mb-0">Sipariş İptal Edildi</h6>
                        <p class="mb-0 small opacity-75">Bu sipariş iptal edilmiştir. Detaylı bilgi için müşteri hizmetleri ile görüşebilirsiniz.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="fw-800 text-secondary mb-0"><i class="fas fa-shopping-basket me-2 text-primary"></i> Sipariş İçeriği</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 text-muted x-small uppercase fw-bold">Ürün</th>
                                    <th class="text-center py-3 text-muted x-small uppercase fw-bold">Birim Fiyat</th>
                                    <th class="text-center py-3 text-muted x-small uppercase fw-bold">Adet</th>
                                    <th class="pe-4 text-end py-3 text-muted x-small uppercase fw-bold">Toplam</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siparis->urunler as $item)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bg-light rounded-3 p-2 border" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                                @if($item->urun && $item->urun->resim)
                                                    <img src="{{ asset($item->urun->resim) }}" alt="" class="img-fluid" style="max-height: 100%;">
                                                @else
                                                    <i class="fas fa-image text-muted opacity-50"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-1 text-dark fw-bold small">{{ $item->urun->isim ?? 'Ürün Silinmiş' }}</h6>
                                                <span class="text-muted x-small">Kod: {{ $item->urun->urun_kodu ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center text-muted fw-500">{{ number_format($item->birim_fiyat, 2) }} ₺</td>
                                    <td class="text-center text-dark fw-bold">{{ $item->adet }}</td>
                                    <td class="text-end pe-4 fw-800 text-dark">{{ number_format($item->toplam_fiyat + ($item->kdv_tutari ?? 0), 2) }} ₺</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4">
                            <h6 class="fw-800 text-secondary mb-3"><i class="fas fa-truck me-2 text-primary"></i> Teslimat Bilgileri</h6>
                            <p class="mb-1 fw-bold text-dark">{{ Auth::user()->name }}</p>
                            <p class="text-muted small mb-3">{{ $siparis->kargo_adresi }}</p>
                            
                            @if($siparis->kargo_takip_no)
                                <div class="alert alert-primary bg-opacity-10 border-0 mb-0 py-2">
                                    <small class="fw-bold text-primary d-block">Kargo Takip No:</small>
                                    <span class="font-monospace text-dark">{{ $siparis->kargo_takip_no }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4">
                            <h6 class="fw-800 text-secondary mb-3"><i class="fas fa-file-invoice me-2 text-primary"></i> Fatura Bilgileri</h6>
                            @if($fatura = $siparis->fatura) {{-- Eğer fatura ilişkisi varsa --}}
                                <p class="mb-1 fw-bold text-dark">{{ $fatura->unvan ?? Auth::user()->name }}</p>
                                <p class="text-muted small mb-3">{{ $fatura->adres ?? $siparis->fatura_adresi }}</p>
                                <a href="#" class="btn btn-sm btn-light border w-100 fw-bold text-secondary">
                                    <i class="fas fa-download me-1"></i> E-Fatura İndir
                                </a>
                            @else
                                <p class="text-muted small">Bireysel Fatura</p>
                                <p class="text-muted small mb-0">{{ $siparis->fatura_adresi }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="fw-800 text-secondary mb-0">Ödeme Özeti</h6>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Ara Toplam</span>
                        <span class="fw-bold text-dark">{{ number_format($siparis->toplam_tutar, 2) }} ₺</span>
                    </div>
                    
                    @if($siparis->kdv_tutari > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">KDV</span>
                        <span class="fw-bold text-dark">{{ number_format($siparis->kdv_tutari, 2) }} ₺</span>
                    </div>
                    @endif

                    @if($siparis->indirim_tutari > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-success small fw-bold">İndirim</span>
                        <span class="fw-bold text-success">-{{ number_format($siparis->indirim_tutari, 2) }} ₺</span>
                    </div>
                    @endif

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted small">Kargo</span>
                        <span class="fw-bold text-success">Ücretsiz</span>
                    </div>

                    <hr class="border-light">

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-800 text-dark fs-5">Toplam</span>
                        <span class="fw-800 text-primary fs-4">{{ number_format($siparis->toplam_tutar + $siparis->kdv_tutari - $siparis->indirim_tutari, 2) }} ₺</span>
                    </div>
                </div>
                <div class="card-footer bg-light border-top p-3">
                    <div class="d-flex align-items-center gap-2 text-muted small justify-content-center">
                        <i class="fas fa-credit-card"></i>
                        <span>Ödeme Yöntemi: <strong>{{ ucfirst(str_replace('_', ' ', $siparis->odeme_tipi)) }}</strong></span>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 bg-primary bg-opacity-10">
                <div class="card-body p-4 text-center">
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle shadow-sm" style="width: 50px; height: 50px;">
                            <i class="fas fa-headset text-primary fs-5"></i>
                        </div>
                    </div>
                    <h6 class="fw-800 text-dark">Yardıma mı ihtiyacınız var?</h6>
                    <p class="small text-muted mb-3">Siparişinizle ilgili sorularınız için bize ulaşın.</p>
                    <a href="tel:08505333444" class="btn btn-white bg-white border fw-bold text-dark w-100 rounded-pill hover-scale">
                        0850 533 3444
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Stepper Özelleştirmeleri */
    .steps-wrapper { padding: 0 10px; }
    .step-circle {
        width: 32px; height: 32px;
        background: #e2e8f0;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: #94a3b8;
        font-size: 14px;
        transition: all 0.3s;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #e2e8f0;
        z-index: 2; position: relative;
    }
    .step-item.active .step-circle {
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 0 0 3px rgba(0, 212, 170, 0.2);
    }
    .progress { background-color: #e2e8f0; overflow: visible; border-radius: 10px; }
    .progress-bar { border-radius: 10px; transition: width 0.6s ease; }
    
    .x-small { font-size: 0.75rem; }
    .fw-500 { font-weight: 500; }
    
    .hover-scale:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
    
    /* Responsive Düzenlemeler */
    @media (max-width: 576px) {
        .step-text { display: none; } /* Mobilde metinleri gizle, sadece ikon kalsın */
    }
</style>

<script>
    // Otomatik Yenileme (Sipariş tamamlanmadıysa 30sn'de bir yenile)
    document.addEventListener('DOMContentLoaded', function() {
        @if(!in_array($siparis->durum, ['tamamlandi', 'teslim_edildi', 'iptal', 'iptal_edildi']))
            setInterval(() => {
                location.reload();
            }, 30000);
        @endif
    });
</script>
@endsection