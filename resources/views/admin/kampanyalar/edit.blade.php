@extends('layouts.admin')

@section('title', 'Kampanya Düzenle - Admin Panel')

@section('content')
<div class="container-xxl flex-grow-1 container-py-4">
    <h4 class="fw-bold py-3 mb-4">Kampanya Düzenle</h4>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.kampanyalar.update', $kampanya->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Ürün</label>
                    <select name="urun_id" class="form-select" required>
                        <option value="">Seçiniz</option>
                        @foreach($urunler as $urun)
                            <option value="{{ $urun->id }}" {{ $urun->id == $kampanya->urun_id ? 'selected' : '' }}>
                                {{ $urun->urun_ad }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kampanya Adı</label>
                    <input type="text" name="kampanya_adi" class="form-control" value="{{ old('kampanya_adi', $kampanya->kampanya_adi) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">İndirim Oranı (%)</label>
                    <input type="number" step="0.01" name="indirim_orani" class="form-control" value="{{ old('indirim_orani', $kampanya->indirim_orani) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Yeni Fiyat</label>
                    <input type="number" step="0.01" name="yeni_fiyat" class="form-control" value="{{ old('yeni_fiyat', $kampanya->yeni_fiyat) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Başlangıç Tarihi</label>
                    <input type="date" name="baslangic_tarihi" class="form-control" value="{{ old('baslangic_tarihi', $kampanya->baslangic_tarihi) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Bitiş Tarihi</label>
                    <input type="date" name="bitis_tarihi" class="form-control" value="{{ old('bitis_tarihi', $kampanya->bitis_tarihi) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Aktif mi?</label>
                    <select name="aktif" class="form-select">
                        <option value="1" {{ $kampanya->aktif ? 'selected' : '' }}>Evet</option>
                        <option value="0" {{ !$kampanya->aktif ? 'selected' : '' }}>Hayır</option>
                    </select>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save me-1"></i> Güncelle
                    </button>
                    <a href="{{ route('admin.kampanyalar.index') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back me-1"></i> Geri Dön
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
