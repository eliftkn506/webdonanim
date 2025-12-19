@extends('layouts.admin')

@section('title', 'Kampanya Düzenle')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Kampanyalar /</span> Düzenle
        </h4>
        <a href="{{ route('admin.kampanyalar.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i> İptal
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Kampanya Detayları (#{{ $kampanya->id }})</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.kampanyalar.update', $kampanya->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Kampanya Adı <span class="text-danger">*</span></label>
                    <input type="text" name="kampanya_adi" class="form-control" 
                           value="{{ old('kampanya_adi', $kampanya->kampanya_adi) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kampanya Kapsamı <span class="text-danger">*</span></label>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <div class="form-check custom-option custom-option-basic">
                                <label class="form-check-label custom-option-content" for="kapsamUrun">
                                    <input name="kapsam" class="form-check-input" type="radio" value="urun" id="kapsamUrun" 
                                           {{ (old('kapsam', $kampanya->kapsam) == 'urun') ? 'checked' : '' }} 
                                           onchange="toggleScope()">
                                    <span class="custom-option-header">
                                        <span class="h6 mb-0">Tek Ürün</span>
                                    </span>
                                    <span class="custom-option-body">
                                        <small>Sadece seçili bir ürün için.</small>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check custom-option custom-option-basic">
                                <label class="form-check-label custom-option-content" for="kapsamKategori">
                                    <input name="kapsam" class="form-check-input" type="radio" value="kategori" id="kapsamKategori" 
                                           {{ (old('kapsam', $kampanya->kapsam) == 'kategori') ? 'checked' : '' }}
                                           onchange="toggleScope()">
                                    <span class="custom-option-header">
                                        <span class="h6 mb-0">Kategori Bazlı</span>
                                    </span>
                                    <span class="custom-option-body">
                                        <small>Seçili kategorideki tüm ürünler.</small>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check custom-option custom-option-basic">
                                <label class="form-check-label custom-option-content" for="kapsamTum">
                                    <input name="kapsam" class="form-check-input" type="radio" value="tum" id="kapsamTum" 
                                           {{ (old('kapsam', $kampanya->kapsam) == 'tum') ? 'checked' : '' }}
                                           onchange="toggleScope()">
                                    <span class="custom-option-header">
                                        <span class="h6 mb-0">Tüm Ürünler</span>
                                    </span>
                                    <span class="custom-option-body">
                                        <small>Sitedeki her şeyde geçerli.</small>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3" id="urunSecimDiv" style="display: {{ (old('kapsam', $kampanya->kapsam) == 'urun') ? 'block' : 'none' }};">
                    <label class="form-label">Ürün Seçin</label>
                    <select name="urun_id" class="form-select select2">
                        <option value="">Seçiniz...</option>
                        @foreach($urunler as $urun)
                            <option value="{{ $urun->id }}" {{ (old('urun_id', $kampanya->urun_id) == $urun->id) ? 'selected' : '' }}>
                                {{ $urun->urun_ad }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3" id="kategoriSecimDiv" style="display: {{ (old('kapsam', $kampanya->kapsam) == 'kategori') ? 'block' : 'none' }};">
                    <label class="form-label">Kategori Seçin</label>
                    <select name="kategori_id" class="form-select select2">
                        <option value="">Seçiniz...</option>
                        @foreach($kategoriler as $kat)
                            <option value="{{ $kat->id }}" {{ (old('kategori_id', $kampanya->kategori_id) == $kat->id) ? 'selected' : '' }}>
                                {{ $kat->alt_kategori_ad }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">İndirim Oranı (%)</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">%</span>
                            <input type="number" step="0.01" name="indirim_orani" class="form-control" 
                                   value="{{ old('indirim_orani', $kampanya->indirim_orani) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Sabit Yeni Fiyat (TL)</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">₺</span>
                            <input type="number" step="0.01" name="yeni_fiyat" class="form-control" 
                                   value="{{ old('yeni_fiyat', $kampanya->yeni_fiyat) }}">
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Başlangıç Tarihi</label>
                        <input type="date" name="baslangic_tarihi" class="form-control" required
                               value="{{ old('baslangic_tarihi', \Carbon\Carbon::parse($kampanya->baslangic_tarihi)->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Bitiş Tarihi</label>
                        <input type="date" name="bitis_tarihi" class="form-control" required
                               value="{{ old('bitis_tarihi', \Carbon\Carbon::parse($kampanya->bitis_tarihi)->format('Y-m-d')) }}">
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch mb-2">
                        <input type="hidden" name="aktif" value="0"> <input class="form-check-input" type="checkbox" name="aktif" value="1" id="aktifSwitch"
                               {{ (old('aktif', $kampanya->aktif)) ? 'checked' : '' }}>
                        <label class="form-check-label" for="aktifSwitch">Kampanya Aktif</label>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bx bx-save me-1"></i> Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleScope() {
        const scope = document.querySelector('input[name="kapsam"]:checked').value;
        const urunDiv = document.getElementById('urunSecimDiv');
        const katDiv = document.getElementById('kategoriSecimDiv');

        if (scope === 'urun') {
            urunDiv.style.display = 'block';
            katDiv.style.display = 'none';
        } else if (scope === 'kategori') {
            urunDiv.style.display = 'none';
            katDiv.style.display = 'block';
        } else {
            // Tüm ürünler
            urunDiv.style.display = 'none';
            katDiv.style.display = 'none';
        }
    }

    // Sayfa yüklendiğinde mevcut duruma göre görünürlüğü ayarla (Eski browserlar veya JS gecikmesi için güvenlik önlemi)
    document.addEventListener('DOMContentLoaded', function() {
        toggleScope();
    });
</script>
@endsection