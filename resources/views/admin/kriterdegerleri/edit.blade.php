@extends('admin.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Değer Düzenle</h6>
        </div>
        <div class="card-body">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- DÜZELTME: admin.kriterdegerleri.update --}}
            <form action="{{ route('admin.kriterdegerleri.update', $kriterDeger->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label for="deger">Değer Adı</label>
                    <input type="text" name="deger" class="form-control" id="deger" value="{{ $kriterDeger->deger }}" required>
                </div>

                <button type="submit" class="btn btn-primary">Güncelle</button>
                
                {{-- DÜZELTME: İptal butonu --}}
                <a href="{{ route('admin.kriterdegerleri.index', $kriterDeger->kriter_id) }}" class="btn btn-secondary">İptal</a>
            </form>

        </div>
    </div>
</div>
@endsection