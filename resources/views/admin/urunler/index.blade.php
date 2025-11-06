@extends('layouts.admin')

@section('title', 'Ürünler - Admin Panel')

@section('content')
<div class="container-xxl flex-grow-1 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Ürünler</h4>
        <a href="{{ route('admin.urunler.create') }}" class="btn btn-primary">
            <i class="bx bx-plus me-1"></i> Yeni Ürün Ekle
        </a>
    </div>

    {{-- Başarı Mesajları --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Hata Mesajları (Silme hatası burada görünecek) --}}
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        {{-- Sneat/Bootstrap 5 için responsif tablo yapısı --}}
        <div class="table-responsive text-nowrap">
            <table class="table table-hover table-borderless align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Alt Kategori</th>
                        <th>Ürün Adı</th>
                        <th>Marka</th>
                        <th>Model</th>
                        <th>Fiyat(lar)</th>
                        <th>Resim</th>
                        <th>Barkod No</th>
                        <th>Stok</th>
                        <th class="text-center">İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($urunler as $urun)
                        <tr class="table-primary">
                            <td>{{ $urun->id }}</td>
                            <td>{{ $urun->altKategori->alt_kategori_ad ?? '-' }}</td>
                            <td>{{ $urun->urun_ad }}</td>
                            <td>{{ $urun->marka }}</td>
                            <td>{{ $urun->model }}</td>
                            <td>
                                {{-- Fiyat Listeleme --}}
                                @forelse($urun->fiyatlar as $fiyat)
                                    @php
                                        $temel = $fiyat->maliyet + ($fiyat->maliyet * $fiyat->kar_orani / 100);
                                        if ($fiyat->bayi_indirimi > 0) {
                                            $temel -= ($temel * $fiyat->bayi_indirimi / 100);
                                        }
                                        $hesaplanmis = $temel + ($temel * $fiyat->vergi_orani / 100);
                                    @endphp

                                    <div class="mb-2 p-2 border rounded bg-light">
                                        <strong>{{ ucfirst($fiyat->fiyat_turu) }}</strong>:
                                        ₺{{ number_format($hesaplanmis, 2, ',', '.') }}

                                        @if($fiyat->pivot)
                                            <br>
                                            <small class="text-muted">
                                                {{ $fiyat->pivot->baslangic_tarihi }} - {{ $fiyat->pivot->bitis_tarihi ?? 'Süresiz' }}
                                            </small>
                                        @endif
                                    </div>
                                @empty
                                    <span class="text-muted">Tanımlı fiyat yok</span>
                                @endforelse
                            </td>
                            <td>
                                {{-- Resim Gösterimi --}}
                                @if($urun->resim_url && file_exists(public_path($urun->resim_url)))
                                    <img src="{{ asset($urun->resim_url) }}" width="60" class="rounded shadow-sm">
                                @else
                                    <span class="text-muted">Yok</span>
                                @endif
                            </td>
                            <td>{{ $urun->barkod_no ?? '-' }}</td>
                            <td>{{ $urun->stok }}</td>
                            <td class="text-center">
                                {{-- İşlemler --}}
                                <a href="{{ route('admin.urunler.edit', $urun->id) }}" class="btn btn-sm btn-warning me-1">
                                    <i class="bx bx-edit me-1"></i> Düzenle
                                </a>

                                <button class="btn btn-sm btn-success me-1" data-bs-toggle="modal" data-bs-target="#fiyatModal{{ $urun->id }}">
                                    <i class="bx bx-plus me-1"></i> Fiyat Ekle
                                </button>

                                <form action="{{ route('admin.urunler.destroy', $urun->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bx bx-trash me-1"></i> Sil
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <div class="modal fade" id="fiyatModal{{ $urun->id }}" tabindex="-1" aria-labelledby="fiyatModalLabel{{ $urun->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.urunler.fiyat.store', $urun->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="fiyatModalLabel{{ $urun->id }}">Fiyat Ata - {{ $urun->urun_ad }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                                        </div>
                                        <div class="modal-body">

                                            <div class="mb-3">
                                                <label>Fiyat Türü</label>
                                                <select id="fiyatTuru{{ $urun->id }}" class="form-control">
                                                    <option value="">Hepsi</option>
                                                    <option value="standart">Standart</option>
                                                    <option value="bayi">Bayi</option>
                                                    <option value="kampanya">Kampanya</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label>Fiyat Seç <span class="text-danger">*</span></label>
                                                <select name="fiyat_id" id="fiyatSelect{{ $urun->id }}" class="form-control" required>
                                                    <option value="">Seçiniz</option>
                                                    @foreach($fiyatlar as $fiyat)
                                                        @php
                                                            $temel_modal = $fiyat->maliyet + ($fiyat->maliyet * $fiyat->kar_orani / 100);
                                                            if ($fiyat->bayi_indirimi > 0) {
                                                                $temel_modal -= ($temel_modal * $fiyat->bayi_indirimi / 100);
                                                            }
                                                            $hesaplanmis_modal = $temel_modal + ($temel_modal * $fiyat->vergi_orani / 100);
                                                        @endphp
                                                        <option data-tur="{{ $fiyat->fiyat_turu }}" value="{{ $fiyat->fiyat_id }}">
                                                            {{ ucfirst($fiyat->fiyat_turu) }} - ₺{{ number_format($hesaplanmis_modal, 2, ',', '.') }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label>Başlangıç Tarihi <span class="text-danger">*</span></label>
                                                <input type="date" name="baslangic_tarihi" class="form-control" value="{{ date('Y-m-d') }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label>Bitiş Tarihi</label>
                                                <input type="date" name="bitis_tarihi" class="form-control">
                                                <small class="text-muted">Boş bırakırsanız süresiz olacak</small>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <a href="{{ route('admin.fiyatlar.create') }}" class="btn btn-outline-primary me-auto">
                                                <i class="bx bx-plus me-1"></i> Yeni Fiyat Oluştur
                                            </a>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                                            <button type="submit" class="btn btn-success">Kaydet</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <script>
                            document.getElementById('fiyatTuru{{ $urun->id }}').addEventListener('change', function() {
                                let secilenTur = this.value;
                                let options = document.querySelectorAll('#fiyatSelect{{ $urun->id }} option');

                                options.forEach(option => {
                                    if (!secilenTur || option.dataset.tur === secilenTur || option.value === "") {
                                        option.style.display = '';
                                    } else {
                                        option.style.display = 'none';
                                    }
                                });
                                document.getElementById('fiyatSelect{{ $urun->id }}').value = "";
                            });
                        </script>

                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">Henüz ürün eklenmemiş</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-end p-3">
            {{ $urunler->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection