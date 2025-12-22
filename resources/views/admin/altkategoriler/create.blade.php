@extends('layouts.admin')
@section('title', 'Alt Kategori Ekle')

@section('content')
<div class="container-xxl flex-grow-1 container-py-4">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Kategori: {{ $kategori->kategori_ad }} /</span> Yeni Alt Kategori</h4>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.altkategoriler.store') }}" method="POST">
                @csrf
                <input type="hidden" name="kategori_id" value="{{ $kategori->id }}">

                <div class="mb-3">
                    <label class="form-label">Üst Kategori</label>
                    <input type="text" class="form-control" value="{{ $kategori->kategori_ad }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alt Kategori Adı</label>
                    <input type="text" name="alt_kategori_ad" class="form-control" placeholder="Örn: Laptop, Televizyon" required autofocus>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                    <a href="{{ route('admin.kategoriler.altkategoriler', $kategori->id) }}" class="btn btn-label-secondary">İptal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection