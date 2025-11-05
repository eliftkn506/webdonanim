@extends('layouts.admin')
@section('title', 'Yeni Kupon Ekle')

@section('content')
<h4>Yeni Kupon Ekle</h4>

@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.kuponlar.store') }}" method="POST">
    @csrf
    @include('admin.kuponlar.form')
    <button class="btn btn-primary">Kaydet</button>
</form>
@endsection
