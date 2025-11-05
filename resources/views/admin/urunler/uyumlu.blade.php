@extends('layouts.admin')

@section('title', 'Uyumlu Ürünler - Admin Panel')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">
            <i class="bx bx-link me-2"></i>Uyumlu Ürünler
        </h4>
        <a href="{{ route('admin.urunler.index') }}" class="btn btn-secondary">
            <i class="bx bx-arrow-back me-1"></i> Ürünlere Dön
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($uyumluUrunler->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bx bx-info-circle display-1 text-muted mb-3"></i>
                <h5 class="text-muted">Henüz uyumlu ürün kaydı bulunmuyor.</h5>
                <p class="text-muted">Ürünler arasında uyumluluk kuralları tanımladığınızda otomatik olarak uyumlu ürünler oluşacaktır.</p>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Toplam {{ $uyumluUrunler->total() }} Uyumluluk İlişkisi</h5>
                    <span class="badge bg-primary">Sayfa {{ $uyumluUrunler->currentPage() }} / {{ $uyumluUrunler->lastPage() }}</span>
                </div>
            </div>
            <div class="card-body">
                <div class="accordion" id="uyumluUrunAccordion">
                    @foreach($uyumluUrunler as $index => $kayit)
                        <div class="accordion-item mb-3 shadow-sm">
                            <h2 class="accordion-header" id="heading{{ $kayit->id }}">
                                <button class="accordion-button collapsed" type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#collapse{{ $kayit->id }}" 
                                        aria-expanded="false" 
                                        aria-controls="collapse{{ $kayit->id }}">
                                    <div class="d-flex align-items-center w-100">
                                        <i class="bx bx-box me-2 text-primary"></i>
                                        <span class="fw-bold me-2">{{ $kayit->urun->urun_ad }}</span>
                                        <i class="bx bx-right-arrow-alt mx-2 text-success"></i>
                                        <span class="fw-bold text-success">{{ $kayit->uyumluUrun->urun_ad }}</span>
                                        
                                        @if($kayit->urun->varyasyonlar->count() > 0 || $kayit->uyumluUrun->varyasyonlar->count() > 0)
                                            <span class="badge bg-info ms-auto me-2">
                                                <i class="bx bx-layer"></i> Varyasyonlu
                                            </span>
                                        @endif
                                    </div>
                                </button>
                            </h2>
                            <div id="collapse{{ $kayit->id }}" 
                                 class="accordion-collapse collapse" 
                                 aria-labelledby="heading{{ $kayit->id }}" 
                                 data-bs-parent="#uyumluUrunAccordion">
                                <div class="accordion-body">
                                    <div class="row">
                                        <!-- Sol Taraf: Ana Ürün -->
                                        <div class="col-md-6">
                                            <div class="card bg-light h-100">
                                                <div class="card-header bg-primary text-white">
                                                    <h6 class="mb-0">
                                                        <i class="bx bx-box me-1"></i>
                                                        {{ $kayit->urun->urun_ad }}
                                                    </h6>
                                                    <small>{{ $kayit->urun->altKategori->alt_kategori_ad ?? '-' }}</small>
                                                </div>
                                                <div class="card-body">
                                                    <h6 class="text-primary mb-3">
                                                        <i class="bx bx-list-check me-1"></i>Ana Ürün Kriterleri:
                                                    </h6>
                                                    
                                                    @if($kayit->urun->urunKriterDegerleri->isNotEmpty())
                                                        <ul class="list-group list-group-flush mb-3">
                                                            @foreach($kayit->urun->urunKriterDegerleri as $kriter)
                                                                <li class="list-group-item bg-transparent px-0">
                                                                    <strong class="text-primary">
                                                                        {{ $kriter->kriter->kriter_ad ?? 'Bilinmeyen' }}:
                                                                    </strong>
                                                                    <span class="badge bg-primary-subtle text-primary">
                                                                        {{ $kriter->kriterDeger->deger ?? '-' }}
                                                                    </span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <p class="text-muted small">Ana ürün kriter bilgisi yok</p>
                                                    @endif

                                                    @if($kayit->urun->varyasyonlar->count() > 0)
                                                        <h6 class="text-info mt-4 mb-3">
                                                            <i class="bx bx-layer me-1"></i>Varyasyonlar ({{ $kayit->urun->varyasyonlar->count() }}):
                                                        </h6>
                                                        @foreach($kayit->urun->varyasyonlar as $vIndex => $varyasyon)
                                                            <div class="card mb-2 border-info">
                                                                <div class="card-body p-2">
                                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                                        <strong class="small">Varyasyon {{ $vIndex + 1 }}</strong>
                                                                        <div>
                                                                            <span class="badge bg-success-subtle text-success small">
                                                                                ₺{{ number_format($varyasyon->fiyat, 2) }}
                                                                            </span>
                                                                            <span class="badge bg-secondary-subtle text-secondary small">
                                                                                Stok: {{ $varyasyon->stok }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    @php
                                                                        $varyasyonKriterler = \App\Models\UrunVaryasyonKriterDegeri::where('urun_varyasyon_id', $varyasyon->id)
                                                                            ->with(['kriter', 'kriterDeger'])
                                                                            ->get();
                                                                    @endphp
                                                                    @if($varyasyonKriterler->isNotEmpty())
                                                                        <div class="small">
                                                                            @foreach($varyasyonKriterler as $vKriter)
                                                                                <span class="badge bg-info-subtle text-info me-1">
                                                                                    {{ $vKriter->kriter->kriter_ad ?? '' }}: 
                                                                                    {{ $vKriter->kriterDeger->deger ?? '' }}
                                                                                </span>
                                                                            @endforeach
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Sağ Taraf: Uyumlu Ürün -->
                                        <div class="col-md-6">
                                            <div class="card bg-light h-100">
                                                <div class="card-header bg-success text-white">
                                                    <h6 class="mb-0">
                                                        <i class="bx bx-check-circle me-1"></i>
                                                        {{ $kayit->uyumluUrun->urun_ad }}
                                                    </h6>
                                                    <small>{{ $kayit->uyumluUrun->altKategori->alt_kategori_ad ?? '-' }}</small>
                                                </div>
                                                <div class="card-body">
                                                    <h6 class="text-success mb-3">
                                                        <i class="bx bx-list-check me-1"></i>Uyumlu Ürün Kriterleri:
                                                    </h6>
                                                    
                                                    @if($kayit->uyumluUrun->urunKriterDegerleri->isNotEmpty())
                                                        <ul class="list-group list-group-flush mb-3">
                                                            @foreach($kayit->uyumluUrun->urunKriterDegerleri as $kriter)
                                                                <li class="list-group-item bg-transparent px-0">
                                                                    <strong class="text-success">
                                                                        {{ $kriter->kriter->kriter_ad ?? 'Bilinmeyen' }}:
                                                                    </strong>
                                                                    <span class="badge bg-success-subtle text-success">
                                                                        {{ $kriter->kriterDeger->deger ?? '-' }}
                                                                    </span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <p class="text-muted small">Uyumlu ürün kriter bilgisi yok</p>
                                                    @endif

                                                    @if($kayit->uyumluUrun->varyasyonlar->count() > 0)
                                                        <h6 class="text-info mt-4 mb-3">
                                                            <i class="bx bx-layer me-1"></i>Varyasyonlar ({{ $kayit->uyumluUrun->varyasyonlar->count() }}):
                                                        </h6>
                                                        @foreach($kayit->uyumluUrun->varyasyonlar as $vIndex => $varyasyon)
                                                            <div class="card mb-2 border-info">
                                                                <div class="card-body p-2">
                                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                                        <strong class="small">Varyasyon {{ $vIndex + 1 }}</strong>
                                                                        <div>
                                                                            <span class="badge bg-success-subtle text-success small">
                                                                                ₺{{ number_format($varyasyon->fiyat, 2) }}
                                                                            </span>
                                                                            <span class="badge bg-secondary-subtle text-secondary small">
                                                                                Stok: {{ $varyasyon->stok }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    @php
                                                                        $varyasyonKriterler = \App\Models\UrunVaryasyonKriterDegeri::where('urun_varyasyon_id', $varyasyon->id)
                                                                            ->with(['kriter', 'kriterDeger'])
                                                                            ->get();
                                                                    @endphp
                                                                    @if($varyasyonKriterler->isNotEmpty())
                                                                        <div class="small">
                                                                            @foreach($varyasyonKriterler as $vKriter)
                                                                                <span class="badge bg-info-subtle text-info me-1">
                                                                                    {{ $vKriter->kriter->kriter_ad ?? '' }}: 
                                                                                    {{ $vKriter->kriterDeger->deger ?? '' }}
                                                                                </span>
                                                                            @endforeach
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-4 d-flex justify-content-center">
            {{ $uyumluUrunler->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<style>
.accordion-button {
    background-color: #f8f9fa;
    color: #2C3E50;
    font-weight: 500;
}

.accordion-button:not(.collapsed) {
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    color: #fff;
}

.accordion-button:not(.collapsed)::after {
    filter: brightness(0) invert(1);
}

.accordion-item {
    border: 1px solid #e0e0e0;
    border-radius: 0.5rem;
    overflow: hidden;
}

.list-group-item {
    border: none;
    padding: 0.5rem 0;
}

.card-header.bg-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.card-header.bg-success {
    background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%) !important;
}

.badge {
    font-weight: 500;
}
</style>
@endsection