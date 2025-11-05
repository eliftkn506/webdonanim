@extends('layouts.admin')
@section('title', 'Kupon Düzenle')

@section('content')
<h4>Kupon Düzenle</h4>

@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.kuponlar.update', $kupon->id) }}" method="POST">
    @csrf
    @method('PUT')
    @include('admin.kuponlar.form', ['kupon' => $kupon])
    <button class="btn btn-primary">Güncelle</button>
</form>
@endsection
