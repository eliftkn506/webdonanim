@extends('layouts.app')

@section('content')
<style>
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    --hover-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    --border-radius: 20px;
}

.about-container {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
}

/* Hero Section */
.about-hero {
    background: var(--primary-gradient);
    color: white;
    padding: 6rem 0 4rem;
    position: relative;
    overflow: hidden;
}

.about-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.hero-content {
    position: relative;
    z-index: 2;
    text-align: center;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
    text-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.hero-subtitle {
    font-size: 1.3rem;
    opacity: 0.95;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

/* Stats Section */
.stats-section {
    padding: 4rem 0;
    background: white;
    margin-top: -3rem;
    position: relative;
    z-index: 10;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.stat-card {
    text-align: center;
    padding: 2rem;
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    transition: var(--transition);
    border: 1px solid rgba(255, 255, 255, 0.2);
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--primary-gradient);
}

.stat-card:hover {
    transform: translateY(-10px);
    box-shadow: var(--hover-shadow);
}

.stat-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    background: var(--primary-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 800;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #718096;
    font-weight: 500;
}

/* Content Sections */
.content-section {
    padding: 5rem 0;
    background: white;
}

.content-section:nth-child(even) {
    background: #f8fafc;
}

.section-header {
    text-align: center;
    margin-bottom: 4rem;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 1rem;
}

.section-subtitle {
    font-size: 1.2rem;
    color: #718096;
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

.content-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 3rem;
    align-items: center;
}

.content-text {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #4a5568;
}

.content-text h3 {
    color: #2d3748;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.content-text p {
    margin-bottom: 1.5rem;
}

.content-image {
    text-align: center;
}

.content-image img {
    max-width: 100%;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    transition: var(--transition);
}

.content-image img:hover {
    transform: scale(1.05);
    box-shadow: var(--hover-shadow);
}

/* Values Section */
.values-section {
    padding: 5rem 0;
    background: var(--primary-gradient);
    color: white;
    position: relative;
    overflow: hidden;
}

.values-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.values-content {
    position: relative;
    z-index: 2;
}

.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.value-card {
    text-align: center;
    padding: 2rem;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    border-radius: var(--border-radius);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: var(--transition);
}

.value-card:hover {
    transform: translateY(-10px);
    background: rgba(255, 255, 255, 0.15);
}

.value-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
}

.value-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.value-description {
    opacity: 0.9;
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
    text-align: center;
    padding: 2rem;
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    transition: var(--transition);
    border: 1px solid rgba(255, 255, 255, 0.2);
    position: relative;
    overflow: hidden;
}

.team-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--success-gradient);
}

.team-card:hover {
    transform: translateY(-10px);
    box-shadow: var(--hover-shadow);
}

.team-avatar {
    width: 120px;
    height: 120px;
    margin: 0 auto 1.5rem;
    background: var(--primary-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: white;
    border: 4px solid #f8fafc;
}

.team-name {
    font-size: 1.3rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.team-position {
    color: #667eea;
    font-weight: 600;
    margin-bottom: 1rem;
}

.team-description {
    color: #718096;
    line-height: 1.6;
}

/* Timeline Section */
.timeline-section {
    padding: 5rem 0;
    background: #f8fafc;
}

.timeline {
    position: relative;
    max-width: 800px;
    margin: 0 auto;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 50%;
    top: 0;
    bottom: 0;
    width: 4px;
    background: var(--primary-gradient);
    transform: translateX(-50%);
}

.timeline-item {
    position: relative;
    margin-bottom: 3rem;
}

.timeline-item:nth-child(odd) .timeline-content {
    margin-left: 60%;
}

.timeline-item:nth-child(even) .timeline-content {
    margin-right: 60%;
    text-align: right;
}

.timeline-marker {
    position: absolute;
    left: 50%;
    top: 1rem;
    width: 20px;
    height: 20px;
    background: var(--primary-gradient);
    border-radius: 50%;
    transform: translateX(-50%);
    border: 4px solid white;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.timeline-content {
    background: white;
    padding: 2rem;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    transition: var(--transition);
}

.timeline-content:hover {
    transform: translateY(-5px);
    box-shadow: var(--hover-shadow);
}

.timeline-year {
    color: #667eea;
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.timeline-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 1rem;
}

.timeline-description {
    color: #718096;
    line-height: 1.6;
}

/* CTA Section */
.cta-section {
    padding: 5rem 0;
    background: var(--secondary-gradient);
    color: white;
    text-align: center;
}

.cta-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.cta-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 2rem;
}

.cta-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-cta {
    padding: 1rem 2rem;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition);
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: white;
    color: #667eea;
}

.btn-primary:hover {
    background: #f8fafc;
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(255, 255, 255, 0.3);
}

.btn-outline {
    background: transparent;
    color: white;
    border: 2px solid white;
}

.btn-outline:hover {
    background: white;
    color: #667eea;
    transform: translateY(-3px);
}

/* Responsive */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
    }
    
    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
    }
    
    .content-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .timeline::before {
        left: 2rem;
    }
    
    .timeline-marker {
        left: 2rem;
    }
    
    .timeline-item:nth-child(odd) .timeline-content,
    .timeline-item:nth-child(even) .timeline-content {
        margin-left: 4rem;
        margin-right: 0;
        text-align: left;
    }
    
    .cta-buttons {
        flex-direction: column;
        align-items: center;
    }
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}

.fade-in-up:nth-child(1) { animation-delay: 0.1s; }
.fade-in-up:nth-child(2) { animation-delay: 0.2s; }
.fade-in-up:nth-child(3) { animation-delay: 0.3s; }
.fade-in-up:nth-child(4) { animation-delay: 0.4s; }
</style>

<div class="about-container">
    <!-- Hero Section -->
    <section class="about-hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Hakkımızda</h1>
                <p class="hero-subtitle">
                    2015'ten beri bilgisayar donanımları alanında Türkiye'nin güvenilir adresi. 
                    Teknoloji tutkusu ve müşteri memnuniyetiyle büyüyen bir marka hikayesi.
                </p>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card fade-in-up">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stat-number">8+</div>
                    <div class="stat-label">Yıllık Deneyim</div>
                </div>
                <div class="stat-card fade-in-up">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number">50K+</div>
                    <div class="stat-label">Mutlu Müşteri</div>
                </div>
                <div class="stat-card fade-in-up">
                    <div class="stat-icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="stat-number">5000+</div>
                    <div class="stat-label">Ürün Çeşidi</div>
                </div>
                <div class="stat-card fade-in-up">
                    <div class="stat-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="stat-number">99%</div>
                    <div class="stat-label">Müşteri Memnuniyeti</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="content-section">
        <div class="container">
            <div class="content-grid">
                <div class="content-text">
                    <h3>Misyonumuz</h3>
                    <p>
                        WebDonanım olarak, bilgisayar donanımları alanında Türkiye'nin en güvenilir ve yenilikçi 
                        markası olmayı hedefliyoruz. Her geçen gün gelişen teknolojiye ayak uydurarak, 
                        müşterilerimize en kaliteli ürünleri en uygun fiyatlarla sunmaya devam ediyoruz.
                    </p>
                    <p>
                        Amacımız, teknoloji meraklılarından profesyonel kullanıcılara kadar geniş bir 
                        kitlenin ihtiyaçlarını karşılayarak, dijital dünyanın kapılarını aralamaktır.
                    </p>
                </div>
                <div class="content-image">
                    <img src="https://via.placeholder.com/500x400?text=Misyon+Görseli" alt="Misyonumuz">
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="values-section">
        <div class="container">
            <div class="values-content">
                <div class="section-header">
                    <h2 class="section-title">Değerlerimiz</h2>
                    <p class="section-subtitle">
                        İş hayatımızda ve müşteri ilişkilerimizde bizi yönlendiren temel prensiplerimiz
                    </p>
                </div>
                
                <div class="values-grid">
                    <div class="value-card fade-in-up">
                        <div class="value-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="value-title">Güvenilirlik</h3>
                        <p class="value-description">
                            Müşterilerimizin güvenini kazanmak ve sürdürmek için her zaman dürüst ve şeffaf davranırız.
                        </p>
                    </div>
                    
                    <div class="value-card fade-in-up">
                        <div class="value-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <h3 class="value-title">Kalite</h3>
                        <p class="value-description">
                            Sunduğumuz her üründe ve hizmette en yüksek kalite standartlarını koruyoruz.
                        </p>
                    </div>
                    
                    <div class="value-card fade-in-up">
                        <div class="value-icon">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <h3 class="value-title">İnovasyon</h3>
                        <p class="value-description">
                            Teknolojideki son gelişmeleri takip ederek sürekli yeniliğe açık kalıyoruz.
                        </p>
                    </div>
                    
                    <div class="value-card fade-in-up">
                        <div class="value-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h3 class="value-title">Müşteri Odaklılık</h3>
                        <p class="value-description">
                            Müşteri memnuniyeti bizim için her şeyden önce gelir ve bu doğrultuda çalışırız.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Takımımız</h2>
                <p class="section-subtitle">
                    WebDonanım'ın başarısının arkasındaki deneyimli ve tutkulu ekibimiz
                </p>
            </div>
            
            <div class="team-grid">
                <div class="team-card fade-in-up">
                    <div class="team-avatar">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h3 class="team-name"># #</h3>
                    <div class="team-position">Genel Müdür</div>
                    <p class="team-description">
                        15 yıllık teknoloji sektörü deneyimi ile WebDonanım'ı sektörde lider konuma taşıyan vizyoner lider.
                    </p>
                </div>
                
                <div class="team-card fade-in-up">
                    <div class="team-avatar">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <h3 class="team-name"># #</h3>
                    <div class="team-position">Teknik Müdür</div>
                    <p class="team-description">
                        Bilgisayar mühendisliği alanında uzman, en son teknolojileri takip eden teknik liderimiz.
                    </p>
                </div>
                
                <div class="team-card fade-in-up">
                    <div class="team-avatar">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 class="team-name"># #</h3>
                    <div class="team-position">Müşteri Hizmetleri Müdürü</div>
                    <p class="team-description">
                        Müşteri memnuniyetini en üst seviyede tutmak için çalışan, 24/7 destek ekibimizin lideri.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Timeline Section -->
    <section class="timeline-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Hikayemiz</h2>
                <p class="section-subtitle">
                    WebDonanım'ın kuruluşundan bugüne kadar geçirdiği önemli kilometre taşları
                </p>
            </div>
            
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <div class="timeline-year">2015</div>
                        <h3 class="timeline-title">Kuruluş</h3>
                        <p class="timeline-description">
                            WebDonanım, küçük bir ekip ve büyük hayallerle İstanbul'da kuruldu.
                        </p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <div class="timeline-year">2017</div>
                        <h3 class="timeline-title">İlk Büyük Başarı</h3>
                        <p class="timeline-description">
                            10.000'inci müşterimize ulaştık ve Türkiye genelinde kargo hizmeti başlattık.
                        </p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <div class="timeline-year">2019</div>
                        <h3 class="timeline-title">Teknolojik Dönüşüm</h3>
                        <p class="timeline-description">
                            PC Toplama Sihirbazı sistemimizi geliştirdik ve müşteri deneyimini yeniden tanımladık.
                        </p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <div class="timeline-year">2021</div>
                        <h3 class="timeline-title">Büyük Büyüme</h3>
                        <p class="timeline-description">
                            Ürün gamımızı genişlettik ve 5000+ ürün çeşidine ulaştık.
                        </p>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <div class="timeline-year">2023</div>
                        <h3 class="timeline-title">Bugün</h3>
                        <p class="timeline-description">
                            50.000+ mutlu müşteri ve Türkiye'nin en güvenilir donanım markalarından biri haline geldik.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2 class="cta-title">Bizimle İletişime Geçin</h2>
            <p class="cta-subtitle">
                Sorularınız mı var? Yardıma mı ihtiyacınız var? Ekibimiz size yardımcı olmaktan mutluluk duyar.
            </p>
            <div class="cta-buttons">
                <a href="{{ route('iletisim') }}" class="btn-cta btn-primary">
                    <i class="fas fa-envelope me-2"></i>İletişime Geç
                </a>
                <a href="{{ route('urun.index') }}" class="btn-cta btn-outline">
                    <i class="fas fa-shopping-cart me-2"></i>Ürünleri İncele
                </a>
            </div>
        </div>
    </section>
</div>

<script>
// Intersection Observer for animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.animationDelay = entry.target.dataset.delay || '0s';
            entry.target.classList.add('fade-in-up');
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

document.addEventListener('DOMContentLoaded', function() {
    // Counter animation
    const counters = document.querySelectorAll('.stat-number');
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = parseInt(counter.textContent.replace(/\D/g, ''));
                if (target > 0) {
                    animateCounter(counter, target);
                    counterObserver.unobserve(counter);
                }
            }
        });
    });

    counters.forEach(counter => {
        counterObserver.observe(counter);
    });

    // Observe other elements
    document.querySelectorAll('.fade-in-up').forEach((el, index) => {
        el.dataset.delay = `${index * 0.1}s`;
        observer.observe(el);
    });
});

function animateCounter(element, target) {
    let current = 0;
    const increment = target / 100;
    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        
        let displayValue = Math.floor(current);
        if (target >= 1000) {
            displayValue = (displayValue / 1000).toFixed(0) + 'K';
        }
        if (element.textContent.includes('%')) {
            displayValue += '%';
        } else if (element.textContent.includes('+')) {
            displayValue += '+';
        }
        
        element.textContent = displayValue;
    }, 20);
}

// Smooth scroll for internal links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>
@endsection