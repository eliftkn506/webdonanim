@extends('layouts.admin')

@section('title', 'Yeni Ürün Ekle')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Ürünler /</span> Yeni Ürün Ekle
    </h4>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Ürün Bilgileri</h5>
            <a href="{{ route('admin.urunler.index') }}" class="btn btn-sm btn-secondary">
                <i class="bx bx-arrow-back me-1"></i> Geri Dön
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.urunler.store') }}" method="POST" id="urunForm">

                @csrf

                <!-- Temel Bilgiler -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Alt Kategori <span class="text-danger">*</span></label>
                        <select name="alt_kategori_id" id="alt_kategori" class="form-select @error('alt_kategori_id') is-invalid @enderror" required>
                            <option value="">Seçiniz...</option>
                            @foreach($altkategoriler as $alt)
                                <option value="{{ $alt->id }}" {{ old('alt_kategori_id') == $alt->id ? 'selected' : '' }}>
                                    {{ $alt->kategori->kategori_ad ?? '' }} → {{ $alt->alt_kategori_ad }}
                                </option>
                            @endforeach
                        </select>
                        @error('alt_kategori_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Ürün Adı <span class="text-danger">*</span></label>
                        <input type="text" name="urun_ad" class="form-control @error('urun_ad') is-invalid @enderror" value="{{ old('urun_ad') }}" required>
                        @error('urun_ad')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Marka <span class="text-danger">*</span></label>
                        <input type="text" name="marka" class="form-control @error('marka') is-invalid @enderror" value="{{ old('marka') }}" required>
                        @error('marka')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Model <span class="text-danger">*</span></label>
                        <input type="text" name="model" class="form-control @error('model') is-invalid @enderror" value="{{ old('model') }}" required>
                        @error('model')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Barkod No</label>
                        <input type="text" name="barkod_no" class="form-control" value="{{ old('barkod_no') }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Stok <span class="text-danger">*</span></label>
                        <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror" value="{{ old('stok', 0) }}" required>
                        @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Resim URL</label>
                        <input type="text" name="resim_url" class="form-control" value="{{ old('resim_url') }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Açıklama</label>
                    <textarea name="aciklama" rows="4" class="form-control">{{ old('aciklama') }}</textarea>
                </div>

                <!-- Kriterler -->
                <div id="kriterler-container" class="mb-4"></div>

                <!-- Varyasyonlar -->
                <div class="card bg-light mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="bx bx-layer me-2"></i>Varyasyonlar</h6>
                        <button type="button" id="ekle-varyasyon" class="btn btn-sm btn-primary">
                            <i class="bx bx-plus me-1"></i> Varyasyon Ekle
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="varyasyonlar-container">
                            <p class="text-muted text-center mb-0">Önce alt kategori seçin, sonra varyasyon ekleyebilirsiniz.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success me-2">
                        <i class="bx bx-save me-1"></i> Kaydet
                    </button>
                    <a href="{{ route('admin.urunler.index') }}" class="btn btn-secondary">
                        <i class="bx bx-x me-1"></i> İptal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let kriterlerData = [];
const kriterContainer = document.getElementById('kriterler-container');
const varyasyonContainer = document.getElementById('varyasyonlar-container');

// Alt kategori değiştiğinde kriterleri yükle
document.getElementById('alt_kategori').addEventListener('change', function() {
    const altKategoriId = this.value;
    kriterContainer.innerHTML = '';
    varyasyonContainer.innerHTML = '<p class="text-muted text-center mb-0">Önce alt kategori seçin, sonra varyasyon ekleyebilirsiniz.</p>';
    kriterlerData = [];

    if(altKategoriId) {
        fetch(`/admin/urunler/kriterler/${altKategoriId}`)
            .then(res => res.json())
            .then(data => {
                kriterlerData = data;
                
                if(data.length > 0) {
                    const kriterDiv = document.createElement('div');
                    kriterDiv.className = 'card bg-light mb-3';
                    kriterDiv.innerHTML = '<div class="card-header"><h6 class="mb-0"><i class="bx bx-list-check me-2"></i>Ana Ürün Kriterleri</h6></div><div class="card-body"><div class="row" id="kriter-list"></div></div>';
                    kriterContainer.appendChild(kriterDiv);
                    
                    const kriterList = document.getElementById('kriter-list');
                    data.forEach(kriter => {
                        const options = kriter.degerler.map(d => 
                            `<option value="${d.id}">${d.deger}</option>`
                        ).join('');
                        
                        const col = document.createElement('div');
                        col.className = 'col-md-6 mb-3';
                        col.innerHTML = `
                            <label class="form-label fw-semibold">${kriter.kriter_ad}</label>
                            <select name="kriter_degerleri[${kriter.id}]" class="form-select">
                                <option value="">Seçiniz...</option>
                                ${options}
                            </select>
                        `;
                        kriterList.appendChild(col);
                    });
                    
                    varyasyonContainer.innerHTML = '<p class="text-muted text-center mb-0">Varyasyon eklemek için yukarıdaki butonu kullanın.</p>';
                } else {
                    kriterContainer.innerHTML = '<div class="alert alert-info"><i class="bx bx-info-circle me-2"></i>Bu alt kategori için kriter tanımlanmamış.</div>';
                }
            })
            .catch(err => {
                console.error('Kriter yükleme hatası:', err);
                alert('Kriterler yüklenirken bir hata oluştu.');
            });
    }
});

// Varyasyon ekleme
let varyasyonSayac = 0;
document.getElementById('ekle-varyasyon').addEventListener('click', function() {
    if(kriterlerData.length === 0) {
        alert('Önce alt kategori seçip kriterleri yükleyin.');
        return;
    }

    if(varyasyonContainer.querySelector('p')) {
        varyasyonContainer.innerHTML = '';
    }

    varyasyonSayac++;
    const varyasyonDiv = document.createElement('div');
    varyasyonDiv.className = 'card mb-3';
    varyasyonDiv.innerHTML = `
        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
            <strong>Varyasyon ${varyasyonSayac}</strong>
            <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('.card').remove()">
                <i class="bx bx-trash"></i> Sil
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                ${kriterlerData.map(kriter => {
                    const options = kriter.degerler.map(d => 
                        `<option value="${d.id}">${d.deger}</option>`
                    ).join('');
                    return `
                        <div class="col-md-4 mb-3">
                            <label class="form-label">${kriter.kriter_ad}</label>
                            <select name="varyasyonlar[${varyasyonSayac}][kriter_degerleri][${kriter.id}]" class="form-select" required>
                                <option value="">Seçiniz...</option>
                                ${options}
                            </select>
                        </div>
                    `;
                }).join('')}
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Stok <span class="text-danger">*</span></label>
                    <input type="number" name="varyasyonlar[${varyasyonSayac}][stok]" class="form-control" value="0" required>
                </div>
            </div>
        </div>
    `;
    varyasyonContainer.appendChild(varyasyonDiv);
});
</script>

<style>
.card-header.bg-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}
</style>
@endsection
