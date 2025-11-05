@extends('layouts.admin')

@section('title', 'Ürün Düzenle')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Ürünler /</span> Ürün Düzenle
    </h4>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Ürün Bilgilerini Düzenle</h5>
            <a href="{{ route('admin.urunler.index') }}" class="btn btn-sm btn-secondary">
                <i class="bx bx-arrow-back me-1"></i> Geri Dön
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.urunler.update', $urun->id) }}" method="POST" id="urunForm">
                @csrf
                @method('PUT')

                <!-- Temel Bilgiler -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Alt Kategori <span class="text-danger">*</span></label>
                        <select name="alt_kategori_id" id="alt_kategori" class="form-select @error('alt_kategori_id') is-invalid @enderror" required>
                            <option value="">Seçiniz...</option>
                            @foreach($altkategoriler as $alt)
                                <option value="{{ $alt->id }}" {{ $urun->alt_kategori_id == $alt->id ? 'selected' : '' }}>
                                    {{ $alt->kategori->kategori_ad ?? '' }} → {{ $alt->alt_kategori_ad }}
                                </option>
                            @endforeach
                        </select>
                        @error('alt_kategori_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">Ürün Adı <span class="text-danger">*</span></label>
                        <input type="text" name="urun_ad" class="form-control @error('urun_ad') is-invalid @enderror" value="{{ old('urun_ad', $urun->urun_ad) }}" required>
                        @error('urun_ad')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Marka <span class="text-danger">*</span></label>
                        <input type="text" name="marka" class="form-control @error('marka') is-invalid @enderror" value="{{ old('marka', $urun->marka) }}" required>
                        @error('marka')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Model <span class="text-danger">*</span></label>
                        <input type="text" name="model" class="form-control @error('model') is-invalid @enderror" value="{{ old('model', $urun->model) }}" required>
                        @error('model')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Barkod No</label>
                        <input type="text" name="barkod_no" class="form-control" value="{{ old('barkod_no', $urun->barkod_no) }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Fiyat (₺) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="fiyat" class="form-control @error('fiyat') is-invalid @enderror" value="{{ old('fiyat', $urun->fiyat) }}" required>
                        @error('fiyat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">Stok <span class="text-danger">*</span></label>
                        <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror" value="{{ old('stok', $urun->stok) }}" required>
                        @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label">KDV (%)</label>
                        <input type="number" step="0.01" name="kdv" class="form-control" value="{{ old('kdv', $urun->kdv ?? 20) }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Resim URL</label>
                        <input type="text" name="resim_url" class="form-control" value="{{ old('resim_url', $urun->resim_url) }}">
                        @if($urun->resim_url)
                            <div class="mt-2">
                                <img src="{{ asset($urun->resim_url) }}" alt="Ürün Resmi" style="max-width: 200px;" class="img-thumbnail">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Açıklama</label>
                    <textarea name="aciklama" rows="4" class="form-control">{{ old('aciklama', $urun->aciklama) }}</textarea>
                </div>

                <!-- Mevcut Kriterler -->
                <div id="kriterler-container" class="mb-4">
                    @if($urun->altKategori && $urun->altKategori->kriterler->count() > 0)
                        <div class="card bg-light mb-3">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="bx bx-list-check me-2"></i>Ana Ürün Kriterleri</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($urun->altKategori->kriterler as $kriter)
                                        @php
                                            $secilenDeger = $urun->kriterDegerleri->firstWhere('pivot.kriter_id', $kriter->id);
                                        @endphp
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">{{ $kriter->kriter_ad }}</label>
                                            <select name="kriter_degerleri[{{ $kriter->id }}]" class="form-select">
                                                <option value="">Seçiniz...</option>
                                                @foreach($kriter->degerler as $deger)
                                                    <option value="{{ $deger->id }}" {{ $secilenDeger && $secilenDeger->id == $deger->id ? 'selected' : '' }}>
                                                        {{ $deger->deger }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Varyasyonlar -->
                <div class="card bg-light mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="bx bx-layer me-2"></i>Varyasyonlar</h6>
                        <button type="button" id="ekle-varyasyon" class="btn btn-sm btn-primary">
                            <i class="bx bx-plus me-1"></i> Yeni Varyasyon Ekle
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="varyasyonlar-container">
                            @forelse($urun->varyasyonlar as $index => $varyasyon)
                                <div class="card mb-3">
                                    <div class="card-header d-flex justify-content-between align-items-center bg-info text-white">
                                        <strong>Varyasyon {{ $index + 1 }}</strong>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('.card').remove()">
                                            <i class="bx bx-trash"></i> Sil
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach($urun->altKategori->kriterler as $kriter)
                                                @php
                                                    $varyasyonKriter = \App\Models\UrunVaryasyonKriterDegeri::where('urun_varyasyon_id', $varyasyon->id)
                                                        ->where('kriter_id', $kriter->id)
                                                        ->first();
                                                @endphp
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">{{ $kriter->kriter_ad }}</label>
                                                    <select name="varyasyonlar[{{ $index }}][kriter_degerleri][{{ $kriter->id }}]" class="form-select" required>
                                                        <option value="">Seçiniz...</option>
                                                        @foreach($kriter->degerler as $deger)
                                                            <option value="{{ $deger->id }}" {{ $varyasyonKriter && $varyasyonKriter->kriter_deger_id == $deger->id ? 'selected' : '' }}>
                                                                {{ $deger->deger }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label">Fiyat (₺) <span class="text-danger">*</span></label>
                                                <input type="number" step="0.01" name="varyasyonlar[{{ $index }}][fiyat]" class="form-control" value="{{ $varyasyon->fiyat }}" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Stok <span class="text-danger">*</span></label>
                                                <input type="number" name="varyasyonlar[{{ $index }}][stok]" class="form-control" value="{{ $varyasyon->stok }}" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">KDV (%)</label>
                                                <input type="number" step="0.01" name="varyasyonlar[{{ $index }}][kdv]" class="form-control" value="{{ $varyasyon->kdv ?? 20 }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted text-center mb-0">Henüz varyasyon eklenmemiş.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success me-2">
                        <i class="bx bx-save me-1"></i> Güncelle
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
let kriterlerData = @json($urun->altKategori->kriterler ?? []);
let varyasyonSayac = {{ $urun->varyasyonlar->count() }};

// Yeni varyasyon ekleme
document.getElementById('ekle-varyasyon').addEventListener('click', function() {
    if(kriterlerData.length === 0) {
        alert('Bu kategoride kriter bulunmuyor.');
        return;
    }

    const container = document.getElementById('varyasyonlar-container');
    if(container.querySelector('p.text-muted')) {
        container.innerHTML = '';
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
                <div class="col-md-4">
                    <label class="form-label">Fiyat (₺) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="varyasyonlar[${varyasyonSayac}][fiyat]" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Stok <span class="text-danger">*</span></label>
                    <input type="number" name="varyasyonlar[${varyasyonSayac}][stok]" class="form-control" value="0" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">KDV (%)</label>
                    <input type="number" step="0.01" name="varyasyonlar[${varyasyonSayac}][kdv]" class="form-control" value="20">
                </div>
            </div>
        </div>
    `;
    container.appendChild(varyasyonDiv);
});
</script>

<style>
.card-header.bg-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}
.card-header.bg-info {
    background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%) !important;
}
</style>
@endsection