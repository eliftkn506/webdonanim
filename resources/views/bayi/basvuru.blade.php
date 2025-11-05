@extends('layouts.app')

@section('title', 'Bayi Başvuru')

@section('content')
<div class="container mt-5">
    <h2>Bayi Başvuru Formu</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('bayi.basvuru.submit') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Firma Adı</label>
            <input type="text" name="firma_adi" class="form-control" value="{{ old('firma_adi') }}">
            @error('firma_adi')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="mb-3">
            <label>Yetkili Ad</label>
            <input type="text" name="yetkili_ad" class="form-control" value="{{ old('yetkili_ad') }}">
            @error('yetkili_ad')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="mb-3">
            <label>Yetkili Soyad</label>
            <input type="text" name="yetkili_soyad" class="form-control" value="{{ old('yetkili_soyad') }}">
            @error('yetkili_soyad')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
            @error('email')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="mb-3">
            <label>Telefon</label>
            <input type="text" name="telefon" class="form-control" value="{{ old('telefon') }}">
            @error('telefon')<small class="text-danger">{{ $message }}</small>@enderror
        </div>

        <div class="mb-3">
            <label>Adres</label>
            <textarea name="adres" class="form-control">{{ old('adres') }}</textarea>
        </div>

        <div class="mb-3">
            <label>Vergi No</label>
            <input type="text" name="vergi_no" class="form-control" value="{{ old('vergi_no') }}">
        </div>

        <button type="submit" class="btn btn-primary">Başvur</button>
    </form>
</div>
@endsection
