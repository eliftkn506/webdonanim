
@extends('layouts.admin')

@section('title', 'Ürün Düzenle')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold py-3 mb-0">
                <span class="text-muted fw-light">Ürünler /</span> {{ $urun->urun_ad ?? 'Düzenle' }}
            </h4>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.urunler.index') }}" class="btn btn-label-secondary">
                <i class="bx bx-arrow-back me-1"></i> Listeye Dön
            </a>
            <a href="{{ route('admin.urunler.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Yeni Ürün
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.urunler.update', ['urunler' => $urun->id]) }}" method="POST" id="urunForm">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-12 col-lg-8">
                
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Temel Bilgiler</h5>
                        <span class="badge bg-label-primary">ID: {{ $urun->id }}</span>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Ürün Adı <span class="text-danger">*</span></label>
                            <input type="text" name="urun_ad" class="form-control @error('urun_ad') is-invalid @enderror" value="{{ old('urun_ad', $urun->urun_ad) }}" placeholder="Örn: iPhone 13" required>
                            @error('urun_ad')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Marka <span class="text-danger">*</span></label>
                                <input type="text" name="marka" class="form-control @error('marka') is-invalid @enderror" value="{{ old('marka', $urun->marka) }}" placeholder="Marka giriniz" required>
                                @error('marka')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Model <span class="text-danger">*</span></label>
                                <input type="text" name="model" class="form-control @error('model') is-invalid @enderror" value="{{ old('model', $urun->model) }}" placeholder="Model giriniz" required>
                                @error('model')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label">Açıklama</label>
                            <textarea name="aciklama" rows="4" class="form-control" placeholder="Ürün açıklaması...">{{ old('aciklama', $urun->aciklama) }}</textarea>
                        </div>
                    </div>
                </div>

                @if($urun->altKategori && $urun->altKategori->kriterler && $urun->altKategori->kriterler->count() > 0)
                <div class="card mb-4">
                    <div class="card-header bg-label-secondary">
                        <h5 class="card-title mb-0"><i class="bx bx-list-check me-2"></i>Ürün Özellikleri (Kriterler)</h5>
                    </div>
                    <div class="card-body pt-3">
                        <div class="row g-3">
                            @foreach($urun->altKategori->kriterler as $kriter)
                                @php
                                    $secilenDeger = $urun->kriterDegerleri->firstWhere('pivot.kriter_id', $kriter->id);
                                @endphp
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase text-muted">{{ $kriter->kriter_ad }}</label>
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

                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0"><i class="bx bx-layer me-2"></i>Varyasyon Yönetimi</h5>
                        <button type="button" id="ekle-varyasyon" class="btn btn-primary btn-sm">
                            <i class="bx bx-plus me-1"></i> Yeni Varyasyon Ekle
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="varyasyonlar-container">
                            @forelse($urun->varyasyonlar as $index => $varyasyon)
                                <div class="card border mb-3 shadow-none bg-transparent position-relative">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-2">
                                            <h6 class="mb-0 text-primary fw-bold">#{{ $index + 1 }} Varyasyon</h6>
                                            <button type="button" class="btn btn-icon btn-label-danger btn-sm" onclick="this.closest('.card').remove()">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                        
                                        @if($urun->altKategori && $urun->altKategori->kriterler)
                                        <div class="row g-2 mb-3">
                                            @foreach($urun->altKategori->kriterler as $kriter)
                                                @php
                                                    $varyasyonKriter = \App\Models\UrunVaryasyonKriterDegeri::where('urun_varyasyon_id', $varyasyon->id)
                                                        ->where('kriter_id', $kriter->id)->first();
                                                @endphp
                                                <div class="col-md-4 mb-2">
                                                    <label class="form-label small mb-1 fw-semibold">{{ $kriter->kriter_ad }}</label>
                                                    <select name="varyasyonlar[{{ $index }}][kriter_degerleri][{{ $kriter->id }}]" class="form-select form-select-sm" required>
                                                        <option value="">Seç...</option>
                                                        @foreach($kriter->degerler as $deger)
                                                            <option value="{{ $deger->id }}" {{ $varyasyonKriter && $varyasyonKriter->kriter_deger_id == $deger->id ? 'selected' : '' }}>
                                                                {{ $deger->deger }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endforeach
                                        </div>
                                        @endif

                                        <div class="row g-2 border-top pt-2 bg-light rounded mx-0">
                                            <div class="col-md-12 p-2">
                                                <label class="form-label small fw-bold">Varyasyon Stoğu</label>
                                                <input type="number" name="varyasyonlar[{{ $index }}][stok]" class="form-control form-control-sm" value="{{ $varyasyon->stok }}" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-muted" id="varyasyon-empty-state">
                                    <i class='bx bx-git-branch fs-1'></i>
                                    <p class="mt-2">Henüz varyasyon eklenmemiş.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-12 col-lg-4">
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Organizasyon</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Alt Kategori <span class="text-danger">*</span></label>
                            <select name="alt_kategori_id" class="form-select @error('alt_kategori_id') is-invalid @enderror" required>
                                @foreach($altkategoriler as $alt)
                                    <option value="{{ $alt->id }}" {{ $urun->alt_kategori_id == $alt->id ? 'selected' : '' }}>
                                        {{ $alt->kategori->kategori_ad ?? '' }} > {{ $alt->alt_kategori_ad }}
                                    </option>
                                @endforeach
                            </select>
                            @error('alt_kategori_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text text-warning">
                                <i class="bx bx-error-circle"></i> Kategori değişimi mevcut kriterleri sıfırlayabilir.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Barkod No</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-barcode"></i></span>
                                <input type="text" name="barkod_no" class="form-control" value="{{ old('barkod_no', $urun->barkod_no) }}" placeholder="SCAN-123">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ana Stok <span class="text-danger">*</span></label>
                            <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror" value="{{ old('stok', $urun->stok) }}" required>
                            @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Varyasyonlu ürünlerde ana stok toplamı gösterir.</div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Medya</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Resim URL</label>
                            <input type="text" name="resim_url" id="resim_url_input" class="form-control" value="{{ old('resim_url', $urun->resim_url) }}" placeholder="https://...">
                        </div>
                        <div class="border rounded d-flex align-items-center justify-content-center bg-light" style="height: 200px; overflow: hidden;">
                            @if($urun->resim_url)
                                <img id="image_preview" src="{{ $urun->resim_url }}" alt="Önizleme" style="max-width: 100%; max-height: 100%;" class="d-block">
                                <div id="no_image_placeholder" class="text-center text-muted" style="display: none;">
                                    <i class="bx bx-image fs-1"></i>
                                    <div class="small mt-1">Resim Yok</div>
                                </div>
                            @else
                                <img id="image_preview" src="" alt="Önizleme" style="display: none; max-width: 100%; max-height: 100%;">
                                <div id="no_image_placeholder" class="text-center text-muted">
                                    <i class="bx bx-image fs-1"></i>
                                    <div class="small mt-1">Resim Yok</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="bx bx-save me-1"></i> Değişiklikleri Kaydet
                        </button>
                        <a href="{{ route('admin.urunler.index') }}" class="btn btn-outline-secondary w-100">İptal</a>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // PHP'den gelen kriter verisi
        let kriterlerData = @json($urun->altKategori->kriterler ?? []);
        let varyasyonSayac = {{ $urun->varyasyonlar->count() + 100 }}; 
        
        const varyasyonContainer = document.getElementById('varyasyonlar-container');
        const emptyState = document.getElementById('varyasyon-empty-state');

        // Resim Önizleme
        const imgInput = document.getElementById('resim_url_input');
        const imgPrev = document.getElementById('image_preview');
        const noImgPlace = document.getElementById('no_image_placeholder');
        
        if(imgInput && imgPrev) {
            imgInput.addEventListener('input', function() {
                if(this.value) {
                    imgPrev.src = this.value;
                    imgPrev.style.display = 'block';
                    if(noImgPlace) noImgPlace.style.display = 'none';
                } else {
                    imgPrev.style.display = 'none';
                    if(noImgPlace) noImgPlace.style.display = 'block';
                }
            });
        }

        // Yeni Varyasyon Ekleme
        const varyasyonBtn = document.getElementById('ekle-varyasyon');
        if(varyasyonBtn) {
            varyasyonBtn.addEventListener('click', function() {
                if(kriterlerData.length === 0) {
                    alert('Bu kategoride kriter bulunmuyor.');
                    return;
                }

                if(emptyState) emptyState.style.display = 'none';

                varyasyonSayac++;
                const varyasyonCard = document.createElement('div');
                varyasyonCard.className = 'card border mb-3 shadow-none bg-transparent position-relative';

                const kriterInputs = kriterlerData.map(kriter => {
                    const options = kriter.degerler.map(d => `<option value="${d.id}">${d.deger}</option>`).join('');
                    return `
                        <div class="col-md-4 mb-2">
                            <label class="form-label small mb-1 fw-semibold">${kriter.kriter_ad}</label>
                            <select name="varyasyonlar[${varyasyonSayac}][kriter_degerleri][${kriter.id}]" class="form-select form-select-sm" required>
                                <option value="">Seç...</option>
                                ${options}
                            </select>
                        </div>
                    `;
                }).join('');

                varyasyonCard.innerHTML = `
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-2">
                            <h6 class="mb-0 text-primary fw-bold">#Yeni Varyasyon</h6>
                            <button type="button" class="btn btn-icon btn-label-danger btn-sm" onclick="this.closest('.card').remove()">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                        <div class="row g-2 mb-3">
                            ${kriterInputs}
                        </div>
                        <div class="row g-2 border-top pt-2 bg-light rounded mx-0">
                            <div class="col-md-12 p-2">
                                <label class="form-label small fw-bold">Varyasyon Stoğu</label>
                                <input type="number" name="varyasyonlar[${varyasyonSayac}][stok]" class="form-control form-control-sm" value="0" required>
                            </div>
                        </div>
                    </div>
                `;
                varyasyonContainer.appendChild(varyasyonCard);
            });
        }
    });
</script>
@endsection