@extends('layouts.admin')

@section('title', 'Bayi Detayı')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold py-3 mb-0">
                <span class="text-muted fw-light">Bayi Yönetimi /</span> Detay
            </h4>
        </div>
        <a href="{{ url()->previous() }}" class="btn btn-label-secondary">
            <i class="bx bx-arrow-back me-1"></i> Geri Dön
        </a>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Başvuru Bilgileri #{{ $basvuru->id }}</h5>
                    @php
                        $durumBadge = match($basvuru->durum) {
                            'onaylandi' => 'bg-success',
                            'reddedildi' => 'bg-danger',
                            'beklemede' => 'bg-warning',
                            default => 'bg-secondary'
                        };
                        $durumText = match($basvuru->durum) {
                            'onaylandi' => 'Onaylandı',
                            'reddedildi' => 'Reddedildi',
                            'beklemede' => 'Beklemede',
                            default => $basvuru->durum
                        };
                    @endphp
                    <span class="badge {{ $durumBadge }}">{{ $durumText }}</span>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="content-header mb-3">
                                <h6 class="mb-0 text-primary"><i class="bx bx-building me-2"></i>Firma Bilgileri</h6>
                            </div>
                            <dl class="row mb-0">
                                <dt class="col-sm-4 text-muted">Firma Adı:</dt>
                                <dd class="col-sm-8 fw-semibold">{{ $basvuru->firma_adi ?? '-' }}</dd>

                                <dt class="col-sm-4 text-muted">Vergi Dairesi:</dt>
                                <dd class="col-sm-8">{{ $basvuru->vergi_dairesi ?? '-' }}</dd>

                                <dt class="col-sm-4 text-muted">Vergi No:</dt>
                                <dd class="col-sm-8">{{ $basvuru->vergi_no ?? '-' }}</dd>

                                <dt class="col-sm-4 text-muted">Adres:</dt>
                                <dd class="col-sm-8">{{ $basvuru->adres ?? '-' }}</dd>
                            </dl>
                        </div>

                        <div class="col-md-6">
                            <div class="content-header mb-3">
                                <h6 class="mb-0 text-primary"><i class="bx bx-user me-2"></i>Yetkili Bilgileri</h6>
                            </div>
                            <dl class="row mb-0">
                                <dt class="col-sm-4 text-muted">Ad Soyad:</dt>
                                <dd class="col-sm-8 fw-semibold">{{ $basvuru->yetkili_ad }} {{ $basvuru->yetkili_soyad }}</dd>

                                <dt class="col-sm-4 text-muted">E-posta:</dt>
                                <dd class="col-sm-8">
                                    <a href="mailto:{{ $basvuru->email }}">{{ $basvuru->email }}</a>
                                </dd>

                                <dt class="col-sm-4 text-muted">Telefon:</dt>
                                <dd class="col-sm-8">
                                    <a href="tel:{{ $basvuru->telefon }}">{{ $basvuru->telefon ?? '-' }}</a>
                                </dd>
                            </dl>
                        </div>
                    </div>

                    @if($basvuru->mesaj)
                        <hr class="my-4">
                        <div class="alert alert-info d-flex align-items-start mb-0" role="alert">
                            <i class="bx bx-chat me-2 mt-1"></i>
                            <div>
                                <h6 class="alert-heading mb-1">Başvuru Mesajı:</h6>
                                <p class="mb-0">{{ $basvuru->mesaj }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Süreç Bilgisi</h6>
                </div>
                <div class="card-body">
                    <ul class="timeline timeline-dashed mt-3">
                        <li class="timeline-item timeline-item-transparent pb-3 border-left-dashed">
                            <span class="timeline-point timeline-point-primary"></span>
                            <div class="timeline-event">
                                <div class="timeline-header mb-1">
                                    <h6 class="mb-0">Başvuru Oluşturuldu</h6>
                                    <small class="text-muted">{{ $basvuru->created_at->format('d.m.Y H:i') }}</small>
                                </div>
                            </div>
                        </li>
                        <li class="timeline-item timeline-item-transparent border-left-dashed">
                            @if($basvuru->durum == 'onaylandi')
                                <span class="timeline-point timeline-point-success"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-1">
                                        <h6 class="mb-0">Onaylandı</h6>
                                        <small class="text-muted">{{ $basvuru->updated_at->format('d.m.Y H:i') }}</small>
                                    </div>
                                    <p class="mb-0 text-success">Bayi hesabı aktifleştirildi.</p>
                                </div>
                            @elseif($basvuru->durum == 'reddedildi')
                                <span class="timeline-point timeline-point-danger"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-1">
                                        <h6 class="mb-0">Reddedildi</h6>
                                        <small class="text-muted">{{ $basvuru->updated_at->format('d.m.Y H:i') }}</small>
                                    </div>
                                    <p class="mb-0 text-danger">Başvuru olumsuz sonuçlandı.</p>
                                </div>
                            @else
                                <span class="timeline-point timeline-point-warning"></span>
                                <div class="timeline-event">
                                    <div class="timeline-header mb-1">
                                        <h6 class="mb-0">İnceleme Bekliyor</h6>
                                    </div>
                                    <p class="mb-0 text-muted">Yetkili onayı bekleniyor.</p>
                                </div>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>

            @if($basvuru->durum == 'beklemede')
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Aksiyon Al</h6>
                    </div>
                    <div class="card-body d-flex flex-column gap-2">
                        <form action="{{ route('admin.bayiler.approve', $basvuru->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Onaylamak istediğinize emin misiniz?')">
                                <i class="bx bx-check me-1"></i> Başvuruyu Onayla
                            </button>
                        </form>

                        <form action="{{ route('admin.bayiler.reject', $basvuru->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-label-danger w-100" onclick="return confirm('Reddetmek istediğinize emin misiniz?')">
                                <i class="bx bx-x me-1"></i> Başvuruyu Reddet
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection