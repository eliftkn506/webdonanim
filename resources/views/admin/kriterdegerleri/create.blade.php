@extends('layouts.admin')

@section('title', 'Yeni Kriter Değeri Ekle - Admin Panel')

@section('content')
<div class="container-xxl flex-grow-1 container-py-4">
    <h4 class="fw-bold py-3 mb-4">Yeni Kriter Değeri Ekle</h4>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.kriterdegerleri.store') }}" method="POST">
                @csrf

                <!-- Alt Kategori -->
                <div class="mb-3">
                    <label for="alt_kategori_id" class="form-label">Alt Kategori</label>
                    <select name="alt_kategori_id" id="alt_kategori_id" class="form-select" required>
                        <option value="">Seçiniz</option>
                        @foreach($altkategoriler as $altkategori)
                            <option value="{{ $altkategori->id }}">{{ $altkategori->alt_kategori_ad }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Kriter -->
                <div class="mb-3">
                    <label for="kriter_id" class="form-label">Kriter</label>
                    <select name="kriter_id" id="kriter_id" class="form-select" required>
                        <option value="">Önce alt kategori seçin</option>
                    </select>
                </div>

                <!-- Değer -->
                <div class="mb-3">
                    <label for="deger" class="form-label">Değer</label>
                    <input type="text" name="deger" class="form-control" required>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="bx bx-plus me-1"></i> Kaydet
                    </button>
                    <a href="{{ route('admin.kriterdegerleri.index') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back me-1"></i> Geri
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('alt_kategori_id').addEventListener('change', function() {
    let altKategoriId = this.value;
    let kriterSelect = document.getElementById('kriter_id');
    kriterSelect.innerHTML = '<option value="">Yükleniyor...</option>';

    if (altKategoriId) {
        fetch('/admin/kriterdegerleri/kriterler/' + altKategoriId)
            .then(response => response.json())
            .then(data => {
                let options = '<option value="">Seçiniz</option>';
                data.forEach(function(kriter) {
                    options += `<option value="${kriter.id}">${kriter.kriter_ad}</option>`;
                });
                kriterSelect.innerHTML = options;
            });
    } else {
        kriterSelect.innerHTML = '<option value="">Önce alt kategori seçin</option>';
    }
});
</script>
@endsection
