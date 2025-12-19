@extends('layouts.admin')

@section('title', 'Uyumlu Ürünler')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold py-3 mb-0">
                <span class="text-muted fw-light">Ürünler /</span> Uyumlu Ürünler
            </h4>
            <p class="text-muted mb-0">Tanımlı kurallara göre otomatik eşleşen ürünler.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.uyumluluk.index') }}" class="btn btn-label-primary">
                <i class="bx bx-cog me-1"></i> Kuralları Yönet
            </a>
            <a href="{{ route('admin.urunler.index') }}" class="btn btn-label-secondary">
                <i class="bx bx-arrow-back me-1"></i> Geri Dön
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="bx bx-check-circle me-2 fs-4"></i>
                {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($uyumluUrunler->isEmpty())
        <div class="card h-100">
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <span class="badge bg-label-primary p-4 rounded-circle">
                        <i class="bx bx-link-alt display-4"></i>
                    </span>
                </div>
                <h4 class="mb-2">Henüz uyumlu ürün bulunamadı!</h4>
                <p class="text-muted mb-4">Ürünleriniz arasında otomatik eşleşme sağlayacak kurallar tanımlayarak başlayın.</p>
                <a href="{{ route('admin.uyumluluk.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Kural Oluştur
                </a>
            </div>
        </div>
    @else
        <div class="card mb-4">
            <div class="card-body d-flex justify-content-between align-items-center p-3">
                <div class="d-flex align-items-center">
                    <i class="bx bx-data fs-3 text-primary me-3"></i>
                    <div>
                        <h6 class="mb-0">Toplam Eşleşme</h6>
                        <small class="text-muted">{{ $uyumluUrunler->total() }} adet kayıt bulundu.</small>
                    </div>
                </div>
                <span class="badge bg-label-secondary">
                    Sayfa {{ $uyumluUrunler->currentPage() }} / {{ $uyumluUrunler->lastPage() }}
                </span>
            </div>
        </div>

        <div class="row g-4">
            @foreach($uyumluUrunler as $kayit)
                <div class="col-12 col-xl-6">
                    <div class="card h-100 border-0 shadow-sm position-relative">
                        <div class="position-absolute top-50 start-50 translate-middle bg-white rounded-circle shadow-sm p-2 z-1 d-none d-md-block border">
                            <i class="bx bx-transfer-alt text-primary fs-4"></i>
                        </div>

                        <div class="card-body p-0">
                            <div class="row g-0 h-100">
                                
                                <div class="col-md-6 border-end p-4 bg-label-secondary bg-opacity-10">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <span class="badge bg-label-primary">Ana Ürün</span>
                                        <a href="{{ route('admin.urunler.edit', $kayit->urun->id) }}" class="text-muted" target="_blank">
                                            <i class="bx bx-link-external"></i>
                                        </a>
                                    </div>
                                    
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar avatar-lg me-3">
                                            @if($kayit->urun->resim_url)
                                                <img src="{{ asset($kayit->urun->resim_url) }}" class="rounded">
                                            @else
                                                <span class="avatar-initial rounded bg-label-secondary"><i class="bx bx-box"></i></span>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-0 text-truncate" style="max-width: 150px;" title="{{ $kayit->urun->urun_ad }}">
                                                {{ $kayit->urun->urun_ad }}
                                            </h6>
                                            <small class="text-muted">{{ $kayit->urun->altKategori->alt_kategori_ad ?? '-' }}</small>
                                        </div>
                                    </div>

                                    <div class="bg-white rounded p-2 border">
                                        <small class="d-block text-uppercase fw-bold text-muted mb-1" style="font-size: 0.7rem;">Eşleşen Kriterler</small>
                                        @forelse($kayit->urun->urunKriterDegerleri->take(3) as $kriter)
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="small text-muted">{{ $kriter->kriter->kriter_ad }}:</span>
                                                <span class="small fw-semibold text-dark">{{ $kriter->kriterDeger->deger }}</span>
                                            </div>
                                        @empty
                                            <small class="text-muted">Kriter verisi yok</small>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="col-md-6 p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <span class="badge bg-label-success">Uyumlu</span>
                                        <a href="{{ route('admin.urunler.edit', $kayit->uyumluUrun->id) }}" class="text-muted" target="_blank">
                                            <i class="bx bx-link-external"></i>
                                        </a>
                                    </div>

                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar avatar-lg me-3">
                                            @if($kayit->uyumluUrun->resim_url)
                                                <img src="{{ asset($kayit->uyumluUrun->resim_url) }}" class="rounded">
                                            @else
                                                <span class="avatar-initial rounded bg-label-secondary"><i class="bx bx-check-circle"></i></span>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-0 text-truncate" style="max-width: 150px;" title="{{ $kayit->uyumluUrun->urun_ad }}">
                                                {{ $kayit->uyumluUrun->urun_ad }}
                                            </h6>
                                            <small class="text-muted">{{ $kayit->uyumluUrun->altKategori->alt_kategori_ad ?? '-' }}</small>
                                        </div>
                                    </div>

                                    <div class="bg-label-success bg-opacity-10 rounded p-2 border border-success border-opacity-25">
                                        <small class="d-block text-uppercase fw-bold text-success mb-1" style="font-size: 0.7rem;">Eşleşen Kriterler</small>
                                        @forelse($kayit->uyumluUrun->urunKriterDegerleri->take(3) as $kriter)
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="small text-muted">{{ $kriter->kriter->kriter_ad }}:</span>
                                                <span class="small fw-semibold text-dark">{{ $kriter->kriterDeger->deger }}</span>
                                            </div>
                                        @empty
                                            <small class="text-muted">Kriter verisi yok</small>
                                        @endforelse
                                    </div>
                                </div>

                            </div>
                        </div>
                        
                        @if($kayit->urun->varyasyonlar->count() > 0 || $kayit->uyumluUrun->varyasyonlar->count() > 0)
                            <div class="card-footer bg-light py-2 px-4 border-top">
                                <small class="text-muted">
                                    <i class="bx bx-layer me-1"></i> Bu eşleşmede varyasyonlar da dikkate alınmıştır.
                                </small>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $uyumluUrunler->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection