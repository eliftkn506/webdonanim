@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                "{{ $kriter->kriter_ad ?? '' }}" Kriterine Yeni Değer Ekle
            </h6>
        </div>
        <div class="card-body">

            {{-- Hata Gösterimi --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- DÜZELTME: Rota ismi 'admin.kriterdegerleri.store' yapıldı --}}
            <form action="{{ route('admin.kriterdegerleri.store') }}" method="POST">
                @csrf
                
                {{-- Controller'a hangi kritere ekleme yaptığımızı söylüyoruz --}}
                <input type="hidden" name="kriter_id" value="{{ $kriter->id }}">

                <div class="form-group mb-3">
                    <label for="deger">Değer Adı</label>
                    <input type="text" name="deger" class="form-control" id="deger" placeholder="Örn: 32 GB, Kırmızı, Large..." required>
                </div>

                <button type="submit" class="btn btn-success">Kaydet</button>
                
                {{-- DÜZELTME: İptal butonu 'admin.kriterdegerleri.index' rotasına gider --}}
                <a href="{{ route('admin.kriterdegerleri.index', $kriter->id) }}" class="btn btn-secondary">İptal</a>
            </form>

        </div>
    </div>
</div>
@endsection