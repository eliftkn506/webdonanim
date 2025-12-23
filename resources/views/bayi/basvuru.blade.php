@extends('layouts.app')
@section('title', 'Bayi Başvuru Formu - Avantaj Bilişim')

@section('content')
<style>
    :root {
        --primary-turq: #00d4aa;
        --primary-dark: #00a896;
        --secondary-navy: #1e293b;
        --bg-soft: #f8fafc;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .bayi-section {
        min-height: 80vh;
        display: flex;
        align-items: center;
        padding: 60px 0;
        background-color: var(--bg-soft);
    }

    .bayi-card {
        background: white;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 20px 40px -10px rgba(30, 41, 59, 0.05);
        overflow: hidden;
    }

    .bayi-header {
        background: var(--secondary-navy);
        padding: 45px 30px;
        text-align: center;
        color: white;
        position: relative;
    }

    .bayi-header::after {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(var(--primary-turq) 1px, transparent 1px);
        background-size: 20px 20px;
        opacity: 0.1;
    }

    .bayi-header h2 {
        font-weight: 800;
        font-size: 1.85rem;
        margin-bottom: 10px;
        position: relative;
        z-index: 1;
    }

    .bayi-header p {
        color: #94a3b8;
        font-size: 0.95rem;
        margin: 0;
        position: relative;
        z-index: 1;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }

    .bayi-body {
        padding: 40px;
    }

    .form-section-title {
        font-size: 0.9rem;
        font-weight: 800;
        color: var(--primary-dark);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-section-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e2e8f0;
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

    .submit-btn {
        background: var(--primary-turq);
        color: var(--secondary-navy) !important;
        border: none;
        padding: 16px;
        border-radius: 12px;
        font-weight: 800;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
    }

    .submit-btn:hover {
        background: #00bf9a;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 212, 170, 0.2);
    }

    .info-box {
        background: #eff6ff;
        border-radius: 12px;
        padding: 15px;
        display: flex;
        gap: 12px;
        margin-bottom: 30px;
    }

    .info-box i {
        color: #3b82f6;
        font-size: 1.2rem;
        margin-top: 3px;
    }

    .info-box p {
        font-size: 0.85rem;
        color: #1e40af;
        margin: 0;
        line-height: 1.5;
    }

    .fade-up {
        animation: fadeUp 0.6s ease-out;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<section class="bayi-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 fade-up">
                
                <div class="bayi-card shadow-lg">
                    <div class="bayi-header">
                        <h2>Bayi Başvuru Formu</h2>
                        <p>Kurumsal ailemize katılarak özel iskontolardan ve avantajlı teknoloji çözümlerinden yararlanın.</p>
                    </div>

                    <div class="bayi-body">
                        @if(session('success'))
                            <div class="alert alert-success border-0 rounded-4 p-3 d-flex align-items-center mb-4" style="background: #ecfdf5; color: #065f46;">
                                <i class="fas fa-check-circle me-3 fs-4"></i>
                                <div>
                                    <h6 class="fw-bold mb-0">Başvurunuz Alındı!</h6>
                                    <small>{{ session('success') }}</small>
                                </div>
                            </div>
                        @endif

                        <div class="info-box">
                            <i class="fas fa-info-circle"></i>
                            <p>Başvurunuz ekibimiz tarafından incelendikten sonra, belirtmiş olduğunuz e-posta adresi üzerinden sizinle iletişime geçilecektir.</p>
                        </div>

                        <form action="{{ route('bayi.basvuru.submit') }}" method="POST">
                            @csrf

                            <div class="form-section-title">Firma Bilgileri</div>
                            <div class="row mb-4">
                                <div class="col-md-8 mb-3">
                                    <label class="form-label">Firma Adı</label>
                                    <input type="text" name="firma_adi" class="form-control custom-input @error('firma_adi') is-invalid @enderror" value="{{ old('firma_adi') }}" placeholder="Resmi Şirket Ünvanı" required>
                                    @error('firma_adi')<small class="text-danger fw-bold mt-1 d-block">{{ $message }}</small>@enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Vergi Numarası</label>
                                    <input type="text" name="vergi_no" class="form-control custom-input" value="{{ old('vergi_no') }}" placeholder="1234567890" required>
                                </div>
                            </div>

                            <div class="form-section-title">Yetkili Bilgileri</div>
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Yetkili Ad</label>
                                    <input type="text" name="yetkili_ad" class="form-control custom-input @error('yetkili_ad') is-invalid @enderror" value="{{ old('yetkili_ad') }}" placeholder="Adınız" required>
                                    @error('yetkili_ad')<small class="text-danger fw-bold mt-1 d-block">{{ $message }}</small>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Yetkili Soyad</label>
                                    <input type="text" name="yetkili_soyad" class="form-control custom-input @error('yetkili_soyad') is-invalid @enderror" value="{{ old('yetkili_soyad') }}" placeholder="Soyadınız" required>
                                    @error('yetkili_soyad')<small class="text-danger fw-bold mt-1 d-block">{{ $message }}</small>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">E-posta Adresi</label>
                                    <input type="email" name="email" class="form-control custom-input @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="kurumsal@firma.com" required>
                                    @error('email')<small class="text-danger fw-bold mt-1 d-block">{{ $message }}</small>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Telefon</label>
                                    <input type="text" name="telefon" class="form-control custom-input @error('telefon') is-invalid @enderror" value="{{ old('telefon') }}" placeholder="0(5xx) xxx xx xx" required>
                                    @error('telefon')<small class="text-danger fw-bold mt-1 d-block">{{ $message }}</small>@enderror
                                </div>
                            </div>

                            <div class="form-section-title">İletişim Bilgileri</div>
                            <div class="mb-4">
                                <label class="form-label">Firma Adresi</label>
                                <textarea name="adres" class="form-control custom-input" rows="3" placeholder="Sokak, Mahalle, İlçe/İl">{{ old('adres') }}</textarea>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="submit-btn">
                                    <i class="fas fa-paper-plane"></i> BAŞVURUYU TAMAMLA
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('home') }}" class="text-muted text-decoration-none small fw-bold">
                        <i class="fas fa-arrow-left me-1"></i> Anasayfaya Dön
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection