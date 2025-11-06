@extends('layouts.app')
@section('title', 'Fatura ')
@section('content')
<div class="container py-5">
    <h2>Fatura</h2>

    <div class="card mb-4">
        <div class="card-body">
            <h5>Fatura No: {{ $fatura->fatura_no }}</h5>
            <p><strong>Unvan:</strong> {{ $fatura->unvan }}</p>
            <p><strong>Vergi Dairesi:</strong> {{ $fatura->vergi_dairesi }}</p>
            <p><strong>Vergi No:</strong> {{ $fatura->vergi_no }}</p>
            <p><strong>TCKN:</strong> {{ $fatura->tc_kimlik_no }}</p>
            <p><strong>Fatura Adresi:</strong> {{ $fatura->fatura_adresi }}</p>
        </div>
    </div>

    <h4>Sipariş Bilgileri</h4>
    <p><strong>Sipariş No:</strong> {{ $fatura->siparis->siparis_no }}</p>
    <p><strong>Ara Toplam:</strong> {{ number_format($fatura->ara_toplam,2) }} TL</p>
    <p><strong>KDV:</strong> {{ number_format($fatura->kdv_tutari,2) }} TL</p>
    <p><strong>Genel Toplam:</strong> {{ number_format($fatura->genel_toplam,2) }} TL</p>
</div>
@endsection
