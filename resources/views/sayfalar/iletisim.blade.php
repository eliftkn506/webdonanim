@extends('layouts.app')
@section('title', 'İletişim - Avantaj Bilişim')

@section('content')
<style>
:root {
    /* Ana Tema Renkleri */
    --primary-color: #1a365d;       /* Lacivert */
    --secondary-color: #22987e;     /* Yeşil */
    --accent-color: #3182ce;        /* Mavi */
    --bg-light: #f8fafc;
    
    /* Kurumsal Gradyanlar */
    --hero-gradient: linear-gradient(135deg, var(--primary-color) 0%, #2c5282 100%);
    --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --hover-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --border-radius: 1rem;
}

.contact-container {
    background-color: var(--bg-light);
    min-height: 100vh;
}

/* Hero Section */
.contact-hero {
    background: var(--hero-gradient);
    color: white;
    padding: 5rem 0 8rem; /* Kartlar içine girsin diye alt boşluk fazla */
    position: relative;
    overflow: hidden;
    text-align: center;
}

/* Arkaplan Deseni */
.contact-hero::before {
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
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
    font-weight: 300;
}

/* Contact Methods (Kartlar Yukarı Kaydırıldı) */
.contact-methods {
    position: relative;
    z-index: 10;
    margin-top: -4rem;
    padding-bottom: 4rem;
}

.methods-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.method-card {
    text-align: center;
    padding: 2.5rem 2rem;
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    transition: var(--transition);
    border: 1px solid #e2e8f0;
    position: relative;
    overflow: hidden;
    /* Kart içeriğini dikey ortalamak için */
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
}

.method-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    background: var(--secondary-color); /* Yeşil şerit */
}

.method-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--hover-shadow);
    border-color: var(--accent-color);
}

.method-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto 1.5rem;
    background: rgba(34, 152, 126, 0.1);
    color: var(--secondary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
}

.method-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.method-info {
    color: #64748b;
    font-size: 1.05rem;
    line-height: 1.6;
    margin-bottom: 0; /* Alt boşluk kaldırıldı çünkü buton yok */
}

/* Form Section */
.form-section {
    padding: 5rem 0;
    background: white;
}

.form-container {
    max-width: 1100px;
    margin: 0 auto;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1.2fr;
    gap: 4rem;
    align-items: start;
}

.form-info {
    background: #f8fafc;
    padding: 3rem;
    border-radius: var(--border-radius);
    border: 1px solid #e2e8f0;
}

.form-info h3 {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 1.5rem;
}

.form-info p {
    color: #64748b;
    font-size: 1.05rem;
    line-height: 1.7;
    margin-bottom: 2rem;
}

.info-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: white;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    transition: var(--transition);
}

.info-item:hover {
    border-color: var(--secondary-color);
    transform: translateX(5px);
}

.info-icon {
    width: 45px;
    height: 45px;
    background: rgba(26, 54, 93, 0.1);
    color: var(--primary-color);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.info-text {
    flex: 1;
}

.info-label {
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
    text-transform: uppercase;
}

.info-value {
    color: #475569;
    font-weight: 500;
}

.contact-form {
    background: white;
    padding: 0; /* Container padding'i yeterli */
}

.form-header {
    margin-bottom: 2rem;
}

.form-title {
    font-size: 2rem;
    font-weight: 800;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.form-subtitle {
    color: #64748b;
    font-size: 1.1rem;
}

.form-label {
    display: block;
    font-weight: 600;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.form-control {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 1rem;
    transition: var(--transition);
    background: white;
    color: var(--primary-color);
}

.form-control:focus {
    outline: none;
    border-color: var(--accent-color);
    box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.1);
}

textarea.form-control {
    resize: vertical;
    min-height: 150px;
}

.form-grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.submit-btn {
    width: 100%;
    padding: 1rem 2rem;
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 1.1rem;
    font-weight: 700;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
}

.submit-btn:hover {
    background: var(--secondary-color);
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(34, 152, 126, 0.2);
}

/* Map Section */
.map-section {
    padding: 0 0 5rem;
    background: white;
}

.map-container {
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--card-shadow);
    height: 450px;
    border: 1px solid #e2e8f0;
}

/* FAQ Section */
.faq-section {
    padding: 5rem 0;
    background: var(--bg-light);
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

.faq-container {
    max-width: 800px;
    margin: 0 auto;
}

.faq-item {
    background: white;
    border-radius: 12px;
    margin-bottom: 1rem;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    transition: var(--transition);
}

.faq-item:hover {
    border-color: var(--accent-color);
}

.faq-question {
    padding: 1.5rem;
    background: white;
    border: none;
    width: 100%;
    text-align: left;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--primary-color);
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: var(--transition);
}

.faq-question:hover {
    color: var(--accent-color);
}

.faq-question.active {
    background: #f1f5f9;
    color: var(--primary-color);
}

.faq-icon {
    color: #cbd5e1;
    transition: transform 0.3s ease;
}

.faq-question.active .faq-icon {
    transform: rotate(180deg);
    color: var(--accent-color);
}

.faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
    background: white;
}

.faq-answer.active {
    max-height: 300px;
    border-top: 1px solid #f1f5f9;
}

.faq-answer-content {
    padding: 1.5rem;
    color: #64748b;
    line-height: 1.7;
}

/* Responsive */
@media (max-width: 991px) {
    .form-grid {
        grid-template-columns: 1fr;
        gap: 3rem;
    }
    .form-info {
        order: 2;
    }
    .contact-form {
        order: 1;
    }
}

@media (max-width: 768px) {
    .hero-title { font-size: 2.25rem; }
    .methods-grid { grid-template-columns: 1fr; }
    .form-grid-2 { grid-template-columns: 1fr; gap: 1rem; }
    .stats-section { margin-top: -2rem; }
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

/* Alerts */
.alert {
    padding: 1rem 1.5rem;
    border-radius: 10px;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 500;
}

.alert-success {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #166534;
}

.alert-error {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #991b1b;
}
</style>

<div class="contact-container">
    <section class="contact-hero">
        <div class="container">
            <div class="hero-content fade-in-up">
                <h1 class="hero-title">Bize Ulaşın</h1>
                <p class="hero-subtitle">
                    Teknoloji çözümlerimiz hakkında bilgi almak veya destek talepleriniz için uzman ekibimiz bir tık uzağınızda.
                </p>
            </div>
        </div>
    </section>

    <section class="contact-methods">
        <div class="container">
            <div class="methods-grid">
                <div class="method-card fade-in-up" style="animation-delay: 0.1s">
                    <div class="method-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <h3 class="method-title">Telefon</h3>
                    <div class="method-info">
                        7/24 Müşteri Hizmetleri<br>
                        <strong>+90 850 533 3444</strong>
                    </div>
                </div>
                
                <div class="method-card fade-in-up" style="animation-delay: 0.2s">
                    <div class="method-icon">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                    <h3 class="method-title">E-posta</h3>
                    <div class="method-info">
                        Satış ve Destek Talepleri<br>
                        <strong>bilgi@avantajbilisim.com</strong>
                    </div>
                </div>
                
                <div class="method-card fade-in-up" style="animation-delay: 0.3s">
                    <div class="method-icon">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <h3 class="method-title">WhatsApp</h3>
                    <div class="method-info">
                        Hızlı Mesaj Hattı<br>
                        <strong>05XX XXX XX XX</strong>
                    </div>
                </div>
                
                <div class="method-card fade-in-up" style="animation-delay: 0.4s">
                    <div class="method-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3 class="method-title">Canlı Destek</h3>
                    <div class="method-info">
                        Anlık Müşteri Temsilcisi<br>
                        <strong>09:00 - 22:00</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="form-section">
        <div class="container">
            <div class="form-container">
                <div class="form-grid">
                    
                    <div class="contact-form fade-in-up">
                        <div class="form-header">
                            <h3 class="form-title">İletişim Formu</h3>
                            <p class="form-subtitle">
                                Aşağıdaki formu doldurarak bize mesajınızı iletebilirsiniz. En kısa sürede dönüş yapacağız.
                            </p>
                        </div>
                        
                        @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                        </div>
                        @endif
                        
                        @if(session('error'))
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                        </div>
                        @endif
                        
                        <form id="contactForm" method="POST" action="{{ route('iletisim.gonder') }}">
                            @csrf
                            <div class="form-grid-2">
                                <div class="form-group">
                                    <label for="ad" class="form-label">Ad Soyad</label>
                                    <input type="text" id="ad" name="ad" class="form-control" value="{{ old('ad') }}" placeholder="Adınız Soyadınız" required>
                                    @error('ad')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="email" class="form-label">E-posta Adresi</label>
                                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="ornek@email.com" required>
                                    @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                            </div>
                            
                            <div class="form-grid-2">
                                <div class="form-group">
                                    <label for="telefon" class="form-label">Telefon (İsteğe Bağlı)</label>
                                    <input type="tel" id="telefon" name="telefon" class="form-control" value="{{ old('telefon') }}" placeholder="05XX XXX XX XX">
                                </div>
                                
                                <div class="form-group">
                                    <label for="konu" class="form-label">Konu</label>
                                    <select id="konu" name="konu" class="form-control" required>
                                        <option value="">Seçiniz...</option>
                                        <option value="genel">Genel Bilgi</option>
                                        <option value="siparis">Sipariş Durumu</option>
                                        <option value="teknik">Teknik Destek</option>
                                        <option value="iade">İade ve Değişim</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group mb-4">
                                <label for="mesaj" class="form-label">Mesajınız</label>
                                <textarea id="mesaj" name="mesaj" class="form-control" placeholder="Sorunuzu detaylı bir şekilde yazınız..." required>{{ old('mesaj') }}</textarea>
                                @error('mesaj')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            
                            <button type="submit" class="submit-btn" id="submitBtn">
                                <i class="fas fa-paper-plane"></i> Mesajı Gönder
                            </button>
                        </form>
                    </div>

                    <div class="form-info fade-in-up" style="animation-delay: 0.2s">
                        <h3>Merkez Ofisimiz</h3>
                        <p>
                            Mağazamızı ziyaret ederek ürünleri yerinde inceleyebilir, teknik ekibimizden yüz yüze destek alabilirsiniz.
                        </p>
                        
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="info-text">
                                <div class="info-label">Adres</div>
                                <div class="info-value">
                                    Teknoloji Mah. Bilişim Cad.<br>
                                    No: 123, Selçuklu / KONYA
                                </div>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-phone-volume"></i></div>
                            <div class="info-text">
                                <div class="info-label">Telefon</div>
                                <div class="info-value">+90 850 533 3444</div>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon"><i class="fas fa-at"></i></div>
                            <div class="info-text">
                                <div class="info-label">E-posta</div>
                                <div class="info-value">bilgi@avantajbilisim.com</div>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon"><i class="far fa-clock"></i></div>
                            <div class="info-text">
                                <div class="info-label">Çalışma Saatleri</div>
                                <div class="info-value">Pzt - Cmt: 09:00 - 19:00</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="map-section">
        <div class="container">
            <div class="map-container">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d201880.8360662237!2d32.493155!3d37.871553!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14d08568d615f745%3A0x240dd0fc08060967!2sKonya!5e0!3m2!1str!2str!4v1700000000000!5m2!1str!2str" 
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>

    <section class="faq-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Sıkça Sorulan Sorular</h2>
                <p class="section-subtitle">
                    Müşterilerimizin en çok merak ettiği soruları sizin için derledik.
                </p>
            </div>
            
            <div class="faq-container">
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFAQ(this)">
                        <span>Siparişim ne zaman kargoya verilir?</span>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            Stokta bulunan ürünler hafta içi saat 16:00'a kadar verilen siparişlerde aynı gün kargoya teslim edilmektedir.
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFAQ(this)">
                        <span>İade koşulları nelerdir?</span>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            Ürünü teslim aldığınız tarihten itibaren 14 gün içinde, ambalajı açılmamış ve kullanılmamış olmak şartıyla iade edebilirsiniz.
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFAQ(this)">
                        <span>Kapıda ödeme var mı?</span>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            Evet, belirli tutarın altındaki siparişlerinizde kapıda nakit veya kredi kartı ile ödeme seçeneğini kullanabilirsiniz.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
// Form Loading State
document.getElementById('contactForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Gönderiliyor...';
    submitBtn.disabled = true;
    
    // 10 sn sonra butonu resetle (güvenlik için)
    setTimeout(() => {
        if (submitBtn.disabled) {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }, 10000);
});

// FAQ Toggle Logic
function toggleFAQ(button) {
    const faqItem = button.parentElement;
    const answer = faqItem.querySelector('.faq-answer');
    
    // Diğer açık olanları kapat (Opsiyonel - Akordeon etkisi için)
    document.querySelectorAll('.faq-question').forEach(otherButton => {
        if (otherButton !== button) {
            otherButton.classList.remove('active');
            otherButton.parentElement.querySelector('.faq-answer').classList.remove('active');
        }
    });
    
    // Tıklananı aç/kapa
    button.classList.toggle('active');
    answer.classList.toggle('active');
}

// Scroll Animasyonları
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('fade-in-up');
            observer.unobserve(entry.target);
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.fade-in-up').forEach(el => observer.observe(el));

// Telefon Formatlama
document.getElementById('telefon')?.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 10) value = value.slice(0, 10);
    e.target.value = value;
});
</script>
@endsection