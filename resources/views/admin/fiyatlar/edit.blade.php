@extends('layouts.admin')

@section('title', 'Fiyat Düzenle ve Ürün Atama')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Fiyat Düzenleme -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Fiyat Düzenle #{{ $fiyat->fiyat_id }}
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

                    <!-- İlişkili Ürünler Uyarısı -->
                    @if($fiyat->urunler->count() > 0)
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Dikkat!</strong> Bu fiyat tanımı <strong>{{ $fiyat->urunler->count() }}</strong> ürünle ilişkili.
                            Değişiklikler tüm ilişkili ürünleri etkileyecektir.
                            <div class="mt-2">
                                <small><strong>İlişkili Ürünler:</strong></small>
                                <ul class="small mb-0 mt-1">
                                    @foreach($fiyat->urunler->take(5) as $urun)
                                        <li>{{ $urun->urun_ad }} ({{ $urun->marka }})</li>
                                    @endforeach
                                    @if($fiyat->urunler->count() > 5)
                                        <li><em>ve {{ $fiyat->urunler->count() - 5 }} ürün daha...</em></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    @endif

                    <!-- Fiyat Düzenleme ve Ürün Atama Formu -->
                    <form action="{{ route('admin.fiyatlar.update', $fiyat) }}" method="POST" id="fiyatForm">
                        @csrf
                        @method('PUT')

                        <!-- Fiyat Türü -->
                        <div class="mb-3">
                            <label class="form-label required">Fiyat Türü</label>
                            <select name="fiyat_turu" class="form-select" required>
                                <option value="standart" {{ old('fiyat_turu', $fiyat->fiyat_turu)=='standart' ? 'selected':'' }}>Standart</option>
                                <option value="bayi" {{ old('fiyat_turu', $fiyat->fiyat_turu)=='bayi' ? 'selected':'' }}>Bayi</option>
                                <option value="kampanya" {{ old('fiyat_turu', $fiyat->fiyat_turu)=='kampanya' ? 'selected':'' }}>Kampanya</option>
                            </select>
                        </div>

                        <!-- Maliyet -->
                        <div class="mb-3">
                            <label class="form-label required">Maliyet (₺)</label>
                            <input type="number" name="maliyet" id="maliyet" class="form-control" step="0.01" min="0" value="{{ old('maliyet', $fiyat->maliyet) }}" required>
                        </div>

                        <!-- Kar Oranı -->
                        <div class="mb-3">
                            <label class="form-label required">Kar Oranı (%)</label>
                            <input type="number" name="kar_orani" id="kar_orani" class="form-control" step="0.01" min="0" max="1000" value="{{ old('kar_orani', $fiyat->kar_orani) }}" required>
                        </div>

                        <!-- Bayi İndirimi -->
                        <div class="mb-3">
                            <label class="form-label">Bayi İndirimi (%)</label>
                            <input type="number" name="bayi_indirimi" id="bayi_indirimi" class="form-control" step="0.01" min="0" max="100" value="{{ old('bayi_indirimi', $fiyat->bayi_indirimi) }}">
                        </div>

                        <!-- Vergi Oranı -->
                        <div class="mb-3">
                            <label class="form-label required">Vergi Oranı (KDV %)</label>
                            <select name="vergi_orani" id="vergi_orani" class="form-select" required>
                                <option value="1" {{ old('vergi_orani',$fiyat->vergi_orani)==1 ? 'selected':'' }}>%1</option>
                                <option value="8" {{ old('vergi_orani',$fiyat->vergi_orani)==8 ? 'selected':'' }}>%8</option>
                                <option value="18" {{ old('vergi_orani',$fiyat->vergi_orani)==18 ? 'selected':'' }}>%18</option>
                                <option value="20" {{ old('vergi_orani',$fiyat->vergi_orani)==20 ? 'selected':'' }}>%20</option>
                            </select>
                        </div>

                        <!-- Ürün Atama -->
                        <div class="mb-3">
                            <label class="form-label">Bu Fiyatı Atayacağınız Ürünler</label>
                            <select name="urunler[]" class="form-select" multiple>
                                @foreach($urunler as $urun)
                                    <option value="{{ $urun->id }}"
                                        {{ in_array($urun->id, old('urunler', $fiyat->urunler->pluck('id')->toArray() )) ? 'selected' : '' }}>
                                        {{ $urun->urun_ad }} ({{ $urun->marka }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Ctrl/Command tuşu ile birden fazla ürün seçebilirsiniz</small>
                        </div>

                        <!-- Tarih Aralığı -->
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Başlangıç Tarihi</label>
                                <input type="date" name="baslangic_tarihi" class="form-control" value="{{ old('baslangic_tarihi') }}">
                            </div>
                            <div class="col">
                                <label class="form-label">Bitiş Tarihi</label>
                                <input type="date" name="bitis_tarihi" class="form-control" value="{{ old('bitis_tarihi') }}">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.fiyatlar.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i>Geri Dön</a>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Kaydet ve Ata</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Önizleme Paneli -->
        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Fiyat Önizleme</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr><th>Maliyet:</th><td class="text-end"><span id="prev_maliyet">0.00</span> ₺</td></tr>
                        <tr><th>Kar Tutarı:</th><td class="text-end"><span id="prev_kar">0.00</span> ₺</td></tr>
                        <tr class="table-active"><th>Temel Fiyat:</th><td class="text-end"><strong><span id="prev_temel">0.00</span> ₺</strong></td></tr>
                        <tr><th>KDV Tutarı:</th><td class="text-end"><span id="prev_kdv">0.00</span> ₺</td></tr>
                        <tr class="table-primary"><th>Vergi Dahil Fiyat:</th><td class="text-end"><strong><span id="prev_vergi_dahil">0.00</span> ₺</strong></td></tr>
                        <tr id="bayi_row" style="display:none;" class="table-success"><th>Bayi Fiyatı:</th><td class="text-end"><strong><span id="prev_bayi">0.00</span> ₺</strong></td></tr>
                    </table>
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

        if(mal>0){
            const karTutari = mal * kar/100;
            const temelFiyat = mal + karTutari;
            const kdvTutari = temelFiyat * vergi/100;
            const vergiDahilFiyat = temelFiyat + kdvTutari;
            const bayiFiyat = vergiDahilFiyat - (vergiDahilFiyat * bayi/100);

            document.getElementById('prev_maliyet').textContent = mal.toFixed(2);
            document.getElementById('prev_kar').textContent = karTutari.toFixed(2);
            document.getElementById('prev_temel').textContent = temelFiyat.toFixed(2);
            document.getElementById('prev_kdv').textContent = kdvTutari.toFixed(2);
            document.getElementById('prev_vergi_dahil').textContent = vergiDahilFiyat.toFixed(2);

            if(bayi>0){
                document.getElementById('bayi_row').style.display='table-row';
                document.getElementById('prev_bayi').textContent = bayiFiyat.toFixed(2);
            } else {
                document.getElementById('bayi_row').style.display='none';
            }
        }
    }

    maliyet.addEventListener('input', hesaplaFiyat);
    karOrani.addEventListener('input', hesaplaFiyat);
    bayiIndirimi.addEventListener('input', hesaplaFiyat);
    vergiOrani.addEventListener('change', hesaplaFiyat);

    hesaplaFiyat();
});
</script>

<style>
.required::after { content:" *"; color:red; }
.sticky-top{ position: sticky; top:20px;}
</style>
@endsection
