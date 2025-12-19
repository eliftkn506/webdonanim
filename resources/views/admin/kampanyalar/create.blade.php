@extends('layouts.admin')

@section('title', 'Kampanya Ekle')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Kampanyalar /</span> Yeni Ekle
    </h4>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Kampanya Detayları</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.kampanyalar.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Kampanya Adı <span class="text-danger">*</span></label>
                    <input type="text" name="kampanya_adi" class="form-control" placeholder="Örn: Kış İndirimi" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kampanya Kapsamı <span class="text-danger">*</span></label>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <div class="form-check custom-option custom-option-basic">
                                <label class="form-check-label custom-option-content" for="kapsamUrun">
                                    <input name="kapsam" class="form-check-input" type="radio" value="urun" id="kapsamUrun" checked onchange="toggleScope()">
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
                                    <input name="kapsam" class="form-check-input" type="radio" value="kategori" id="kapsamKategori" onchange="toggleScope()">
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
                                    <input name="kapsam" class="form-check-input" type="radio" value="tum" id="kapsamTum" onchange="toggleScope()">
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

                <div class="mb-3" id="urunSecimDiv">
                    <label class="form-label">Ürün Seçin</label>
                    <select name="urun_id" class="form-select select2">
                        <option value="">Seçiniz...</option>
                        @foreach($urunler as $urun)
                            <option value="{{ $urun->id }}">{{ $urun->urun_ad }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3" id="kategoriSecimDiv" style="display: none;">
                    <label class="form-label">Kategori Seçin</label>
                    <select name="kategori_id" class="form-select select2">
                        <option value="">Seçiniz...</option>
                        @foreach($kategoriler as $kat)
                            <option value="{{ $kat->id }}">{{ $kat->alt_kategori_ad }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">İndirim Oranı (%)</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">%</span>
                            <input type="number" step="0.01" name="indirim_orani" class="form-control" placeholder="10">
                        </div>
                        <div class="form-text">Örn: %10 indirim.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Sabit Yeni Fiyat (TL)</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">₺</span>
                            <input type="number" step="0.01" name="yeni_fiyat" class="form-control" placeholder="100">
                        </div>
                        <div class="form-text text-warning">Dikkat: Kategori veya Tüm ürünler seçiliyse sabit fiyat mantıklı olmayabilir.</div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Başlangıç Tarihi</label>
                        <input type="date" name="baslangic_tarihi" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Bitiş Tarihi</label>
                        <input type="date" name="bitis_tarihi" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="aktif" value="1" id="aktifSwitch" checked>
                        <label class="form-check-label" for="aktifSwitch">Kampanya Aktif</label>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary me-2">Kaydet</button>
                    <a href="{{ route('admin.kampanyalar.index') }}" class="btn btn-outline-secondary">İptal</a>
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
            // Tum urunler
            urunDiv.style.display = 'none';
            katDiv.style.display = 'none';
        }
    }
</script>
@endsection