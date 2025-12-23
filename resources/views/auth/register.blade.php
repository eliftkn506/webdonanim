@extends('layouts.app')
@section('title', 'Yeni Üyelik Oluştur - Avantaj Bilişim')

@section('content')
<style>
    :root {
        --primary-turq: #00d4aa;
        --secondary-navy: #1e293b;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .register-section {
        min-height: 85vh;
        display: flex;
        align-items: center;
        padding: 60px 0;
    }

    .register-card {
        background: white;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 20px 40px -10px rgba(30, 41, 59, 0.05);
        overflow: hidden;
    }

    .register-header {
        background: var(--secondary-navy);
        padding: 45px 30px;
        text-align: center;
        color: white;
        position: relative;
    }

    .register-header::after {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(var(--primary-turq) 1px, transparent 1px);
        background-size: 20px 20px;
        opacity: 0.1;
    }

    .register-header h2 {
        font-weight: 800;
        font-size: 1.85rem;
        margin-bottom: 10px;
        position: relative;
        z-index: 1;
    }

    .register-header p {
        color: #94a3b8;
        font-size: 0.95rem;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .register-body {
        padding: 40px;
    }

    .form-label {
        font-weight: 700;
        color: var(--secondary-navy);
        font-size: 0.85rem;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .custom-input {
        background-color: #f1f5f9 !important;
        border: 2px solid transparent !important;
        border-radius: 12px !important;
        padding: 12px 18px !important;
        font-weight: 500;
        transition: var(--transition);
    }

    .custom-input:focus {
        background-color: #fff !important;
        border-color: var(--primary-turq) !important;
        box-shadow: 0 0 0 4px rgba(0, 212, 170, 0.1) !important;
    }

    .register-btn {
        background: var(--primary-turq);
        color: var(--secondary-navy) !important;
        border: none;
        padding: 15px;
        border-radius: 12px;
        font-weight: 800;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: var(--transition);
    }

    .register-btn:hover {
        background: #00bf9a;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 212, 170, 0.2);
    }

    /* Bayi Bölümü */
    .bayi-promo-box {
        background: #f0fdfa;
        border: 2px dashed rgba(0, 212, 170, 0.3);
        border-radius: 16px;
        padding: 20px;
        margin-top: 25px;
        text-align: center;
    }

    .bayi-promo-box h6 {
        color: var(--primary-dark);
        font-weight: 800;
        margin-bottom: 5px;
    }

    .btn-bayi {
        color: var(--primary-dark);
        text-decoration: none;
        font-weight: 700;
        font-size: 0.9rem;
        display: inline-block;
        margin-top: 10px;
        transition: 0.2s;
    }

    .btn-bayi:hover {
        color: var(--secondary-navy);
        transform: translateX(5px);
    }

    .register-footer {
        padding: 25px;
        background: #f8fafc;
        text-align: center;
        border-top: 1px solid #e2e8f0;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-muted);
    }

    .register-footer a {
        color: var(--primary-turq);
        text-decoration: none;
        font-weight: 800;
    }

    .fade-up {
        animation: fadeUp 0.6s ease-out;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<section class="register-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-9 fade-up">
                
                <div class="register-card shadow-lg">
                    <div class="register-header">
                        <h2>Avantaj Dünyasına Katıl</h2>
                        <p>Hemen üye ol, en yeni donanımlara özel fırsatlarla ulaş.</p>
                    </div>

                    <div class="register-body">
                        @if(session('error'))
                            <div class="alert alert-danger border-0 rounded-3 mb-4">
                                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <label for="name" class="form-label">AD SOYAD</label>
                                    <input id="name" type="text" 
                                        class="form-control custom-input @error('name') is-invalid @enderror" 
                                        name="name" value="{{ old('name') }}" 
                                        placeholder="Adınız ve Soyadınız" required autofocus>
                                    @error('name')
                                        <span class="invalid-feedback d-block mt-2" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-4">
                                    <label for="email" class="form-label">E-POSTA ADRESİ</label>
                                    <input id="email" type="email" 
                                        class="form-control custom-input @error('email') is-invalid @enderror" 
                                        name="email" value="{{ old('email') }}" 
                                        placeholder="ornek@mail.com" required>
                                    @error('email')
                                        <span class="invalid-feedback d-block mt-2" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="password" class="form-label">ŞİFRE</label>
                                    <input id="password" type="password" 
                                        class="form-control custom-input @error('password') is-invalid @enderror" 
                                        name="password" placeholder="••••••••" required>
                                    @error('password')
                                        <span class="invalid-feedback d-block mt-2" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="password-confirm" class="form-label">ŞİFRE TEKRAR</label>
                                    <input id="password-confirm" type="password" 
                                        class="form-control custom-input" 
                                        name="password_confirmation" placeholder="••••••••" required>
                                </div>
                            </div>

                            <div class="d-grid mt-2">
                                <button type="submit" class="register-btn">
                                    <i class="fas fa-user-plus me-2"></i> KAYIT OL VE BAŞLA
                                </button>
                            </div>
                        </form>

                        <div class="bayi-promo-box">
                            <h6>Kurumsal bir firmanız mı var?</h6>
                            <p class="small text-muted mb-0">Bayi kanalımıza özel iskontolu fiyatlardan yararlanmak için kurumsal üyelik talebi oluşturun.</p>
                            <a href="{{ route('bayi.basvuru.form') }}" class="btn-bayi">
                                Bayi Başvuru Formuna Git <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>

                    <div class="register-footer">
                        Zaten bir hesabınız var mı? <a href="{{ route('login') }}">Giriş Yapın</a>
                    </div>
                </div>

                <div class="text-center mt-4 mb-5">
                    <a href="{{ route('home') }}" class="text-muted text-decoration-none small fw-bold">
                        <i class="fas fa-arrow-left me-1"></i> Anasayfaya Dön
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection