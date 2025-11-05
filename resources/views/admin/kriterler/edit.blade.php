@extends('layouts.admin')

@section('title', 'Kriter Düzenle - Admin Panel')

@section('content')
<div class="container-xxl flex-grow-1 container-py-4">
    <h4 class="fw-bold py-3 mb-4">Kriter Düzenle</h4>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.kriterler.update', $kriter->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Alt Kategori -->
                <div class="mb-3">
                    <label class="form-label">Alt Kategori</label>
                    <select name="alt_kategori_id" class="form-select" required>
                        @foreach($altkategoriler as $altkategori)
                            <option value="{{ $altkategori->id }}" {{ $kriter->alt_kategori_id == $altkategori->id ? 'selected' : '' }}>
                                {{ $altkategori->alt_kategori_ad }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Kriter Adı -->
                <div class="mb-3">
                    <label class="form-label">Kriter Adı</label>
                    <input type="text" name="kriter_ad" value="{{ $kriter->kriter_ad }}" class="form-control" required>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save me-1"></i> Güncelle
                    </button>
                    <a href="{{ route('admin.kriterler.index') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back me-1"></i> Geri
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
