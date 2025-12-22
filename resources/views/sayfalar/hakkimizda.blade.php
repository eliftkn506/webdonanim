@extends('layouts.app')
@section('title', 'Hakkımızda - Avantaj Bilişim')

@section('content')
<style>
:root {
    /* Ana Tema Renkleri */
    --primary-color: #1a365d;       /* Lacivert */
    --secondary-color: #22987e;     /* Yeşil */
    --accent-color: #3182ce;        /* Mavi */
    --bg-light: #f8fafc;
    
    /* Gradyanlar (Kurumsal) */
    --hero-gradient: linear-gradient(135deg, var(--primary-color) 0%, #2c5282 100%);
    --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --hover-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --border-radius: 1rem;
}

.about-container {
    background-color: var(--bg-light);
    min-height: 100vh;
}

/* Hero Section */
.about-hero {
    background: var(--hero-gradient);
    color: white;
    padding: 5rem 0 8rem; /* Alt boşluk artırıldı */
    position: relative;
    overflow: hidden;
}

/* Arkaplan Deseni */
.about-hero::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background-image: radial-gradient(rgba(255,255,255,0.1) 1px, transparent 1px);
    background-size: 30px 30px;
    opacity: 0.5;
}

.hero-content {
    position: relative;
    z-index: 2;
    text-align: center;
    max-width: 800px;
    margin: 0 auto;
}

.hero-title {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
    letter-spacing: -1px;
}

.hero-subtitle {
    font-size: 1.25rem;
    opacity: 0.9;
    line-height: 1.6;
    font-weight: 300;
}

/* Stats Section (Kartlar Yukarı Kaydırıldı) */
.stats-section {
    position: relative;
    z-index: 10;
    margin-top: -4rem; /* Hero içine girinti */
    padding-bottom: 4rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1.5rem;
}

.stat-card {
    text-align: center;
    padding: 2.5rem 1.5rem;
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    transition: var(--transition);
    border: 1px solid #e2e8f0;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--hover-shadow);
    border-color: var(--accent-color);
}

.stat-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto 1rem;
    background: rgba(49, 130, 206, 0.1);
    color: var(--accent-color);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--primary-color);
    margin-bottom: 0.25rem;
    line-height: 1;
}

.stat-label {
    color: #64748b;
    font-weight: 600;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Content Sections */
.content-section {
    padding: 5rem 0;
    background: white;
}

.section-header {
    text-align: center;
    margin-bottom: 3rem;
}

.section-title {
    font-size: 2.25rem;
    font-weight: 800;
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.section-subtitle {
    font-size: 1.1rem;
    color: #64748b;
    max-width: 600px;
    margin: 0 auto;
}

.content-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
}

.content-text h3 {
    color: var(--primary-color);
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.content-text p {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #475569;
    margin-bottom: 1.5rem;
}

.content-image img {
    width: 100%;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    transition: var(--transition);
}

.content-image img:hover {
    transform: scale(1.02);
    box-shadow: var(--hover-shadow);
}

/* Values Section */
.values-section {
    padding: 5rem 0;
    background-color: #f1f5f9;
}

.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.value-card {
    background: white;
    padding: 2rem;
    border-radius: var(--border-radius);
    border: 1px solid #e2e8f0;
    transition: var(--transition);
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.value-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--hover-shadow);
    border-color: var(--secondary-color);
}

.value-icon {
    width: 70px;
    height: 70px;
    margin-bottom: 1.5rem;
    background: rgba(34, 152, 126, 0.1);
    color: var(--secondary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
}

.value-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.75rem;
}

.value-description {
    color: #64748b;
    line-height: 1.6;
}

/* Team Section */
.team-section {
    padding: 5rem 0;
    background: white;
}

.team-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
}

.team-card {
    background: white;
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--card-shadow);
    border: 1px solid #e2e8f0;
    transition: var(--transition);
    text-align: center;
    padding: 2rem;
}

.team-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--hover-shadow);
}

.team-avatar {
    width: 120px;
    height: 120px;
    margin: 0 auto 1.5rem;
    background: #f1f5f9;
    color: var(--primary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    border: 3px solid white;
    box-shadow: 0 0 0 3px #e2e8f0;
}

.team-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.25rem;
    min-height: 1.5em; /* İsim alanı boş kalsa da yer kaplasın */
}

.team-position {
    color: var(--accent-color);
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 1rem;
    text-transform: uppercase;
}

.team-description {
    color: #64748b;
    font-size: 0.95rem;
    line-height: 1.6;
}

/* CTA Section */
.cta-section {
    padding: 5rem 0;
    background: linear-gradient(135deg, var(--secondary-color), #1a7f6c);
    color: white;
    text-align: center;
}

.cta-title {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 1rem;
}

.cta-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 2.5rem;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.btn-cta {
    padding: 1rem 2.5rem;
    border-radius: 50px;
    font-weight: 700;
    text-decoration: none;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-white {
    background: white;
    color: var(--secondary-color);
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.btn-white:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 15px rgba(0,0,0,0.2);
    background-color: #f8fafc;
    color: var(--secondary-color);
}

.btn-outline-white {
    background: transparent;
    color: white;
    border: 2px solid white;
    margin-left: 1rem;
}

.btn-outline-white:hover {
    background: white;
    color: var(--secondary-color);
    transform: translateY(-3px);
}

/* Responsive */
@media (max-width: 768px) {
    .hero-title { font-size: 2.25rem; }
    .content-grid { grid-template-columns: 1fr; gap: 2rem; }
    .stats-section { margin-top: -2rem; }
    .cta-buttons { display: flex; flex-direction: column; gap: 1rem; }
    .btn-outline-white { margin-left: 0; }
}

/* Animations */
.fade-in-up {
    animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
    opacity: 0;
    transform: translateY(20px);
}

@keyframes fadeInUp {
    to { opacity: 1; transform: translateY(0); }
}
</style>

<div class="about-container">
    
    <section class="about-hero">
        <div class="container">
            <div class="hero-content fade-in-up">
                <h1 class="hero-title">Teknolojinin Güvenilir Adresi</h1>
                <p class="hero-subtitle">
                    2015'ten beri Türkiye'nin en yeni teknolojilerini, en uygun fiyatlarla ve güvenilir hizmet anlayışıyla sizlerle buluşturuyoruz.
                </p>
            </div>
        </div>
    </section>

    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card fade-in-up" style="animation-delay: 0.1s">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-number">10+</div>
                    <div class="stat-label">Yıllık Tecrübe</div>
                </div>
                <div class="stat-card fade-in-up" style="animation-delay: 0.2s">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number">50K+</div>
                    <div class="stat-label">Mutlu Müşteri</div>
                </div>
                <div class="stat-card fade-in-up" style="animation-delay: 0.3s">
                    <div class="stat-icon">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <div class="stat-number">5000+</div>
                    <div class="stat-label">Ürün Çeşidi</div>
                </div>
                <div class="stat-card fade-in-up" style="animation-delay: 0.4s">
                    <div class="stat-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <div class="stat-number">%99</div>
                    <div class="stat-label">Memnuniyet</div>
                </div>
            </div>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <div class="content-grid">
                <div class="content-text fade-in-up">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3">HİKAYEMİZ</span>
                    <h3>Misyonumuz</h3>
                    <p>
                        Avantaj Bilişim olarak, teknolojiye ulaşımı herkes için kolay, güvenli ve ekonomik hale getirmeyi amaçlıyoruz. Sadece ürün satmıyor, ihtiyacınıza en uygun çözümleri üretiyoruz.
                    </p>
                    <p>
                        Bilgisayar donanımları, gaming ekipmanları ve kurumsal çözümler alanında sektörün öncüsü olarak, satış öncesi ve sonrası desteğimizle her zaman yanınızdayız.
                    </p>
                </div>
                <div class="content-image fade-in-up" style="animation-delay: 0.2s">
                    <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=800&auto=format&fit=crop" alt="Ofis Ortamı">
                </div>
            </div>
        </div>
    </section>

    <section class="values-section">
        <div class="container">
            <div class="section-header fade-in-up">
                <h2 class="section-title">Bizi Biz Yapan Değerler</h2>
                <p class="section-subtitle">
                    İş yapış şeklimize yön veren ve bizi rakiplerimizden ayıran temel prensiplerimiz.
                </p>
            </div>
            
            <div class="values-grid">
                <div class="value-card fade-in-up" style="animation-delay: 0.1s">
                    <div class="value-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="value-title">Güvenilirlik</h3>
                    <p class="value-description">
                        Şeffaf fiyat politikamız ve %100 orijinal ürün garantimizle müşterilerimizin güvenini her şeyin üstünde tutarız.
                    </p>
                </div>
                
                <div class="value-card fade-in-up" style="animation-delay: 0.2s">
                    <div class="value-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 class="value-title">Müşteri Odaklılık</h3>
                    <p class="value-description">
                        Satışla bitmeyen dostluk... Teknik destek ekibimizle satış sonrasında da her türlü sorununuzda yanınızdayız.
                    </p>
                </div>
                
                <div class="value-card fade-in-up" style="animation-delay: 0.3s">
                    <div class="value-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h3 class="value-title">Yenilikçilik</h3>
                    <p class="value-description">
                        Teknoloji dünyasındaki son trendleri yakından takip eder, en yeni donanımları ilk biz sunarız.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="team-section">
        <div class="container">
            <div class="section-header fade-in-up">
                <h2 class="section-title">Yönetim Kadromuz</h2>
                <p class="section-subtitle">
                    Başarımızın arkasındaki deneyimli ve tutkulu liderlerimiz.
                </p>
            </div>
            
            <div class="team-grid">
                <div class="team-card fade-in-up" style="animation-delay: 0.1s">
                    <div class="team-avatar">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h3 class="team-name"></h3> <div class="team-position">Genel Müdür</div>
                    <p class="team-description">
                        15 yıllık sektör tecrübesiyle şirketin vizyonunu belirleyen liderimiz.
                    </p>
                </div>
                
                <div class="team-card fade-in-up" style="animation-delay: 0.2s">
                    <div class="team-avatar">
                        <i class="fas fa-user-gear"></i>
                    </div>
                    <h3 class="team-name"></h3> <div class="team-position">Teknik Müdür</div>
                    <p class="team-description">
                        Donanım dünyasının uzmanı, teknik operasyonların başındaki isim.
                    </p>
                </div>
                
                <div class="team-card fade-in-up" style="animation-delay: 0.3s">
                    <div class="team-avatar">
                        <i class="fas fa-user-headset"></i>
                    </div>
                    <h3 class="team-name"></h3> <div class="team-position">Müşteri İlişkileri</div>
                    <p class="team-description">
                        Müşteri memnuniyetini en üst seviyede tutmak için çalışan ekibin lideri.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container">
            <h2 class="cta-title">Sorularınız mı Var?</h2>
            <p class="cta-subtitle">
                Uzman ekibimiz size yardımcı olmak için hazır. Bize ulaşın, teknolojiyi birlikte keşfedelim.
            </p>
            <div class="cta-buttons">
                <a href="{{ route('iletisim') }}" class="btn-cta btn-white">
                    <i class="fas fa-envelope me-2"></i>İletişime Geç
                </a>
                <a href="{{ route('urun.index') }}" class="btn-cta btn-outline-white">
                    <i class="fas fa-shopping-bag me-2"></i>Ürünleri İncele
                </a>
            </div>
        </div>
    </section>
</div>

<script>
// Sayaç Animasyonu
document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.stat-number');
    const options = { threshold: 0.5 };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = parseInt(counter.innerText.replace(/\D/g, ''));
                let count = 0;
                const speed = 2000 / target;
                
                const updateCount = () => {
                    const inc = target / 100;
                    if(count < target) {
                        count += inc;
                        // Formatlama (10+, 50K+, %99 vb.)
                        let text = Math.ceil(count);
                        if(counter.innerText.includes('K')) text = (text/1000).toFixed(0) + 'K+';
                        else if(counter.innerText.includes('%')) text = '%' + text;
                        else text += '+';
                        
                        counter.innerText = text;
                        setTimeout(updateCount, 20);
                    } else {
                        counter.innerText = entry.target.getAttribute('data-target') || entry.target.innerText;
                    }
                };
                updateCount();
                observer.unobserve(counter);
            }
        });
    }, options);
    
    counters.forEach(counter => {
        counter.setAttribute('data-target', counter.innerText); // Orijinal metni sakla
        counter.innerText = '0'; // Sıfırla
        observer.observe(counter);
    });
});
</script>
@endsection