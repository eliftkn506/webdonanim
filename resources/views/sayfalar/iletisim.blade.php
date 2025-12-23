@extends('layouts.app')
@section('title', 'Bize Ulaşın - Avantaj Bilişim')

@section('content')
<style>
    /* --- İLETİŞİM MODERN TEMALANDIRMA --- */
    .contact-hero-section {
        background-color: #1e293b; /* Hakkımızda ile aynı Koyu Lacivert */
        color: white;
        padding: 80px 0 120px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    /* Arkaplan Deseni */
    .contact-hero-section::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(var(--primary) 1px, transparent 1px);
        background-size: 30px 30px;
        opacity: 0.1;
    }

    .contact-title {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 20px;
        color: #ffffff !important;
        position: relative;
    }

    .contact-subtitle {
        font-size: 1.2rem;
        color: #cbd5e1 !important;
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* İletişim Kartları (Üst Bölüm) */
    .contact-cards-wrapper {
        margin-top: -60px;
        position: relative;
        z-index: 10;
    }

    .contact-info-card {
        background: white;
        border-radius: 20px;
        padding: 35px 25px;
        height: 100%;
        border: 1px solid #e2e8f0;
        box-shadow: 0 15px 35px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        text-align: center;
    }

    .contact-info-card:hover {
        transform: translateY(-10px);
        border-color: var(--primary);
        box-shadow: 0 20px 40px rgba(0,0,0,0.12);
    }

    .info-icon {
        width: 65px;
        height: 65px;
        background: #eff6ff;
        color: #3b82f6;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        margin: 0 auto 20px;
        transition: 0.3s;
    }

    .contact-info-card:hover .info-icon {
        background: var(--primary);
        color: white;
    }

    .info-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 10px;
    }

    .info-card-text {
        color: #64748b;
        font-size: 0.95rem;
        line-height: 1.5;
        margin-bottom: 15px;
    }

    .info-card-link {
        color: #3b82f6;
        font-weight: 700;
        font-size: 1.1rem;
        text-decoration: none;
    }

    /* Form ve Harita Bölümü */
    .contact-main-area {
        padding: 80px 0;
    }

    .form-card {
        background: white;
        border-radius: 25px;
        padding: 45px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }

    .form-heading {
        font-size: 2rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 30px;
    }

    .custom-input {
        background-color: #f8fafc !important;
        border: 2px solid #f1f5f9 !important;
        border-radius: 12px !important;
        padding: 14px 20px !important;
        font-weight: 500;
        transition: 0.3s;
    }

    .custom-input:focus {
        background-color: #fff !important;
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1) !important;
    }

    .submit-btn {
        background: var(--dark);
        color: white;
        border: none;
        padding: 16px 30px;
        border-radius: 12px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: 0.3s;
        width: 100%;
    }

    .submit-btn:hover {
        background: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(59, 130, 246, 0.2);
    }

    .map-container-modern {
        border-radius: 25px;
        overflow: hidden;
        height: 100%;
        min-height: 500px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }

    .map-container-modern iframe {
        width: 100%;
        height: 100%;
        min-height: 500px;
        filter: grayscale(0.2) contrast(1.1);
    }

    @media (max-width: 991px) {
        .contact-title { font-size: 2.5rem; }
        .form-card { padding: 30px; }
        .map-container-modern { min-height: 400px; margin-top: 30px; }
    }
</style>

<section class="contact-hero-section">
    <div class="container text-center">
        <h1 class="contact-title">Bize Ulaşın</h1>
        <p class="contact-subtitle">
            Teknik destekten satış öncesi sorularınıza kadar her konuda uzman ekibimiz size yardımcı olmaya hazır.
        </p>
    </div>
</section>

<section class="contact-cards-wrapper">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="contact-info-card">
                    <div class="info-icon"><i class="fas fa-phone-alt"></i></div>
                    <h5 class="info-card-title">Bizi Arayın</h5>
                    <p class="info-card-text">Hafta içi ve Cumartesi 09:00 - 19:00 saatleri arası ulaşabilirsiniz.</p>
                    <a href="tel:{{ $page->phone }}" class="info-card-link">{{ $page->phone }}</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="contact-info-card">
                    <div class="info-icon"><i class="fas fa-envelope"></i></div>
                    <h5 class="info-card-title">E-posta Gönderin</h5>
                    <p class="info-card-text">Tüm taleplerinize en geç 24 saat içerisinde yanıt veriyoruz.</p>
                    <a href="mailto:{{ $page->email }}" class="info-card-link">{{ $page->email }}</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="contact-info-card">
                    <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <h5 class="info-card-title">Ziyaret Edin</h5>
                    <p class="info-card-text">{{ $page->address }}</p>
                    <span class="text-primary fw-bold small">Yol Tarifi Al →</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="contact-main-area">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6">
                <div class="form-card">
                    <h2 class="form-heading">Mesaj Bırakın</h2>
                    
                    @if(session('success'))
                        <div class="alert alert-success border-0 shadow-sm mb-4 py-3">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('iletisim.gonder') }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">AD SOYAD</label>
                                <input type="text" name="ad" class="form-control custom-input" placeholder="Örn: Ahmet Yılmaz" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-muted">E-POSTA</label>
                                <input type="email" name="email" class="form-control custom-input" placeholder="ahmet@example.com" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted">KONU</label>
                                <input type="text" name="konu" class="form-control custom-input" placeholder="Mesajınızın konusu" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold small text-muted">MESAJINIZ</label>
                                <textarea name="mesaj" rows="6" class="form-control custom-input" placeholder="Size nasıl yardımcı olabiliriz?" required></textarea>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="submit-btn">
                                    <i class="fas fa-paper-plane"></i> Mesajı Gönder
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="map-container-modern">
                    {{-- CMS'den gelen Google Maps Iframe kodu --}}
                    {!! $page->google_maps !!}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection