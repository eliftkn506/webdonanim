@extends('layouts.admin')

@section('title', 'Bayi Detayı - Admin Panel')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Bayi Başvuru Detayı #{{ $basvuru->id }}</h5>
        <a href="{{ route('admin.bayiler.basvurular') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Geri Dön
        </a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6 class="text-primary mb-3">Firma Bilgileri</h6>
                <table class="table table-bordered">
                    <tr>
                        <th width="40%">Firma Adı:</th>
                        <td>{{ $basvuru->firma_adi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Vergi Dairesi:</th>
                        <td>{{ $basvuru->vergi_dairesi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Vergi No:</th>
                        <td>{{ $basvuru->vergi_no ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Adres:</th>
                        <td>{{ $basvuru->adres ?? '-' }}</td>
                    </tr>
                </table>
            </div>
            
            <div class="col-md-6">
                <h6 class="text-primary mb-3">Yetkili Bilgileri</h6>
                <table class="table table-bordered">
                    <tr>
                        <th width="40%">Ad Soyad:</th>
                        <td>{{ $basvuru->yetkili_ad }} {{ $basvuru->yetkili_soyad }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{{ $basvuru->email }}</td>
                    </tr>
                    <tr>
                        <th>Telefon:</th>
                        <td>{{ $basvuru->telefon ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Durum:</th>
                        <td>
                            @if($basvuru->durum == 'onaylandi')
                                <span class="badge bg-success">Onaylandı</span>
                            @elseif($basvuru->durum == 'reddedildi')
                                <span class="badge bg-danger">Reddedildi</span>
                            @else
                                <span class="badge bg-warning">Beklemede</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        @if($basvuru->mesaj)
        <div class="row mt-3">
            <div class="col-12">
                <h6 class="text-primary mb-3">Mesaj</h6>
                <div class="alert alert-info">
                    {{ $basvuru->mesaj }}
                </div>
            </div>
        </div>
        @endif

        <div class="row mt-3">
            <div class="col-12">
                <h6 class="text-primary mb-3">Tarih Bilgileri</h6>
                <table class="table table-bordered">
                    <tr>
                        <th width="20%">Başvuru Tarihi:</th>
                        <td>{{ $basvuru->created_at->format('d.m.Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Son Güncelleme:</th>
                        <td>{{ $basvuru->updated_at->format('d.m.Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @if($basvuru->durum == 'beklemede')
        <div class="row mt-4">
            <div class="col-12 text-center">
                <form method="POST" 
                      action="{{ route('admin.bayiler.approve', $basvuru->id) }}" 
                      class="d-inline"
                      onsubmit="return confirm('Bu başvuruyu onaylamak istediğinizden emin misiniz?')">
                    @csrf
                    <button type="submit" class="btn btn-success btn-lg me-2">
                        <i class="fas fa-check me-2"></i> Başvuruyu Onayla
                    </button>
                </form>
                
                <form method="POST" 
                      action="{{ route('admin.bayiler.reject', $basvuru->id) }}" 
                      class="d-inline"
                      onsubmit="return confirm('Bu başvuruyu reddetmek istediğinizden emin misiniz?')">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-lg">
                        <i class="fas fa-times me-2"></i> Başvuruyu Reddet
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection