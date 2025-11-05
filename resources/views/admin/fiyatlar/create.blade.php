@extends('layouts.admin')

@section('title', 'Yeni Fiyat Ekle')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Yeni Fiyat Tanımı
                    </h4>
                </div>

                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Hata!</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.fiyatlar.store') }}" method="POST" id="fiyatForm">
                        @csrf
                        <!-- Ürün seçimi -->
                        <div class="mb-4">
                            <label class="form-label required">Ürün</label>
                            <select name="urun_id" class="form-select" required>
                                <option value="">Seçiniz</option>
                                @foreach($urunler as $urun)
                                    <option value="{{ $urun->id }}" {{ old('urun_id') == $urun->id ? 'selected' : '' }}>
                                        {{ $urun->urun_ad }} ({{ $urun->altKategori->ad ?? '' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        <!-- Fiyat Türü -->
                        <div class="mb-4">
                            <label class="form-label required">Fiyat Türü</label>
                            <select name="fiyat_turu" id="fiyat_turu" class="form-select" required>
                                <option value="">Seçiniz</option>
                                <option value="standart" {{ old('fiyat_turu') == 'standart' ? 'selected' : '' }}>
                                    Standart Fiyat
                                </option>
                                <option value="bayi" {{ old('fiyat_turu') == 'bayi' ? 'selected' : '' }}>
                                    Bayi Fiyatı
                                </option>
                                <option value="kampanya" {{ old('fiyat_turu') == 'kampanya' ? 'selected' : '' }}>
                                    Kampanya Fiyatı
                                </option>
                            </select>
                        </div>

                        <!-- Maliyet -->
                        <div class="mb-4">
                            <label class="form-label required">Maliyet (₺)</label>
                            <input type="number" 
                                   name="maliyet" 
                                   id="maliyet" 
                                   class="form-control" 
                                   step="0.01" 
                                   min="0" 
                                   value="{{ old('maliyet') }}" 
                                   required>
                            <small class="text-muted">Ürünün maliyeti (KDV hariç)</small>
                        </div>

                        <!-- Kar Oranı -->
                        <div class="mb-4">
                            <label class="form-label required">Kar Oranı (%)</label>
                            <input type="number" 
                                   name="kar_orani" 
                                   id="kar_orani" 
                                   class="form-control" 
                                   step="0.01" 
                                   min="0" 
                                   max="1000" 
                                   value="{{ old('kar_orani', 30) }}" 
                                   required>
                            <small class="text-muted">Maliyet üzerine eklenecek kar oranı</small>
                        </div>

                        <!-- Bayi İndirimi -->
                        <div class="mb-4">
                            <label class="form-label">Bayi İndirimi (%)</label>
                            <input type="number" 
                                   name="bayi_indirimi" 
                                   id="bayi_indirimi" 
                                   class="form-control" 
                                   step="0.01" 
                                   min="0" 
                                   max="100" 
                                   value="{{ old('bayi_indirimi', 0) }}">
                            <small class="text-muted">Bayilere özel indirim oranı (opsiyonel)</small>
                        </div>

                        <!-- Vergi Oranı -->
                        <div class="mb-4">
                            <label class="form-label required">Vergi Oranı (KDV %)</label>
                            <select name="vergi_orani" id="vergi_orani" class="form-select" required>
                                <option value="1" {{ old('vergi_orani') == 1 ? 'selected' : '' }}>%1</option>
                                <option value="8" {{ old('vergi_orani') == 8 ? 'selected' : '' }}>%8</option>
                                <option value="18" {{ old('vergi_orani') == 18 ? 'selected' : '' }}>%18</option>
                                <option value="20" {{ old('vergi_orani', 20) == 20 ? 'selected' : '' }}>%20</option>
                            </select>
                        </div>

                        <!-- Butonlar -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.fiyatlar.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Geri Dön
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Kaydet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Önizleme Paneli -->
        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calculator me-2"></i>Fiyat Önizleme
                    </h5>
                </div>
                <div class="card-body">
                    <div id="preview-content">
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-info-circle fa-3x mb-3"></i>
                            <p>Değerleri girin, otomatik hesaplanacak</p>
                        </div>
                    </div>

                    <div id="preview-result" style="display: none;">
                        <table class="table table-sm">
                            <tr>
                                <th>Maliyet:</th>
                                <td class="text-end"><span id="prev_maliyet">0.00</span> ₺</td>
                            </tr>
                            <tr>
                                <th>Kar Tutarı:</th>
                                <td class="text-end"><span id="prev_kar">0.00</span> ₺</td>
                            </tr>
                            <tr class="table-active">
                                <th>Temel Fiyat:</th>
                                <td class="text-end"><strong><span id="prev_temel">0.00</span> ₺</strong></td>
                            </tr>
                            <tr>
                                <th>KDV Tutarı:</th>
                                <td class="text-end"><span id="prev_kdv">0.00</span> ₺</td>
                            </tr>
                            <tr class="table-primary">
                                <th>Vergi Dahil Fiyat:</th>
                                <td class="text-end"><strong><span id="prev_vergi_dahil">0.00</span> ₺</strong></td>
                            </tr>
                            <tr id="bayi_row" style="display: none;" class="table-success">
                                <th>Bayi Fiyatı:</th>
                                <td class="text-end"><strong><span id="prev_bayi">0.00</span> ₺</strong></td>
                            </tr>
                        </table>

                        <div class="alert alert-info mt-3">
                            <small>
                                <strong>Kar Marjı:</strong> <span id="prev_marj">0</span>%<br>
                                <strong>Net Kar:</strong> <span id="prev_net_kar">0.00</span> ₺
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Yardım Kartı -->
            <div class="card mt-3">
                <div class="card-header bg-warning">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb me-2"></i>İpuçları
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="small mb-0">
                        <li>Maliyet ürünün alış fiyatıdır</li>
                        <li>Kar oranı maliyet üzerinden hesaplanır</li>
                        <li>Bayi indirimi son fiyat üzerinden uygulanır</li>
                        <li>KDV temel fiyat üzerine eklenir</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const maliyet = document.getElementById('maliyet');
    const karOrani = document.getElementById('kar_orani');
    const bayiIndirimi = document.getElementById('bayi_indirimi');
    const vergiOrani = document.getElementById('vergi_orani');

    function hesaplaFiyat() {
        const mal = parseFloat(maliyet.value) || 0;
        const kar = parseFloat(karOrani.value) || 0;
        const bayi = parseFloat(bayiIndirimi.value) || 0;
        const vergi = parseFloat(vergiOrani.value) || 0;

        if (mal > 0) {
            // Kar tutarı
            const karTutari = mal * kar / 100;
            
            // Temel fiyat
            const temelFiyat = mal + karTutari;
            
            // KDV tutarı
            const kdvTutari = temelFiyat * vergi / 100;
            
            // Vergi dahil fiyat
            const vergiDahilFiyat = temelFiyat + kdvTutari;
            
            // Bayi fiyatı
            const bayiFiyat = vergiDahilFiyat - (vergiDahilFiyat * bayi / 100);
            
            // Kar marjı
            const karMarji = ((vergiDahilFiyat - mal) / vergiDahilFiyat * 100);
            
            // Net kar
            const netKar = vergiDahilFiyat - mal;

            // Görüntüle
            document.getElementById('preview-content').style.display = 'none';
            document.getElementById('preview-result').style.display = 'block';
            
            document.getElementById('prev_maliyet').textContent = mal.toFixed(2);
            document.getElementById('prev_kar').textContent = karTutari.toFixed(2);
            document.getElementById('prev_temel').textContent = temelFiyat.toFixed(2);
            document.getElementById('prev_kdv').textContent = kdvTutari.toFixed(2);
            document.getElementById('prev_vergi_dahil').textContent = vergiDahilFiyat.toFixed(2);
            document.getElementById('prev_marj').textContent = karMarji.toFixed(1);
            document.getElementById('prev_net_kar').textContent = netKar.toFixed(2);
            
            if (bayi > 0) {
                document.getElementById('bayi_row').style.display = 'table-row';
                document.getElementById('prev_bayi').textContent = bayiFiyat.toFixed(2);
            } else {
                document.getElementById('bayi_row').style.display = 'none';
            }
        } else {
            document.getElementById('preview-content').style.display = 'block';
            document.getElementById('preview-result').style.display = 'none';
        }
    }

    // Event listeners
    maliyet.addEventListener('input', hesaplaFiyat);
    karOrani.addEventListener('input', hesaplaFiyat);
    bayiIndirimi.addEventListener('input', hesaplaFiyat);
    vergiOrani.addEventListener('change', hesaplaFiyat);

    // İlk hesaplama
    hesaplaFiyat();
});
</script>

<style>
.required::after {
    content: " *";
    color: red;
}

.sticky-top {
    position: sticky;
}

.table th {
    font-weight: 600;
    width: 50%;
}

.table-primary td {
    font-size: 1.1rem;
}

.table-success td {
    font-size: 1.1rem;
    color: #198754;
}

@media (max-width: 991px) {
    .sticky-top {
        position: relative !important;
        top: 0 !important;
    }
}
</style>
@endsection