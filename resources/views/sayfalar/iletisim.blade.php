@extends('layouts.app')
@section('title', 'İletişim ')

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

.contact-container {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
}

/* Hero Section */
.contact-hero {
    background: var(--primary-gradient);
    color: white;
    padding: 6rem 0 4rem;
    position: relative;
    overflow: hidden;
    text-align: center;
}

.contact-hero::before {
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

/* Contact Methods */
.contact-methods {
    padding: 5rem 0;
    background: white;
    margin-top: -3rem;
    position: relative;
    z-index: 10;
}

.methods-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.method-card {
    text-align: center;
    padding: 2.5rem 2rem;
    background: white;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    transition: var(--transition);
    border: 1px solid rgba(255, 255, 255, 0.2);
    position: relative;
    overflow: hidden;
}

.method-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--primary-gradient);
}

.method-card:hover {
    transform: translateY(-10px);
    box-shadow: var(--hover-shadow);
}

.method-icon {
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

.method-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 1rem;
}

.method-info {
    color: #718096;
    font-size: 1.1rem;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.method-action {
    background: var(--success-gradient);
    color: white;
    padding: 0.75rem 2rem;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.method-action:hover {
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(79, 172, 254, 0.4);
}

/* Form Section */
.form-section {
    padding: 5rem 0;
    background: #f8fafc;
}

.form-container {
    max-width: 1000px;
    margin: 0 auto;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: start;
}

.form-info {
    background: white;
    padding: 3rem;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
    height: fit-content;
}

.form-info h3 {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 1.5rem;
}

.form-info p {
    color: #718096;
    font-size: 1.1rem;
    line-height: 1.8;
    margin-bottom: 2rem;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 12px;
    border-left: 4px solid #667eea;
}

.info-icon {
    width: 50px;
    height: 50px;
    background: var(--primary-gradient);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.info-text {
    flex: 1;
}

.info-label {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.25rem;
}

.info-value {
    color: #718096;
}

.contact-form {
    background: white;
    padding: 3rem;
    border-radius: var(--border-radius);
    box-shadow: var(--card-shadow);
}

.form-header {
    text-align: center;
    margin-bottom: 2.5rem;
}

.form-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 1rem;
}

.form-subtitle {
    color: #718096;
    font-size: 1.1rem;
}

.form-group {
    margin-bottom: 2rem;
}

.form-label {
    display: block;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.75rem;
    font-size: 1rem;
}

.form-control {
    width: 100%;
    padding: 1rem 1.5rem;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 1rem;
    transition: var(--transition);
    background: #f8fafc;
}

.form-control:focus {
    outline: none;
    border-color: #667eea;
    background: white;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-control.error {
    border-color: #e53e3e;
    background: rgba(229, 62, 62, 0.05);
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
    padding: 1.25rem 2rem;
    background: var(--primary-gradient);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 1.1rem;
    font-weight: 700;
    cursor: pointer;
    transition: var(--transition);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
}

.submit-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Map Section */
.map-section {
    padding: 5rem 0;
    background: white;
}

.map-header {
    text-align: center;
    margin-bottom: 3rem;
}

.map-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 1rem;
}

.map-subtitle {
    font-size: 1.2rem;
    color: #718096;
    max-width: 600px;
    margin: 0 auto;
}

.map-container {
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--card-shadow);
    height: 450px;
    position: relative;
}

.map-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #e2e8f0, #cbd5e0);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    color: #4a5568;
    font-size: 1.2rem;
    font-weight: 600;
}

.map-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
    color: #667eea;
}

/* FAQ Section */
.faq-section {
    padding: 5rem 0;
    background: #f8fafc;
}

.faq-header {
    text-align: center;
    margin-bottom: 4rem;
}

.faq-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 1rem;
}

.faq-subtitle {
    font-size: 1.2rem;
    color: #718096;
}

.faq-container {
    max-width: 800px;
    margin: 0 auto;
}

.faq-item {
    background: white;
    border-radius: 15px;
    margin-bottom: 1rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: var(--transition);
}

.faq-item:hover {
    box-shadow: var(--card-shadow);
}

.faq-question {
    padding: 2rem;
    background: white;
    border: none;
    width: 100%;
    text-align: left;
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: var(--transition);
}

.faq-question:hover {
    background: #f8fafc;
}

.faq-question.active {
    background: var(--primary-gradient);
    color: white;
}

.faq-icon {
    font-size: 1.2rem;
    transition: transform 0.3s ease;
}

.faq-question.active .faq-icon {
    transform: rotate(180deg);
}

.faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
    background: #f8fafc;
}

.faq-answer.active {
    max-height: 300px;
}

.faq-answer-content {
    padding: 2rem;
    color: #718096;
    line-height: 1.8;
}

/* Social Links */
.social-section {
    padding: 4rem 0;
    background: var(--secondary-gradient);
    color: white;
    text-align: center;
}

.social-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.social-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 2rem;
}

.social-links {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.social-link {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    text-decoration: none;
    transition: var(--transition);
    backdrop-filter: blur(10px);
}

.social-link:hover {
    background: white;
    color: #667eea;
    transform: translateY(-5px) scale(1.1);
}

/* Responsive */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
    }
    
    .methods-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .form-grid-2 {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .contact-form,
    .form-info {
        padding: 2rem;
    }
    
    .social-links {
        gap: 1rem;
    }
    
    .social-link {
        width: 50px;
        height: 50px;
        font-size: 1.2rem;
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

/* Success/Error Messages */
.alert {
    padding: 1rem 1.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.alert-success {
    background: rgba(72, 187, 120, 0.1);
    border: 1px solid rgba(72, 187, 120, 0.2);
    color: #2f855a;
}

.alert-error {
    background: rgba(229, 62, 62, 0.1);
    border: 1px solid rgba(229, 62, 62, 0.2);
    color: #c53030;
}
</style>

<div class="contact-container">
    <!-- Hero Section -->
    <section class="contact-hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">İletişim</h1>
                <p class="hero-subtitle">
                    Sorularınız, önerileriniz veya yardıma ihtiyacınız mı var? 
                    Size yardımcı olmak için buradayız!
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Methods -->
    <section class="contact-methods">
        <div class="container">
            <div class="methods-grid">
                <div class="method-card fade-in-up">
                    <div class="method-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <h3 class="method-title">Telefon</h3>
                    <div class="method-info">
                        7/24 müşteri hizmetlerimiz<br>
                        <strong>tel:+90850 533 3444</strong>
                    </div>
                    <a href="tel:08501234567" class="method-action">
                        <i class="fas fa-phone"></i>Hemen Ara
                    </a>
                </div>
                
                <div class="method-card fade-in-up">
                    <div class="method-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <h3 class="method-title">E-posta</h3>
                    <div class="method-info">
                        24 saat içinde yanıt<br>
                        <strong>bilgi@avantajbilisim.com</strong>
                    </div>
                    <a href="mailto:bilgi@avantajbilisim.com" class="method-action">
                        <i class="fas fa-envelope"></i>E-posta Gönder
                    </a>
                </div>
                
                <div class="method-card fade-in-up">
                    <div class="method-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3 class="method-title">Canlı Destek</h3>
                    <div class="method-info">
                        Anlık yardım alın<br>
                        <strong>09:00 - 22:00</strong>
                    </div>
                    <a href="#" onclick="openLiveChat()" class="method-action">
                        <i class="fas fa-comments"></i>Canlı Sohbet
                    </a>
                </div>
                
                <div class="method-card fade-in-up">
                    <div class="method-icon">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <h3 class="method-title">WhatsApp</h3>
                    <div class="method-info">
                        WhatsApp üzerinden<br>
                        <strong>#</strong>
                    </div>
                    <a href="https://wa.me/#" class="method-action" target="_blank">
                        <i class="fab fa-whatsapp"></i>WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form -->
    <section class="form-section">
        <div class="container">
            <div class="form-container">
                <div class="form-grid">
                    <!-- Contact Info -->
                    <div class="form-info fade-in-up">
                        <h3>İletişim Bilgileri</h3>
                        <p>
                            Avantaj Bilişim ekibi olarak, müşteri memnuniyeti bizim önceliğimizdir. 
                            Aşağıdaki kanallardan bize ulaşabilirsiniz.
                        </p>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="info-text">
                                <div class="info-label">Adres</div>
                                <div class="info-value">
                                    #<br>
                                    #
                                </div>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="info-text">
                                <div class="info-label">Telefon</div>
                                <div class="info-value">+90850 533 3444</div>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-text">
                                <div class="info-label">E-posta</div>
                                <div class="info-value">bilgi@avantajbilisim.com</div>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="info-text">
                                <div class="info-label">Çalışma Saatleri</div>
                                <div class="info-value">
                                    Pazartesi - Pazar<br>
                                    09:00 - 22:00
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Form -->
                    <div class="contact-form fade-in-up">
                        <div class="form-header">
                            <h3 class="form-title">Mesaj Gönderin</h3>
                            <p class="form-subtitle">
                                Formu doldurarak bizimle iletişime geçebilirsiniz
                            </p>
                        </div>
                        
                        @if(session('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            {{ session('success') }}
                        </div>
                        @endif
                        
                        @if(session('error'))
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ session('error') }}
                        </div>
                        @endif
                        
                        <form id="contactForm" method="POST" action="{{ route('iletisim.gonder') }}">
                            @csrf
                            <div class="form-grid-2">
                                <div class="form-group">
                                    <label for="ad" class="form-label">Ad Soyad</label>
                                    <input type="text" id="ad" name="ad" class="form-control" 
                                           value="{{ old('ad') }}" required>
                                    @error('ad')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="email" class="form-label">E-posta</label>
                                    <input type="email" id="email" name="email" class="form-control" 
                                           value="{{ old('email') }}" required>
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-grid-2">
                                <div class="form-group">
                                    <label for="telefon" class="form-label">Telefon</label>
                                    <input type="tel" id="telefon" name="telefon" class="form-control" 
                                           value="{{ old('telefon') }}">
                                    @error('telefon')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="konu" class="form-label">Konu</label>
                                    <select id="konu" name="konu" class="form-control" required>
                                        <option value="">Konu seçiniz</option>
                                        <option value="genel" {{ old('konu') == 'genel' ? 'selected' : '' }}>Genel Bilgi</option>
                                        <option value="siparis" {{ old('konu') == 'siparis' ? 'selected' : '' }}>Sipariş Sorunu</option>
                                        <option value="teknik" {{ old('konu') == 'teknik' ? 'selected' : '' }}>Teknik Destek</option>
                                        <option value="iade" {{ old('konu') == 'iade' ? 'selected' : '' }}>İade/Değişim</option>
                                        <option value="oneri" {{ old('konu') == 'oneri' ? 'selected' : '' }}>Öneri/Şikayet</option>
                                    </select>
                                    @error('konu')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="mesaj" class="form-label">Mesajınız</label>
                                <textarea id="mesaj" name="mesaj" class="form-control" 
                                          placeholder="Mesajınızı buraya yazın..." required>{{ old('mesaj') }}</textarea>
                                @error('mesaj')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            
                            <button type="submit" class="submit-btn" id="submitBtn">
                                <i class="fas fa-paper-plane me-2"></i>
                                Mesajı Gönder
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
<section class="map-section">
    <div class="container">
        <div class="map-header">
            <h2 class="map-title">Konumumuz</h2>
            <p class="map-subtitle">
                Konya merkezi lokasyonumuzda sizi bekliyoruz
            </p>
        </div>
        
        <div class="map-container">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3143.9584465103867!2d32.505448375407404!3d38.00142969907227!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14d08dbd1b710435%3A0x637f6e450a12831b!2zRGV2YSBZYXrEsWzEsW0gw4fDtnrDvG1sZXJp!5e0!3m2!1str!2str!4v1758547620366!5m2!1str!2str" 
                width="100%" 
                height="450" 
                style="border:0; border-radius: var(--border-radius);" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</section>


    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <div class="faq-header">
                <h2 class="faq-title">Sık Sorulan Sorular</h2>
                <p class="faq-subtitle">
                    En çok merak edilen soruların yanıtlarını burada bulabilirsiniz
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
                            Stokta bulunan ürünler için siparişiniz aynı gün kargoya verilir. 
                            Stokta olmayan ürünler için 2-3 iş günü süre gerekebilir. 
                            Sipariş durumunuzu hesabınızdan takip edebilirsiniz.
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFAQ(this)">
                        <span>İade ve değişim koşulları nelerdir?</span>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            Satın aldığınız ürünleri 14 gün içerisinde iade edebilirsiniz. 
                            Ürünün orijinal ambalajında ve kullanılmamış durumda olması gerekmektedir. 
                            İade kargo ücreti firmamız tarafından karşılanır.
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFAQ(this)">
                        <span>Garanti süresi ne kadardır?</span>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            Ürünlerimiz üretici garantisi ile gelmektedir. Garanti süreleri ürüne göre değişiklik gösterir:
                            <br>• İşlemciler: 3 yıl
                            <br>• Ekran kartları: 2-3 yıl
                            <br>• RAM: Ömür boyu garanti
                            <br>• SSD/HDD: 3-5 yıl
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFAQ(this)">
                        <span>PC Toplama Sihirbazı nasıl çalışır?</span>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            PC Toplama Sihirbazımız, bütçenize ve ihtiyaçlarınıza uygun uyumlu bileşenleri 
                            otomatik olarak önerir. Adım adım rehberlik ederek mükemmel bilgisayar 
                            konfigürasyonu oluşturmanıza yardımcı olur.
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFAQ(this)">
                        <span>Ödeme seçenekleri nelerdir?</span>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            Kredi kartı, banka kartı, havale/EFT ve kapıda ödeme seçeneklerimiz bulunmaktadır. 
                            Tüm kredi kartlarına 6 aya varan taksit imkanı sağlıyoruz. 
                            Güvenli ödeme için SSL sertifikası kullanıyoruz.
                        </div>
                    </div>
                </div>
                
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFAQ(this)">
                        <span>Teknik destek nasıl alırım?</span>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            7/24 canlı destek hattımızdan, e-posta veya WhatsApp üzerinden teknik destek alabilirsiniz. 
                            Uzman ekibimiz ürün kurulumu, sorun giderme ve optimizasyon konularında 
                            size yardımcı olmaya hazır.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Social Section -->
    <section class="social-section">
        <div class="container">
            <h2 class="social-title">Sosyal Medyada Bizi Takip Edin</h2>
            <p class="social-subtitle">
                Son haberler, kampanyalar ve teknoloji güncellemeleri için bizi takip edin
            </p>
            
            <div class="social-links">
                <a href="https://facebook.com/webdonanim" class="social-link" target="_blank">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://twitter.com/webdonanim" class="social-link" target="_blank">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="https://instagram.com/webdonanim" class="social-link" target="_blank">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="https://youtube.com/webdonanim" class="social-link" target="_blank">
                    <i class="fab fa-youtube"></i>
                </a>
                <a href="https://linkedin.com/company/webdonanim" class="social-link" target="_blank">
                    <i class="fab fa-linkedin-in"></i>
                </a>
            </div>
        </div>
    </section>
</div>

<script>
// Form validation and submission
document.getElementById('contactForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Gönderiliyor...';
    submitBtn.disabled = true;
    
    // Reset after 3 seconds if form doesn't submit
    setTimeout(() => {
        if (submitBtn.disabled) {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }, 10000);
});

// FAQ Toggle Function
function toggleFAQ(button) {
    const faqItem = button.parentElement;
    const answer = faqItem.querySelector('.faq-answer');
    const icon = button.querySelector('.faq-icon');
    
    // Close all other FAQs
    document.querySelectorAll('.faq-question').forEach(otherButton => {
        if (otherButton !== button) {
            otherButton.classList.remove('active');
            otherButton.parentElement.querySelector('.faq-answer').classList.remove('active');
        }
    });
    
    // Toggle current FAQ
    button.classList.toggle('active');
    answer.classList.toggle('active');
}

// Live Chat Function
function openLiveChat() {
    // This would integrate with your live chat system
    alert('Canlı destek sistemi yakında aktif olacak!\n\nŞimdilik aşağıdaki kanallardan bize ulaşabilirsiniz:\n• WhatsApp: +90 537 487 05 48\n• E-posta: info@webdonanim.com\n• Telefon: 0850 120 00 00');
}

// Form field animations
document.querySelectorAll('.form-control').forEach(field => {
    field.addEventListener('focus', function() {
        this.parentElement.classList.add('focused');
    });
    
    field.addEventListener('blur', function() {
        if (!this.value) {
            this.parentElement.classList.remove('focused');
        }
    });
});

// Scroll animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('fade-in-up');
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

document.addEventListener('DOMContentLoaded', function() {
    // Observe elements for animation
    document.querySelectorAll('.fade-in-up').forEach(el => {
        observer.observe(el);
    });
    
    // Auto-resize textarea
    const textarea = document.getElementById('mesaj');
    if (textarea) {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 200) + 'px';
        });
    }
});

// Phone number formatting
document.getElementById('telefon').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    let formattedValue = '';
    
    if (value.length > 0) {
        if (value.length <= 3) {
            formattedValue = value;
        } else if (value.length <= 6) {
            formattedValue = value.slice(0, 3) + ' ' + value.slice(3);
        } else if (value.length <= 8) {
            formattedValue = value.slice(0, 3) + ' ' + value.slice(3, 6) + ' ' + value.slice(6);
        } else {
            formattedValue = value.slice(0, 3) + ' ' + value.slice(3, 6) + ' ' + value.slice(6, 8) + ' ' + value.slice(8, 10);
        }
    }
    
    e.target.value = formattedValue;
});

// Success message auto-hide
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });
});
</script>
@endsection