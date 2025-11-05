@extends('layouts.admin')

@section('title', 'Kriter Değeri Düzenle - Admin Panel')

@section('content')
<div class="container-xxl flex-grow-1 container-py-4">
    <h4 class="fw-bold py-3 mb-4">Kriter Değeri Düzenle</h4>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.kriterdegerleri.update', $kriterDeger) }}" method="POST">

                @csrf
                @method('PUT')

                <!-- Alt Kategori -->
                <div class="mb-3">
                    <label for="alt_kategori_id" class="form-label">Alt Kategori</label>
                    <select name="alt_kategori_id" id="alt_kategori_id" class="form-select" required>
                        @foreach($altkategoriler as $altkategori)
                            <option value="{{ $altkategori->id }}" {{ $kriterDeger->alt_kategori_id == $altkategori->id ? 'selected' : '' }}>
                                {{ $altkategori->alt_kategori_ad }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Kriter -->
                <div class="mb-3">
                    <label for="kriter_id" class="form-label">Kriter</label>
                    <select name="kriter_id" id="kriter_id" class="form-select" required>
                        @foreach($kriterler as $kriter)
                            <option value="{{ $kriter->id }}" {{ $kriterDeger->kriter_id == $kriter->id ? 'selected' : '' }}>
                                {{ $kriter->kriter_ad }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Değer -->
                <div class="mb-3">
                    <label for="deger" class="form-label">Değer</label>
                    <input type="text" name="deger" value="{{ $kriterDeger->deger }}" class="form-control" required>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save me-1"></i> Güncelle
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
    let selectedKriterId = "{{ $kriterDeger->kriter_id }}";

    kriterSelect.innerHTML = '<option>Yükleniyor...</option>';

    if (altKategoriId) {
        fetch('/admin/kriterdegerleri/kriterler/' + altKategoriId)
            .then(response => response.json())
            .then(data => {
                let options = '<option value="">Seçiniz</option>';
                data.forEach(function(kriter) {
                    let selected = (kriter.id == selectedKriterId) ? 'selected' : '';
                    options += `<option value="${kriter.id}" ${selected}>${kriter.kriter_ad}</option>`;
                });
                kriterSelect.innerHTML = options;
            });
    } else {
        kriterSelect.innerHTML = '<option value="">Önce alt kategori seçin</option>';
    }
});
</script>
@endsection
