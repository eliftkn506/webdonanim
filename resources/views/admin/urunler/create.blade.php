@extends('layouts.admin')

@section('title', 'Yeni Ürün Ekle')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold py-3 mb-0">
                <span class="text-muted fw-light">Ürünler /</span> Yeni Ürün
            </h4>
        </div>
        <a href="{{ route('admin.urunler.index') }}" class="btn btn-label-secondary">
            <i class="bx bx-arrow-back me-1"></i> Listeye Dön
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.urunler.store') }}" method="POST" id="urunForm">
        @csrf
        <div class="row">
            <div class="col-12 col-lg-8">
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Temel Bilgiler</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Ürün Adı <span class="text-danger">*</span></label>
                            <input type="text" name="urun_ad" class="form-control @error('urun_ad') is-invalid @enderror" value="{{ old('urun_ad') }}" placeholder="Örn: iPhone 13 Pro Max" required>
                            @error('urun_ad')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Marka <span class="text-danger">*</span></label>
                                <input type="text" name="marka" class="form-control @error('marka') is-invalid @enderror" value="{{ old('marka') }}" placeholder="Örn: Apple" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Model <span class="text-danger">*</span></label>
                                <input type="text" name="model" class="form-control @error('model') is-invalid @enderror" value="{{ old('model') }}" placeholder="Örn: A2643" required>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label">Açıklama</label>
                            <textarea name="aciklama" rows="4" class="form-control" placeholder="Ürün özelliklerini detaylıca yazınız...">{{ old('aciklama') }}</textarea>
                        </div>
                    </div>
                </div>

                <div id="kriterler-wrapper" class="d-none">
                    <div class="card mb-4">
                        <div class="card-header bg-label-secondary">
                            <h5 class="card-title mb-0"><i class="bx bx-list-check me-2"></i>Ürün Özellikleri</h5>
                        </div>
                        <div class="card-body pt-3">
                            <div class="row g-3" id="kriterler-container">
                                </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0"><i class="bx bx-layer me-2"></i>Varyasyonlar</h5>
                        <button type="button" id="ekle-varyasyon" class="btn btn-primary btn-sm" disabled>
                            <i class="bx bx-plus me-1"></i> Varyasyon Ekle
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="varyasyonlar-container">
                            <div class="text-center py-5" id="varyasyon-empty-state">
                                <div class="mb-3">
                                    <span class="badge bg-label-secondary p-3 rounded-circle">
                                        <i class='bx bx-git-branch fs-2'></i>
                                    </span>
                                </div>
                                <h6 class="text-muted">Varyasyon eklemek için bekliyor...</h6>
                                <p class="small text-muted mb-0">Lütfen sağ taraftan bir <strong>Alt Kategori</strong> seçiniz.</p>
                            </div>
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
                            <select name="alt_kategori_id" id="alt_kategori" class="form-select @error('alt_kategori_id') is-invalid @enderror" required>
                                <option value="">Seçiniz...</option>
                                @foreach($altkategoriler as $alt)
                                    <option value="{{ $alt->id }}" {{ old('alt_kategori_id') == $alt->id ? 'selected' : '' }}>
                                        {{ $alt->kategori->kategori_ad ?? '' }} > {{ $alt->alt_kategori_ad }}
                                    </option>
                                @endforeach
                            </select>
                            @error('alt_kategori_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Barkod No</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="bx bx-barcode"></i></span>
                                <input type="text" name="barkod_no" class="form-control" value="{{ old('barkod_no') }}" placeholder="SCAN-123">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ana Stok (Varyasyonsuz) <span class="text-danger">*</span></label>
                            <input type="number" name="stok" class="form-control" value="{{ old('stok', 0) }}" required>
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
                            <input type="text" name="resim_url" id="resim_url_input" class="form-control" value="{{ old('resim_url') }}" placeholder="https://...">
                        </div>
                        <div class="border rounded d-flex align-items-center justify-content-center bg-light" style="height: 200px; overflow: hidden;">
                            <img id="image_preview" src="" alt="Önizleme" style="max-width: 100%; max-height: 100%; display: none;" class="d-block">
                            <div id="no_image_placeholder" class="text-center text-muted">
                                <i class="bx bx-image fs-1"></i>
                                <div class="small mt-1">Resim Yok</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="bx bx-save me-1"></i> Kaydet ve Yayınla
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
    let kriterlerData = [];
    const kriterContainer = document.getElementById('kriterler-container');
    const kriterWrapper = document.getElementById('kriterler-wrapper');
    const varyasyonContainer = document.getElementById('varyasyonlar-container');
    const emptyState = document.getElementById('varyasyon-empty-state');
    const varyasyonBtn = document.getElementById('ekle-varyasyon');

    // Resim Önizleme
    const imgInput = document.getElementById('resim_url_input');
    const imgPrev = document.getElementById('image_preview');
    const noImgPlace = document.getElementById('no_image_placeholder');

    imgInput.addEventListener('input', function() {
        if(this.value) {
            imgPrev.src = this.value;
            imgPrev.style.display = 'block';
            noImgPlace.style.display = 'none';
        } else {
            imgPrev.style.display = 'none';
            noImgPlace.style.display = 'block';
        }
    });

    // Alt Kategori Değişimi
    document.getElementById('alt_kategori').addEventListener('change', function() {
        const altKategoriId = this.value;
        
        // Alanları temizle
        kriterContainer.innerHTML = '';
        kriterWrapper.classList.add('d-none');
        varyasyonContainer.innerHTML = '';
        varyasyonContainer.appendChild(emptyState);
        emptyState.style.display = 'block';
        varyasyonBtn.disabled = true;
        
        kriterlerData = [];

        if(altKategoriId) {
            fetch(`/admin/urunler/kriterler/${altKategoriId}`)
                .then(res => res.json())
                .then(data => {
                    kriterlerData = data;
                    
                    if(data.length > 0) {
                        // Kriterleri Göster
                        kriterWrapper.classList.remove('d-none');
                        varyasyonBtn.disabled = false;
                        emptyState.style.display = 'none'; // Boş durumu gizle

                        data.forEach(kriter => {
                            const options = kriter.degerler.map(d => `<option value="${d.id}">${d.deger}</option>`).join('');
                            
                            const col = document.createElement('div');
                            col.className = 'col-md-6';
                            col.innerHTML = `
                                <label class="form-label small fw-bold text-uppercase text-muted">${kriter.kriter_ad}</label>
                                <select name="kriter_degerleri[${kriter.id}]" class="form-select">
                                    <option value="">Seçiniz...</option>
                                    ${options}
                                </select>
                            `;
                            kriterContainer.appendChild(col);
                        });
                    } else {
                        // Kriter yoksa varyasyon eklenemez uyarısı
                        emptyState.style.display = 'block';
                        emptyState.querySelector('h6').innerText = "Bu kategoride kriter yok.";
                        emptyState.querySelector('p').innerText = "Varyasyon oluşturmak için kategorinin kriterleri olmalıdır.";
                    }
                })
                .catch(err => console.error(err));
        }
    });

    // Varyasyon Ekleme
    let varyasyonSayac = 0;
    varyasyonBtn.addEventListener('click', function() {
        if(kriterlerData.length === 0) return;

        varyasyonSayac++;
        const varyasyonCard = document.createElement('div');
        varyasyonCard.className = 'card border mb-3 shadow-sm position-relative';
        
        // Kriter Selectlerini Hazırla
        const kriterInputs = kriterlerData.map(kriter => {
            const options = kriter.degerler.map(d => `<option value="${d.id}">${d.deger}</option>`).join('');
            return `
                <div class="col-md-4 mb-3">
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
                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                    <h6 class="mb-0 text-primary fw-bold">#${varyasyonSayac} Varyasyon</h6>
                    <button type="button" class="btn btn-icon btn-label-danger btn-sm" onclick="this.closest('.card').remove()">
                        <i class="bx bx-trash"></i>
                    </button>
                </div>
                
                <div class="row g-2">
                    ${kriterInputs}
                </div>
                
                <div class="row g-2 mt-2 pt-2 border-top bg-light rounded mx-0">
                    <div class="col-md-12 p-2">
                        <label class="form-label small fw-bold">Varyasyon Stoğu</label>
                        <input type="number" name="varyasyonlar[${varyasyonSayac}][stok]" class="form-control form-control-sm" value="0" required>
                    </div>
                </div>
            </div>
        `;
        
        varyasyonContainer.appendChild(varyasyonCard);
    });
});
</script>
@endsection