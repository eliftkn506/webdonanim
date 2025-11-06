@extends('layouts.app')
@section('title', 'Üye Ol ')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center fw-bold fs-4 rounded-top">
                    Kayıt Ol
                </div>

                <div class="card-body p-4">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Ad Soyad -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Ad Soyad</label>
                            <input id="name" type="text" 
                                class="form-control @error('name') is-invalid @enderror" 
                                name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- E-posta -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">E-posta Adresi</label>
                            <input id="email" type="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Şifre -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Şifre</label>
                            <input id="password" type="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                name="password" required>
                            @error('password')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Şifre Tekrar -->
                        <div class="mb-3">
                            <label for="password-confirm" class="form-label fw-semibold">Şifre Tekrar</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                        </div>

                        <!-- Kayıt Butonu -->
                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                                Kayıt Ol
                            </button>
                        </div>

                        <!-- Bayi Başvurusu Butonu -->
                        <div class="d-grid">
                            <a href="{{ route('bayi.basvuru.form') }}" class="btn btn-success btn-lg rounded-pill">
                                Bayi Başvurusu Yap
                            </a>
                        </div>
                    </form>
                </div>

                <div class="card-footer text-center text-muted">
                    Zaten bir hesabınız var mı? <a href="{{ route('login') }}" class="text-decoration-none">Giriş Yapın</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

