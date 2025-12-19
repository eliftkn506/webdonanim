@extends('layouts.admin')

@section('title', isset($uyumluluk) ? 'Kural Düzenle' : 'Yeni Kural Ekle')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold py-3 mb-0">
                <span class="text-muted fw-light">Uyumluluk /</span> {{ isset($uyumluluk) ? 'Kural Düzenle' : 'Yeni Kural Ekle' }}
            </h4>
            <p class="text-muted mb-0">İki kategori arasındaki eşleşme mantığını tanımlayın.</p>
        </div>
        <a href="{{ route('admin.uyumluluk.index') }}" class="btn btn-label-secondary">
            <i class="bx bx-arrow-back me-1"></i> Listeye Dön
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class='bx bx-error-circle me-2 fs-4'></i>
                {{ session('error') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h6 class="alert-heading d-flex align-items-center mb-1">
                <i class='bx bx-error-circle me-2'></i> Form Hatası
            </h6>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ isset($uyumluluk) ? route('admin.uyumluluk.update', $uyumluluk->id) : route('admin.uyumluluk.store') }}" 
          method="POST" 
          id="uyumlulukForm">
        @csrf
        @if(isset($uyumluluk))
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-xl-8 col-lg-7">
                <div class="card mb-4">
                    <div class="card-header border-bottom">
                        <h5 class="card-title mb-0">Eşleşme Ayarları</h5>
                    </div>
                    <div class="card-body pt-4">
                        
                        <div class="row g-4 align-items-center position-relative">
                            
                            <div class="col-md-5">
                                <div class="p-3 border rounded bg-label-primary bg-opacity-10 border-primary border-opacity-25">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="avatar avatar-sm bg-primary text-white rounded me-2 d-flex align-items-center justify-content-center">
                                            <i class='bx bx-microchip'></i>
                                        </span>
                                        <h6 class="mb-0 text-primary fw-bold">Ana Bileşen (Kaynak)</h6>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">Kategori Seçin</label>
                                        <select name="ana_kategori_id" id="ana_kategori_id" class="form-select" required>
                                            <option value="">Seçiniz...</option>
                                            @foreach($altKategoriler as $kategoriAd => $altKats)
                                                <optgroup label="{{ $kategoriAd }}">
                                                    @foreach($altKats as $altKat)
                                                        <option value="{{ $altKat->id }}" 
                                                            {{ (isset($uyumluluk) && $uyumluluk->ana_kategori_id == $altKat->id) || old('ana_kategori_id') == $altKat->id ? 'selected' : '' }}>
                                                            {{ $altKat->alt_kategori_ad }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-0">
                                        <label class="form-label small fw-bold text-muted">Kontrol Kriteri</label>
                                        <select name="ana_kriter_id" id="ana_kriter_id" class="form-select" required>
                                            <option value="">Önce kategori seçin...</option>
                                            @if(isset($anaKriterler))
                                                @foreach($anaKriterler as $kriter)
                                                    <option value="{{ $kriter->id }}" {{ (isset($uyumluluk) && $uyumluluk->ana_kriter_id == $kriter->id) ? 'selected' : '' }}>
                                                        {{ $kriter->kriter_ad }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <div class="form-text text-muted" style="font-size: 0.75rem;">Örn: Socket Tipi</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2 text-center">
                                <div class="d-none d-md-block">
                                    <i class='bx bx-right-arrow-alt text-muted display-4'></i>
                                    <div class="small fw-bold text-muted mt-n2">EŞİTTİR</div>
                                </div>
                                <div class="d-md-none py-3">
                                    <i class='bx bx-down-arrow-alt text-muted display-4'></i>
                                    <div class="small fw-bold text-muted mt-n2">EŞİTTİR</div>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="p-3 border rounded bg-label-success bg-opacity-10 border-success border-opacity-25">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="avatar avatar-sm bg-success text-white rounded me-2 d-flex align-items-center justify-content-center">
                                            <i class='bx bx-hdd'></i>
                                        </span>
                                        <h6 class="mb-0 text-success fw-bold">Hedef Bileşen (Kontrol)</h6>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">Kategori Seçin</label>
                                        <select name="hedef_kategori_id" id="hedef_kategori_id" class="form-select" required>
                                            <option value="">Seçiniz...</option>
                                            @foreach($altKategoriler as $kategoriAd => $altKats)
                                                <optgroup label="{{ $kategoriAd }}">
                                                    @foreach($altKats as $altKat)
                                                        <option value="{{ $altKat->id }}" 
                                                            {{ (isset($uyumluluk) && $uyumluluk->hedef_kategori_id == $altKat->id) || old('hedef_kategori_id') == $altKat->id ? 'selected' : '' }}>
                                                            {{ $altKat->alt_kategori_ad }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-0">
                                        <label class="form-label small fw-bold text-muted">Kontrol Kriteri</label>
                                        <select name="hedef_kriter_id" id="hedef_kriter_id" class="form-select" required>
                                            <option value="">Önce kategori seçin...</option>
                                            @if(isset($hedefKriterler))
                                                @foreach($hedefKriterler as $kriter)
                                                    <option value="{{ $kriter->id }}" {{ (isset($uyumluluk) && $uyumluluk->hedef_kriter_id == $kriter->id) ? 'selected' : '' }}>
                                                        {{ $kriter->kriter_ad }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <div class="form-text text-muted" style="font-size: 0.75rem;">Örn: İşlemci Desteği</div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.uyumluluk.index') }}" class="btn btn-outline-secondary">İptal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-check me-1'></i> {{ isset($uyumluluk) ? 'Kuralı Güncelle' : 'Kuralı Kaydet' }}
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-5">
                <div class="card mb-4">
                    <div class="card-header bg-warning bg-opacity-10 border-bottom border-warning border-opacity-25">
                        <h6 class="card-title text-warning mb-0">
                            <i class='bx bx-bulb me-1'></i> İpucu
                        </h6>
                    </div>
                    <div class="card-body pt-3">
                        <p class="mb-3">
                            Uyumluluk kuralı, iki farklı kategorideki ürünlerin birbirleriyle çalışıp çalışmayacağını belirler.
                        </p>
                        <h6 class="small fw-bold text-uppercase text-muted mb-2">Mantık Şudur:</h6>
                        <div class="alert alert-secondary p-2 mb-3 small">
                            Eğer <strong>Ana Bileşenin</strong> [X] kriter değeri,<br>
                            <strong>Hedef Bileşenin</strong> [Y] kriter değeri ile<br>
                            <span class="text-primary fw-bold">AYNI İSE</span> ürünler uyumludur.
                        </div>

                        <h6 class="small fw-bold text-uppercase text-muted mb-2">Örnek:</h6>
                        <ul class="list-unstyled small mb-0">
                            <li class="mb-2">
                                <i class='bx bx-check text-success me-1'></i>
                                <strong>İşlemci</strong> (Socket: 1700) <br>
                                <strong>Anakart</strong> (Socket: 1700) <br>
                                <span class="badge bg-label-success mt-1">UYUMLU</span>
                            </li>
                            <li>
                                <i class='bx bx-x text-danger me-1'></i>
                                <strong>Ram</strong> (Tipi: DDR4) <br>
                                <strong>Anakart</strong> (Tipi: DDR5) <br>
                                <span class="badge bg-label-danger mt-1">UYUMSUZ</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Kriter Yükleme Fonksiyonu
    function loadCriteria(kategoriSelectId, kriterSelectId) {
        const kategoriSelect = document.getElementById(kategoriSelectId);
        const kriterSelect = document.getElementById(kriterSelectId);

        kategoriSelect.addEventListener('change', function() {
            const altKategoriId = this.value;
            
            // Sıfırla ve Yükleniyor göster
            kriterSelect.innerHTML = '<option value="">Yükleniyor...</option>';
            kriterSelect.disabled = true;

            if (altKategoriId) {
                fetch("{{ url('/admin/uyumluluk/kriterler') }}/" + altKategoriId)
                    .then(response => response.json())
                    .then(data => {
                        kriterSelect.innerHTML = '<option value="">Seçiniz...</option>';
                        
                        if (data.length === 0) {
                            kriterSelect.innerHTML = '<option value="">Bu kategoride kriter yok</option>';
                        } else {
                            data.forEach(kriter => {
                                const option = document.createElement('option');
                                option.value = kriter.id;
                                option.textContent = kriter.kriter_ad;
                                kriterSelect.appendChild(option);
                            });
                            kriterSelect.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Hata:', error);
                        kriterSelect.innerHTML = '<option value="">Hata oluştu</option>';
                    });
            } else {
                kriterSelect.innerHTML = '<option value="">Önce kategori seçin...</option>';
                kriterSelect.disabled = true;
            }
        });
    }

    // Ana ve Hedef için listenerları başlat
    loadCriteria('ana_kategori_id', 'ana_kriter_id');
    loadCriteria('hedef_kategori_id', 'hedef_kriter_id');

    // Aynı kategori seçimini engelleme (Basit validasyon)
    document.getElementById('uyumlulukForm').addEventListener('submit', function(e) {
        const ana = document.getElementById('ana_kategori_id').value;
        const hedef = document.getElementById('hedef_kategori_id').value;

        if (ana && hedef && ana === hedef) {
            e.preventDefault();
            alert('Ana Kategori ve Hedef Kategori aynı olamaz! Lütfen farklı kategoriler seçin.');
        }
    });

});
</script>
@endsection