@extends('layouts.admin')
@section('title', 'Yeni Kupon Ekle')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold py-3 mb-0">Yeni Kupon</h4>
        <a href="{{ route('admin.kuponlar.index') }}" class="btn btn-outline-secondary">İptal</a>
    </div>

    <form action="{{ route('admin.kuponlar.store') }}" method="POST">
        @csrf
        @include('admin.kuponlar.form', ['kupon' => null])
        
        <div class="d-flex justify-content-end gap-2 mb-5">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bx bx-check me-1"></i> Kuponu Oluştur
            </button>
        </div>
    </form>
</div>
@endsection