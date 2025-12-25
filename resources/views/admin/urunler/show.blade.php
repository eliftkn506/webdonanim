@extends('layouts.admin')

@section('title', $urun->urun_ad . ' - Detay')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">
            <span class="text-muted fw-light">Ürünler /</span> Ürün Detayı
        </h4>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.urunler.index') }}" class="btn btn-label-secondary">
                <i class="bx bx-arrow-back me-1"></i> Geri Dön
            </a>
            <a href="{{ route('admin.urunler.edit', $urun->id) }}" class="btn btn-warning">
                <i class="bx bx-edit me-1"></i> Düzenle
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-4 col-lg-5 col-md-5">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="user-avatar-section">
                        <div class="d-flex align-items-center flex-column">
                            @if($urun->resim_url && file_exists(public_path($urun->resim_url)))
                                <img class="img-fluid rounded my-4" src="{{ asset($urun->resim_url) }}" height="110" width="110" alt="Ürün Resmi" style="object-fit: cover;">
                            @else
                                <div class="avatar avatar-xl my-4">
                                    <span class="avatar-initial rounded bg-label-secondary fs-1">
                                        <i class='bx bx-package'></i>
                                    </span>
                                </div>
                            @endif
                            <div class="user-info text-center">
                                <h4 class="mb-2">{{ $urun->urun_ad }}</h4>
                                <span class="badge bg-label-primary mt-1">{{ $urun->marka }} - {{ $urun->model }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-around flex-wrap my-4 py-3 border-top border-bottom">
                        <div class="d-flex align-items-start me-4 mt-3 gap-3">
                            <span class="badge bg-label-primary p-2 rounded"><i class='bx bx-check bx-sm'></i></span>
                            <div>
                                <h5 class="mb-0">{{ $urun->stok }}</h5>
                                <span>Mevcut Stok</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-start mt-3 gap-3">
                            <span class="badge bg-label-success p-2 rounded"><i class='bx bx-layer bx-sm'></i></span>
                            <div>
                                <h5 class="mb-0">{{ $urun->varyasyonlar->count() }}</h5>
                                <span>Varyasyon</span>
                            </div>
                        </div>
                    </div>

                    <h5 class="pb-2 border-bottom mb-4">Detaylar</h5>
                    <div class="info-container">
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <span class="fw-bold me-2">Kategori:</span>
                                <span>{{ $urun->altKategori->kategori->kategori_ad ?? '-' }} / {{ $urun->altKategori->alt_kategori_ad ?? '-' }}</span>
                            </li>
                            <li class="mb-3">
                                <span class="fw-bold me-2">Barkod:</span>
                                <span>{{ $urun->barkod_no ?? 'Yok' }}</span>
                            </li>
                            <li class="mb-3">
                                <span class="fw-bold me-2">Oluşturulma:</span>
                                <span>{{ $urun->created_at->format('d.m.Y H:i') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Ürün Açıklaması</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0 text-muted">
                        {{ $urun->aciklama ?? 'Bu ürün için açıklama girilmemiş.' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7 col-md-7">
            
            <div class="nav-align-top mb-4">
                <ul class="nav nav-pills mb-3" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-fiyatlar">
                            <i class="bx bx-dollar me-1"></i> Fiyatlar
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-varyasyonlar">
                            <i class="bx bx-layer me-1"></i> Varyasyonlar
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-ozellikler">
                            <i class="bx bx-list-check me-1"></i> Özellikler/Kriterler
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-uyumlu">
                            <i class="bx bx-link me-1"></i> Uyumlu Ürünler
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content shadow-sm">
                    
                    <div class="tab-pane fade show active" id="navs-fiyatlar" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-header p-0">Fiyat Listesi & Geçmişi</h5>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalFiyatEkle">
                                <i class="bx bx-plus me-1"></i> Yeni Fiyat Tanımla
                            </button>
                        </div>
                        <div class="table-responsive text-nowrap border rounded">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Fiyat Türü</th>
                                        <th>Maliyet</th>
                                        <th>Kar %</th>
                                        <th>Satış Fiyatı (KDV Dahil)</th>
                                        <th>Tarih Aralığı</th>
                                        <th width="50">İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($urun->fiyatlar as $fiyat)
                                        @php
                                            $temel = $fiyat->maliyet + ($fiyat->maliyet * $fiyat->kar_orani / 100);
                                            if ($fiyat->bayi_indirimi > 0) $temel -= ($temel * $fiyat->bayi_indirimi / 100);
                                            $satis = $temel + ($temel * $fiyat->vergi_orani / 100);
                                            
                                            // Aktiflik kontrolü
                                            $isActive = (is_null($fiyat->bitis_tarihi) || $fiyat->bitis_tarihi >= now()) && $fiyat->baslangic_tarihi <= now();
                                        @endphp
                                        <tr class="{{ $isActive ? 'table-success' : '' }}">
                                            <td>
                                                @if($fiyat->fiyat_turu === 'standart')
                                                    <span class="badge bg-label-info">Standart</span>
                                                @elseif($fiyat->fiyat_turu === 'bayi')
                                                    <span class="badge bg-label-warning">Bayi</span>
                                                @else
                                                    <span class="badge bg-label-success">Kampanya</span>
                                                @endif
                                            </td>
                                            <td>₺{{ number_format($fiyat->maliyet, 2) }}</td>
                                            <td>%{{ $fiyat->kar_orani }}</td>
                                            <td><strong class="text-primary">₺{{ number_format($satis, 2) }}</strong></td>
                                            <td>
                                                <small class="d-block">Baş: {{ \Carbon\Carbon::parse($fiyat->baslangic_tarihi)->format('d.m.Y') }}</small>
                                                @if($fiyat->bitis_tarihi)
                                                    <small class="d-block text-danger">Bit: {{ \Carbon\Carbon::parse($fiyat->bitis_tarihi)->format('d.m.Y') }}</small>
                                                @else
                                                    <small class="text-success">Süresiz (Aktif)</small>
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.urunler.fiyat.delete', $fiyat->fiyat_id) }}" method="POST" onsubmit="return confirm('Bu fiyatı silmek istediğinize emin misiniz?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-icon btn-sm btn-label-danger">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center">Fiyat bulunamadı.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="navs-varyasyonlar" role="tabpanel">
                        <h5 class="card-header p-0 mb-3">Varyasyon Stokları</h5>
                        <div class="table-responsive text-nowrap border rounded">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Varyasyon Detayı</th>
                                        <th>Barkod No</th>
                                        <th>Stok</th>
                                        <th>Durum</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($urun->varyasyonlar as $varyasyon)
                                        <tr>
                                            <td>
                                                @php
                                                    $vKriterler = \App\Models\UrunVaryasyonKriterDegeri::where('urun_varyasyon_id', $varyasyon->id)
                                                                        ->with(['kriter', 'kriterDeger'])->get();
                                                @endphp
                                                @foreach($vKriterler as $vk)
                                                    <span class="badge bg-label-secondary me-1">
                                                        {{ $vk->kriter->kriter_ad ?? '' }}: 
                                                        <strong class="text-dark">{{ $vk->kriterDeger->deger ?? '' }}</strong>
                                                    </span>
                                                @endforeach
                                            </td>
                                            <td>{{ $varyasyon->barkod_no ?? '-' }}</td>
                                            <td>
                                                @if($varyasyon->stok < 5)
                                                    <span class="text-danger fw-bold">{{ $varyasyon->stok }}</span>
                                                @else
                                                    <span class="text-success fw-bold">{{ $varyasyon->stok }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($varyasyon->stok > 0)
                                                    <span class="badge bg-success">Var</span>
                                                @else
                                                    <span class="badge bg-danger">Tükendi</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center">Varyasyon bulunamadı.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="navs-ozellikler" role="tabpanel">
                        <h5 class="card-header p-0 mb-3">Ürün Teknik Özellikleri</h5>
                        <div class="alert alert-info">
                            <i class="bx bx-info-circle me-1"></i> Bu alan ürünün genel (varyasyondan bağımsız) teknik özelliklerini gösterir.
                        </div>
                        
                        <div class="row g-3">
                             @forelse($urun->kriterDegerleri as $kriterDeger)
                                 <div class="col-md-6">
                                     <div class="border rounded p-3 h-100">
                                         <small class="text-muted d-block mb-1 text-uppercase fw-bold" style="font-size: 0.75rem;">
                                             {{ $kriterDeger->pivot->kriter_id ? \App\Models\Kriter::find($kriterDeger->pivot->kriter_id)->kriter_ad : 'Kriter' }}
                                         </small>
                                         <span class="fw-bold fs-5 text-dark">{{ $kriterDeger->deger }}</span>
                                     </div>
                                 </div>
                             @empty
                                 <div class="col-12">
                                     <div class="text-center py-3 text-muted">
                                         Bu ürün için atanmış genel özellik bulunmamaktadır.
                                     </div>
                                 </div>
                             @endforelse
                        </div>
                    </div>

                    <div class="tab-pane fade" id="navs-uyumlu" role="tabpanel">
                        <h5 class="card-header p-0 mb-3">Uyumlu Ürünler</h5>
                        <div class="table-responsive text-nowrap border rounded">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="60">Görsel</th>
                                        <th>Ürün Adı</th>
                                        <th>Marka/Model</th>
                                        <th>Kategori</th>
                                        <th>Stok</th>
                                        <th>İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $uyumluUrunler = $urun->uyumluUrunler()->with('uyumluUrun.altKategori.kategori')->get();
                                    @endphp
                                    
                                    @forelse($uyumluUrunler as $kayit)
                                        @php $u = $kayit->uyumluUrun; @endphp
                                        @if($u)
                                        <tr onclick="window.location='{{ route('admin.urunler.show', $u->id) }}'" style="cursor: pointer;">
                                            <td>
                                                <div class="avatar avatar-sm">
                                                    @if($u->resim_url && file_exists(public_path($u->resim_url)))
                                                        <img src="{{ asset($u->resim_url) }}" alt="img" class="rounded-circle" style="object-fit: cover;">
                                                    @else
                                                        <span class="avatar-initial rounded-circle bg-label-secondary"><i class='bx bx-package'></i></span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td><strong>{{ $u->urun_ad }}</strong></td>
                                            <td>{{ $u->marka }} - {{ $u->model }}</td>
                                            <td>
                                                <span class="badge bg-label-info">
                                                    {{ $u->altKategori->kategori->kategori_ad ?? '-' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($u->stok > 0)
                                                    <span class="badge bg-label-success">{{ $u->stok }}</span>
                                                @else
                                                    <span class="badge bg-label-danger">Yok</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.urunler.show', $u->id) }}" class="btn btn-sm btn-icon btn-label-primary">
                                                    <i class="bx bx-show"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="bx bx-link-alt fs-1 text-muted mb-2"></i>
                                                    <p class="text-muted mb-0">Bu ürünle eşleşen uyumlu ürün bulunamadı.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalFiyatEkle" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white">Yeni Fiyat Tanımla</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.urunler.fiyat.store', $urun->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        
                        <div class="col-md-4">
                            <label class="form-label">Fiyat Türü</label>
                            <select name="fiyat_turu" class="form-select" id="inp_fiyat_turu">
                                <option value="standart" selected>Standart Satış</option>
                                <option value="bayi">Bayi Fiyatı</option>
                                <option value="kampanya">Kampanya/İndirim</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Başlangıç Tarihi</label>
                            <input type="date" name="baslangic_tarihi" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Bitiş Tarihi (Opsiyonel)</label>
                            <input type="date" name="bitis_tarihi" class="form-control">
                            <small class="text-muted">Boş bırakılırsa süresiz olur.</small>
                        </div>

                        <hr class="my-2">

                        <div class="col-md-3">
                            <label class="form-label fw-bold text-dark">Maliyet (₺)</label>
                            <input type="number" step="0.01" name="maliyet" id="inp_maliyet" class="form-control" placeholder="0.00" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold text-dark">Kar Oranı (%)</label>
                            <input type="number" step="0.01" name="kar_orani" id="inp_kar" class="form-control" value="30" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Vergi (KDV %)</label>
                            <select name="vergi_orani" id="inp_vergi" class="form-select">
                                <option value="0">0%</option>
                                <option value="1">1%</option>
                                <option value="10">10%</option>
                                <option value="20" selected>20%</option>
                            </select>
                        </div>

                        <div class="col-md-3" id="div_bayi_indirim" style="display:none;">
                            <label class="form-label text-warning">Bayi İndirimi (%)</label>
                            <input type="number" step="0.01" name="bayi_indirimi" id="inp_bayi_indirim" class="form-control" value="0">
                        </div>

                        <div class="col-12 mt-4">
                            <div class="card bg-label-secondary border border-secondary">
                                <div class="card-body p-3">
                                    <h6 class="card-title mb-3 border-bottom pb-2">Hesaplama Özeti</h6>
                                    <div class="row text-center">
                                        <div class="col">
                                            <small class="d-block text-muted">Maliyet</small>
                                            <strong class="fs-5" id="out_maliyet">0.00 ₺</strong>
                                        </div>
                                        <div class="col">
                                            <i class="bx bx-plus text-muted"></i>
                                        </div>
                                        <div class="col">
                                            <small class="d-block text-muted">Kar Tutarı</small>
                                            <strong class="fs-5 text-success" id="out_kar">0.00 ₺</strong>
                                        </div>
                                        <div class="col">
                                            <i class="bx bx-plus text-muted"></i>
                                        </div>
                                        <div class="col">
                                            <small class="d-block text-muted">KDV Tutarı</small>
                                            <strong class="fs-5" id="out_kdv">0.00 ₺</strong>
                                        </div>
                                        <div class="col border-start">
                                            <small class="d-block text-primary fw-bold">SATIŞ FİYATI</small>
                                            <strong class="fs-4 text-primary" id="out_satis">0.00 ₺</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary">Fiyatı Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inpMaliyet = document.getElementById('inp_maliyet');
        const inpKar = document.getElementById('inp_kar');
        const inpVergi = document.getElementById('inp_vergi');
        const inpBayiIndirim = document.getElementById('inp_bayi_indirim');
        const inpTur = document.getElementById('inp_fiyat_turu');
        const divBayi = document.getElementById('div_bayi_indirim');

        // Çıktı Alanları
        const outMaliyet = document.getElementById('out_maliyet');
        const outKar = document.getElementById('out_kar');
        const outKdv = document.getElementById('out_kdv');
        const outSatis = document.getElementById('out_satis');

        function hesapla() {
            let maliyet = parseFloat(inpMaliyet.value) || 0;
            let karOrani = parseFloat(inpKar.value) || 0;
            let vergiOrani = parseFloat(inpVergi.value) || 0;
            let bayiIndirimi = parseFloat(inpBayiIndirim.value) || 0;
            let tur = inpTur.value;

            // Kar Hesabı
            let karTutari = maliyet * (karOrani / 100);
            let karsizFiyat = maliyet + karTutari;

            // Bayi İndirimi Varsa (Net fiyattan düşüyoruz)
            if(tur === 'bayi' && bayiIndirimi > 0) {
                karsizFiyat = karsizFiyat - (karsizFiyat * (bayiIndirimi / 100));
            }

            // Vergi Hesabı
            let kdvTutari = karsizFiyat * (vergiOrani / 100);
            let satisFiyati = karsizFiyat + kdvTutari;

            // Ekrana Yazma
            outMaliyet.innerText = maliyet.toFixed(2) + ' ₺';
            outKar.innerText = karTutari.toFixed(2) + ' ₺';
            outKdv.innerText = kdvTutari.toFixed(2) + ' ₺';
            outSatis.innerText = satisFiyati.toFixed(2) + ' ₺';
        }

        // Event Listeners
        [inpMaliyet, inpKar, inpVergi, inpBayiIndirim].forEach(el => {
            el.addEventListener('input', hesapla);
        });

        // Fiyat türü değişince bayi indirimi alanını göster/gizle
        inpTur.addEventListener('change', function() {
            if(this.value === 'bayi') {
                divBayi.style.display = 'block';
            } else {
                divBayi.style.display = 'none';
                inpBayiIndirim.value = 0;
            }
            hesapla();
        });
    });
</script>

@endsection