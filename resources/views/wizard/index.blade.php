@extends('layouts.app')
@section('title', 'PC Toplama Sihirbazı')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* === ULTRA MODERN CSS (Değiştirilmedi) === */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
    
    :root {
        --primary: #00897B;
        --primary-hover: #00695C;
        --primary-light: #E0F2F1;
        --primary-glow: rgba(0, 137, 123, 0.3);
        
        --dark: #0F172A;
        --dark-lighter: #1E293B;
        --dark-card: #1a2332;
        
        --text-main: #F1F5F9;
        --text-muted: #94A3B8;
        --text-dark: #334155;
        
        --bg-body: #0A0E1A;
        --bg-card: #151b2b;
        --bg-card-hover: #1a2332;
        
        --border: rgba(148, 163, 184, 0.1);
        --border-glow: rgba(0, 137, 123, 0.3);
        
        --danger: #EF4444;
        --success: #10B981;
        --warning: #F59E0B;
        
        --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.3);
        --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.4);
        --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.5);
        --shadow-glow: 0 0 40px var(--primary-glow);
        
        --radius: 16px;
        --radius-sm: 8px;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
        background: linear-gradient(135deg, #0A0E1A 0%, #1a1f35 50%, #0A0E1A 100%);
        font-family: 'Inter', sans-serif;
        color: var(--text-main);
        min-height: 100vh;
        overflow-x: hidden;
        position: relative;
    }

    /* Animated Background */
    body::before {
        content: ''; position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: radial-gradient(circle at 20% 50%, rgba(0, 137, 123, 0.08) 0%, transparent 50%),
                    radial-gradient(circle at 80% 80%, rgba(0, 137, 123, 0.06) 0%, transparent 50%);
        z-index: -1; animation: bgPulse 10s ease-in-out infinite;
    }

    @keyframes bgPulse { 0%, 100% { opacity: 0.5; } 50% { opacity: 1; } }

    /* Floating Particles */
    .particles { position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; z-index: -1; pointer-events: none; }
    .particle { position: absolute; width: 4px; height: 4px; background: var(--primary); border-radius: 50%; opacity: 0.3; animation: float 20s infinite; }
    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        50% { transform: translate(100px, -50px) rotate(180deg); }
        75% { transform: translate(100px, -150px) rotate(270deg); }
    }

    .wizard-container { max-width: 1800px; width: 96%; margin: 2rem auto 4rem; position: relative; z-index: 1; }

    /* Header */
    .wizard-header { text-align: center; margin-bottom: 3rem; animation: fadeInDown 0.8s ease; }
    @keyframes fadeInDown { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); } }
    
    .wizard-header h1 {
        font-size: 3.5rem; font-weight: 900;
        background: linear-gradient(135deg, #fff 0%, var(--primary) 100%);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        background-clip: text; margin-bottom: 1rem; letter-spacing: -0.03em;
        text-shadow: 0 0 60px var(--primary-glow); position: relative; display: inline-block;
    }
    .wizard-header h1::after {
        content: ''; position: absolute; bottom: -10px; left: 50%; transform: translateX(-50%);
        width: 100px; height: 4px; background: linear-gradient(90deg, transparent, var(--primary), transparent); border-radius: 2px;
    }
    .wizard-header p { font-size: 1.2rem; color: var(--text-muted); font-weight: 500; }

    /* Modern Stepper */
    .stepper-wrapper {
        background: var(--bg-card); border-radius: var(--radius); padding: 2rem;
        margin-bottom: 2.5rem; box-shadow: var(--shadow-md); border: 1px solid var(--border);
        backdrop-filter: blur(10px); position: relative; overflow: hidden;
    }
    .stepper-wrapper::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
        background: linear-gradient(90deg, transparent, var(--primary), transparent);
    }
    .stepper { display: flex; justify-content: space-between; position: relative; gap: 1rem; overflow-x: auto; }
    .stepper::before {
        content: ''; position: absolute; top: 24px; left: 60px; right: 60px; height: 3px;
        background: var(--border); z-index: 0; border-radius: 2px;
    }
    .stepper-progress {
        position: absolute; top: 24px; left: 60px; height: 3px;
        background: linear-gradient(90deg, var(--primary), var(--primary-hover));
        z-index: 1; transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 2px; box-shadow: 0 0 20px var(--primary-glow);
    }
    .stepper-item {
        position: relative; z-index: 2; display: flex; flex-direction: column;
        align-items: center; cursor: pointer; flex: 1; transition: transform 0.3s ease; min-width: 80px;
    }
    .stepper-item:hover { transform: translateY(-3px); }
    
    .step-circle {
        width: 50px; height: 50px; border-radius: 50%; background: var(--bg-card-hover);
        border: 3px solid var(--border); color: var(--text-muted); font-weight: 800;
        font-size: 1.1rem; display: flex; align-items: center; justify-content: center;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); position: relative;
    }
    .step-circle::before {
        content: ''; position: absolute; inset: -5px; border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--primary-hover));
        opacity: 0; transition: opacity 0.4s ease; z-index: -1;
    }
    .step-label { margin-top: 0.75rem; font-size: 0.85rem; font-weight: 600; color: var(--text-muted); transition: all 0.3s ease; text-align: center; }

    .stepper-item.active .step-circle {
        border-color: var(--primary); background: var(--primary); color: white;
        box-shadow: 0 0 30px var(--primary-glow), var(--shadow-md); transform: scale(1.15);
    }
    .stepper-item.active .step-circle::before { opacity: 0.2; animation: pulse 2s infinite; }
    @keyframes pulse { 0%, 100% { transform: scale(1); opacity: 0.2; } 50% { transform: scale(1.2); opacity: 0; } }
    .stepper-item.active .step-label { color: var(--primary); font-weight: 700; }
    
    .stepper-item.completed .step-circle { background: var(--primary); border-color: var(--primary); color: white; }
    .stepper-item.completed .step-circle::after { content: '✓'; font-size: 1.3rem; }
    .stepper-item.completed .step-circle span { display: none; }

    /* Main Content Grid */
    .wizard-content { display: grid; grid-template-columns: 1fr 420px; gap: 2rem; align-items: start; }

    /* Products Section */
    .products-section {
        background: rgba(21, 27, 43, 0.6); backdrop-filter: blur(20px); border-radius: var(--radius);
        padding: 2rem; box-shadow: var(--shadow-lg); border: 1px solid var(--border);
        min-height: 700px; position: relative; overflow: hidden;
    }
    .products-section::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
        background: linear-gradient(90deg, transparent, var(--primary), transparent);
    }

    .step-title {
        font-size: 1.8rem; font-weight: 800; color: var(--text-main); padding-bottom: 1.5rem;
        margin-bottom: 2rem; border-bottom: 2px solid var(--border); display: flex; align-items: center; gap: 1rem;
    }
    .step-title i { color: var(--primary); font-size: 1.5rem; animation: rotate 3s linear infinite; }
    @keyframes rotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

    .subcategories-wrapper { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; }
    .subcategory-col { display: flex; flex-direction: column; }
    .subcat-header {
        background: linear-gradient(135deg, var(--primary), var(--primary-hover)); color: white;
        padding: 1rem 1.5rem; border-radius: var(--radius-sm); font-weight: 800; font-size: 1.1rem;
        margin-bottom: 1.5rem; text-align: center; box-shadow: 0 4px 20px var(--primary-glow);
        position: relative; overflow: hidden;
    }
    .subcat-header::before {
        content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
        transform: rotate(45deg); animation: shine 3s infinite;
    }
    @keyframes shine { 0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); } 100% { transform: translateX(100%) translateY(100%) rotate(45deg); } }

    .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem; }

    /* Product Card */
    .product-card {
        background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-sm);
        overflow: hidden; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); position: relative;
        cursor: pointer; height: 100%; display: flex; flex-direction: column;
    }
    .product-card::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(135deg, var(--primary-glow), transparent); opacity: 0; transition: opacity 0.4s ease; z-index: 0;
    }
    .product-card:hover {
        transform: translateY(-8px) scale(1.02); border-color: var(--primary);
        box-shadow: 0 12px 40px rgba(0, 137, 123, 0.3), var(--shadow-lg);
    }
    .product-card:hover::before { opacity: 1; }

    .product-img-area {
        height: 160px; padding: 1rem; display: flex; align-items: center; justify-content: center;
        background: rgba(255, 255, 255, 0.02); position: relative; z-index: 1; overflow: hidden;
    }
    .product-img-area::after {
        content: ''; position: absolute; inset: 0;
        background: radial-gradient(circle at center, var(--primary-glow), transparent); opacity: 0; transition: opacity 0.4s ease;
    }
    .product-card:hover .product-img-area::after { opacity: 1; }
    .product-img-area img {
        max-width: 100%; max-height: 100%; object-fit: contain; position: relative; z-index: 2;
        filter: drop-shadow(0 4px 12px rgba(0,0,0,0.3)); transition: transform 0.4s ease;
    }
    .product-card:hover .product-img-area img { transform: scale(1.1) rotateY(5deg); }

    .product-details { padding: 1rem; flex-grow: 1; display: flex; flex-direction: column; position: relative; z-index: 1; }
    .product-brand { font-size: 0.7rem; color: var(--primary); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.5rem; }
    .product-title {
        font-size: 0.9rem; font-weight: 600; color: var(--text-main); margin-bottom: 1rem; line-height: 1.4;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 2.8em;
    }
    .product-bottom { margin-top: auto; display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 1px solid var(--border); }
    .price-tag {
        font-size: 1.2rem; font-weight: 900; background: linear-gradient(135deg, var(--primary), #00D9B8);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }
    .btn-select {
        background: linear-gradient(135deg, var(--primary), var(--primary-hover)); color: white; border: none;
        padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.8rem; font-weight: 700; cursor: pointer;
        transition: all 0.3s ease; display: flex; align-items: center; gap: 0.4rem; box-shadow: 0 4px 12px var(--primary-glow);
    }
    .btn-select:hover { transform: scale(1.05); box-shadow: 0 6px 20px var(--primary-glow); }

    /* Sidebar */
    .sidebar { position: sticky; top: 2rem; }
    .summary-card {
        background: rgba(21, 27, 43, 0.8); backdrop-filter: blur(20px); border-radius: var(--radius);
        border: 1px solid var(--border); box-shadow: var(--shadow-lg); overflow: hidden; position: relative;
    }
    .summary-card::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
        background: linear-gradient(90deg, var(--primary), var(--primary-hover), var(--primary));
        background-size: 200% 100%; animation: gradientMove 3s linear infinite;
    }
    @keyframes gradientMove { 0% { background-position: 0% 0%; } 100% { background-position: 200% 0%; } }

    .summary-header {
        background: linear-gradient(135deg, var(--dark-lighter), var(--dark)); color: white;
        padding: 1.5rem; font-weight: 800; font-size: 1.2rem; display: flex; align-items: center; gap: 0.75rem;
    }
    .summary-header i { font-size: 1.5rem; color: var(--primary); animation: bounce 2s infinite; }
    @keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-5px); } }

    .summary-body { padding: 1.5rem; max-height: 500px; overflow-y: auto; scrollbar-width: thin; scrollbar-color: var(--primary) var(--bg-card); }
    .summary-body::-webkit-scrollbar { width: 6px; }
    .summary-body::-webkit-scrollbar-track { background: var(--bg-card); }
    .summary-body::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 3px; }

    .selected-item {
        display: flex; gap: 1rem; align-items: center; padding: 1rem; border: 1px solid var(--border);
        border-radius: var(--radius-sm); margin-bottom: 1rem; background: var(--bg-card-hover);
        transition: all 0.3s ease; position: relative; overflow: hidden;
    }
    .selected-item::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 3px;
        background: var(--primary); transform: scaleY(0); transition: transform 0.3s ease;
    }
    .selected-item:hover { border-color: var(--primary); transform: translateX(5px); box-shadow: 0 4px 16px rgba(0, 137, 123, 0.2); }
    .selected-item:hover::before { transform: scaleY(1); }

    .selected-img { width: 50px; height: 50px; object-fit: contain; background: rgba(255, 255, 255, 0.03); border-radius: 6px; padding: 4px; border: 1px solid var(--border); }
    .selected-meta { flex: 1; }
    .selected-name { font-size: 0.85rem; font-weight: 600; color: var(--text-main); margin-bottom: 0.25rem; line-height: 1.3; }
    .selected-price { font-size: 0.9rem; font-weight: 700; color: var(--primary); }

    .btn-remove-sm {
        width: 28px; height: 28px; border-radius: 50%; border: 2px solid var(--danger); color: var(--danger);
        background: transparent; display: flex; align-items: center; justify-content: center; cursor: pointer;
        font-size: 1.1rem; transition: all 0.3s ease; font-weight: 700;
    }
    .btn-remove-sm:hover { background: var(--danger); color: white; transform: rotate(90deg) scale(1.1); }

    .summary-footer { padding: 1.5rem; background: var(--dark-lighter); border-top: 1px solid var(--border); }
    .total-price-area {
        background: linear-gradient(135deg, var(--primary), var(--primary-hover)); padding: 1.5rem;
        border-radius: var(--radius-sm); margin-bottom: 1.5rem; text-align: center; box-shadow: 0 8px 24px var(--primary-glow);
    }
    .total-label { font-size: 0.9rem; color: rgba(255, 255, 255, 0.8); font-weight: 600; margin-bottom: 0.5rem; display: block; }
    .total-amount { font-size: 2rem; font-weight: 900; color: white; display: block; text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3); }

    .config-input {
        width: 100%; padding: 1rem; border: 2px solid var(--border); border-radius: var(--radius-sm);
        margin-bottom: 1rem; font-family: inherit; font-size: 0.95rem; background: var(--bg-card);
        color: var(--text-main); transition: all 0.3s ease;
    }
    .config-input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 4px var(--primary-glow); }

    .btn-save-config {
        width: 100%; padding: 1.2rem; background: linear-gradient(135deg, var(--success), #059669); color: white;
        border: none; border-radius: var(--radius-sm); font-weight: 700; font-size: 1rem; cursor: pointer;
        transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 0.75rem;
        box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3); text-transform: uppercase; letter-spacing: 0.5px;
    }
    .btn-save-config:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(16, 185, 129, 0.4); }
    .btn-save-config:active { transform: translateY(0); }

    .btn-reset {
        width: 100%; margin-top: 0.75rem; padding: 0.8rem; background: transparent; border: 1px solid var(--border);
        color: var(--text-muted); cursor: pointer; font-size: 0.9rem; border-radius: var(--radius-sm); transition: all 0.3s ease;
    }
    .btn-reset:hover { color: var(--danger); border-color: var(--danger); background: rgba(239, 68, 68, 0.1); }

    .empty-state { text-align: center; padding: 3rem 1rem; color: var(--text-muted); }
    .empty-state i { font-size: 3rem; margin-bottom: 1rem; opacity: 0.3; display: block; }

    .wizard-step { display: none; }
    .wizard-step.active { display: block; animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1); }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

    .compatibility-badge {
        position: absolute; top: 10px; right: 10px; background: linear-gradient(135deg, var(--success), #059669);
        color: white; padding: 0.3rem 0.6rem; border-radius: 4px; font-size: 0.7rem; font-weight: 700;
        z-index: 3; box-shadow: 0 2px 8px rgba(16, 185, 129, 0.4);
    }

    @media (max-width: 1400px) { .wizard-content { grid-template-columns: 1fr 380px; } }
    @media (max-width: 1200px) {
        .wizard-content { grid-template-columns: 1fr; }
        .sidebar { position: relative; top: 0; }
        .subcategories-wrapper { grid-template-columns: 1fr; }
    }
    @media (max-width: 768px) {
        .wizard-header h1 { font-size: 2rem; }
        .step-title { font-size: 1.3rem; }
        .products-grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); }
        .stepper { overflow-x: auto; padding-bottom: 1rem; }
    }
</style>

<div class="particles" id="particles"></div>

<div class="wizard-container">
    <div class="wizard-header">
        <h1>PC Toplama Sihirbazı</h1>
        <p>Hayalinizdeki sistemi adım adım oluşturun 🚀</p>
    </div>

    <div class="stepper-wrapper">
        <div class="stepper" id="wizard-stepper">
            @foreach($kategoriler as $index => $kategori)
                <div class="stepper-item {{ $index == 0 ? 'active' : '' }}" data-step="{{ $index }}">
                    <div class="step-circle">
                        <span>{{ $index + 1 }}</span>
                    </div>
                    <div class="step-label">{{ $kategori->kategori_ad }}</div>
                </div>
            @endforeach
        </div>
        <div class="stepper-progress" style="width: 0%"></div>
    </div>

    <div class="wizard-content">
        <div class="products-section">
            <div id="wizard-steps">
                @foreach($kategoriler as $index => $kategori)
                    <div class="wizard-step {{ $index == 0 ? 'active' : '' }}" data-step="{{ $index }}">
                        
                        <div class="step-title">
                            <i class="fas fa-layer-group"></i>
                            <span>{{ $kategori->kategori_ad }} Seçimi</span>
                        </div>

                        <div class="subcategories-wrapper">
                            @foreach($kategori->altKategoriler as $alt)
                                @php
                                    // İsim sadeleştirme
                                    $temizAd = trim(str_ireplace($kategori->kategori_ad, '', $alt->alt_kategori_ad));
                                    if(empty($temizAd) || strlen($temizAd) < 2) {
                                        $temizAd = $alt->alt_kategori_ad;
                                    }
                                @endphp

                                <div class="subcategory-col">
                                    <div class="subcat-header">
                                        {{ $temizAd }}
                                    </div>

                                    <div class="category-body urun-list products-grid" 
                                         data-altkategoriid="{{ $alt->id }}" 
                                         data-step="{{ $index }}">
                                        <div class="empty-state" style="grid-column: 1/-1;">
                                            <i class="fas fa-spinner fa-spin"></i>
                                            <p>Yükleniyor...</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                @endforeach
            </div>
        </div>

        <div class="sidebar">
            <div class="summary-card">
                <div class="summary-header">
                    <i class="fas fa-desktop"></i>
                    <span>Sistem Özeti</span>
                </div>
                
                <div class="summary-body" id="selected-products">
                    <div class="empty-state">
                        <i class="fas fa-box-open"></i>
                        <p>Henüz parça eklenmedi.</p>
                        <p style="font-size: 0.85rem; margin-top: 0.5rem;">Sihirbazı kullanarak parçaları seçmeye başlayın!</p>
                    </div>
                </div>
                
                <div class="summary-footer">
                    <div class="total-price-area">
                        <span class="total-label">Toplam Tutar:</span>
                        <span class="total-amount" id="grand-total">0 ₺</span>
                    </div>

                    <input type="text" 
                           id="konfig-isim" 
                           class="config-input" 
                           placeholder="🎮 Sistem Adı (Örn: Oyun Canavarı)"
                           required>
                    
                    <button class="btn-save-config" id="save-progress">
                        <i class="fas fa-save"></i>
                        <span>Kaydet ve Bitir</span>
                    </button>
                    
                    <button class="btn-reset" id="clear-selection">
                        <i class="fas fa-trash-alt"></i> Tümünü Temizle
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // --- GÖRSEL EFEKTLER (PARTICLES) ---
    function createParticles() {
        const container = document.getElementById('particles');
        const particleCount = 30;
        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.top = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 20 + 's';
            particle.style.animationDuration = (15 + Math.random() * 10) + 's';
            container.appendChild(particle);
        }
    }
    createParticles();

    // --- SİHİRBAZ MANTIĞI (JAVASCRIPT) ---
    let currentStep = 0;
    const singleSelectCategories = ['İşlemci','Anakart','RAM','Ekran Kartı', 'Kasa', 'Güç Kaynağı (PSU)', 'Soğutucu'];
    let selectedUrun = {};
    const stepperItems = document.querySelectorAll('.stepper-item');
    const totalSteps = stepperItems.length;

    // Stepper ve İçerik Gösterimi Güncelleme
    function updateStepper(){
        // Stepper Yuvarlakları
        stepperItems.forEach((item, i)=>{
            item.classList.remove('active', 'completed');
            if(i < currentStep) item.classList.add('completed');
            if(i === currentStep) item.classList.add('active');
        });

        // Progress Bar Width Hesaplama
        const progress = (currentStep / (totalSteps - 1)) * 100;
        const progressBar = document.querySelector('.stepper-progress');
        if(progressBar) progressBar.style.width = (totalSteps > 1 ? progress : 0) + '%';

        // İlgili Wizard Step'i Göster/Gizle (Kategori Geçişi Düzeltmesi)
        document.querySelectorAll('.wizard-step').forEach((s,i)=>{
            s.classList.remove('active');
            if(i === currentStep) {
                s.classList.add('active');
                // Bu adım aktif olduğunda içindeki ürün listelerini tetikle
                const lists = s.querySelectorAll('.urun-list');
                lists.forEach(list => list.dispatchEvent(new Event('reloadUrun')));
            }
        });
    }

    // Adım Değiştirme Fonksiyonu
    function goToStep(stepIndex){
        if (stepIndex >= totalSteps) {
            stepIndex = totalSteps - 1;
            document.getElementById('konfig-isim').focus();
        } else if(stepIndex < 0) {
            stepIndex = 0;
        }
        
        currentStep = stepIndex;
        updateStepper();
        // Sayfa başına yumuşak kaydır
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Stepper Tıklama Olayları
    stepperItems.forEach(stepEl=>{
        stepEl.addEventListener('click', ()=> {
            const targetStep = parseInt(stepEl.dataset.step);
            goToStep(targetStep);
        });
    });

    // Toplam Tutar Hesaplama
    function calculateTotal() {
        let total = 0;
        Object.values(selectedUrun).forEach(group => {
            const items = Array.isArray(group) ? group : [group];
            items.forEach(item => {
                // Fiyat temizleme: "12.500,00 ₺" -> 12500.00
                let priceStr = String(item.fiyat);
                // Sadece rakam ve virgül kalsın, noktaları sil (binlik ayracı)
                // Eğer formatınız farklıysa burayı veritabanı çıktınıza göre düzenleyin.
                // Genelde: str_replace('.','',$fiyat) backend'de yapmak daha güvenlidir.
                // Basit regex temizliği:
                let price = parseFloat(priceStr.replace(/[^0-9.-]+/g,"")); 
                if(isNaN(price)) price = 0;
                total += price * (item.adet || 1);
            });
        });
        document.getElementById('grand-total').innerText = new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY', minimumFractionDigits: 0 }).format(total);
    }

    // Sidebar (Seçilenler) Güncelleme
    function updateSelectedBox(){
        const container = document.getElementById('selected-products');
        container.innerHTML = '';
        const steps = Object.keys(selectedUrun);

        if(steps.length===0){
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-box-open"></i>
                    <p>Henüz parça eklenmedi.</p>
                    <p style="font-size: 0.85rem; margin-top: 0.5rem;">Sihirbazı kullanarak parçaları seçmeye başlayın!</p>
                </div>`;
            calculateTotal();
            return;
        }

        // Adım sırasına göre listele
        steps.sort((a, b) => a - b).forEach(step=>{
            const urunler = Array.isArray(selectedUrun[step]) ? selectedUrun[step] : [selectedUrun[step]];
            
            urunler.forEach(urun=>{
                const div = document.createElement('div');
                div.className = 'selected-item';
                div.innerHTML = `
                    <img src="${urun.resim || 'https://via.placeholder.com/50'}" class="selected-img" alt="Ürün">
                    <div class="selected-meta">
                        <div class="selected-name" title="${urun.urun_ad}">${urun.urun_ad}</div>
                        <div class="selected-price">${urun.fiyat} ₺ ${urun.adet > 1 ? 'x'+urun.adet : ''}</div>
                    </div>
                    <button class="btn-remove-sm remove-urun" title="Çıkar">×</button>
                `;
                
                // Silme Butonu
                div.querySelector('.remove-urun').addEventListener('click', ()=>{
                    if(Array.isArray(selectedUrun[step])){
                        selectedUrun[step] = selectedUrun[step].filter(u=>u.id!==urun.id);
                        if(selectedUrun[step].length===0) delete selectedUrun[step];
                    } else {
                        delete selectedUrun[step];
                    }
                    updateSelectedBox();
                    // Ürün listelerini yenile (ekle butonlarını güncellemek için gerekebilir)
                    document.querySelectorAll('.urun-list').forEach(d=> d.dispatchEvent(new Event('reloadUrun')));
                });

                container.appendChild(div);
            });
        });
        calculateTotal();
    }

    // Temizle Butonu
    document.getElementById('clear-selection').addEventListener('click', ()=>{
        if(Object.keys(selectedUrun).length === 0) return;
        if(!confirm('Tüm sistemi sıfırlamak istiyor musunuz?')) return;
        
        selectedUrun = {};
        updateSelectedBox();
        document.querySelectorAll('.urun-list').forEach(div=> div.dispatchEvent(new Event('reloadUrun')));
        goToStep(0);
    });

    // KAYDET VE BİTİR BUTONU
    document.getElementById('save-progress').addEventListener('click', ()=>{
        let isim = document.getElementById('konfig-isim').value.trim();
        if(!isim) {
            alert("Lütfen sisteminize bir isim verin!");
            document.getElementById('konfig-isim').focus();
            return;
        }

        let urunler = [];
        Object.keys(selectedUrun).forEach(step=>{
            const urunlerArr = Array.isArray(selectedUrun[step]) ? selectedUrun[step] : [selectedUrun[step]];
            urunlerArr.forEach(u=> {
                let price = parseFloat(String(u.fiyat).replace(/[^0-9.-]+/g,""));
                urunler.push({id:u.id, adet:u.adet, fiyat: price});
            });
        });

        if(urunler.length===0){ 
            alert("Lütfen en az bir parça seçin!"); 
            return; 
        }

        const saveBtn = document.getElementById('save-progress');
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Kaydediliyor...';

        // AJAX POST
        fetch('/wizard/konfigurasyon-kaydet', {
            method:'POST',
            headers: {
                'Content-Type':'application/json',
                'X-CSRF-TOKEN':'{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({isim, urunler})
        })
        .then(response => {
            if (!response.ok) return response.json().then(err => { throw new Error(err.message); });
            return response.json();
        })
        .then(data=>{
            if(data.success && data.redirect_url){
                alert("Sistem başarıyla kaydedildi! 🎉");
                window.location.href = data.redirect_url;
            } else {
                alert(data.message || "Hata oluştu!");
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="fas fa-save"></i> Kaydet ve Bitir';
            }
        })
        .catch(error => {
            console.error('Hata:', error);
            alert("Hata oluştu: " + error.message);
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<i class="fas fa-save"></i> Kaydet ve Bitir';
        });
    });

    // --- AJAX İLE ÜRÜN YÜKLEME ---
    document.querySelectorAll('.urun-list').forEach(div=>{
        const altKategoriId = div.dataset.altkategoriid;
        const step = div.dataset.step;

        // Ürünleri Çeken Fonksiyon
        function loadUrunler(){
            // Kategorinin adını bul (Single Select kontrolü için)
            const kategoriAd = div.closest('.wizard-step').querySelector('.step-title span').innerText.replace(' Seçimi','');
            
            // Uyumluluk Parametrelerini Hazırla
            const prevSteps = Object.keys(selectedUrun).filter(s=>parseInt(s) < parseInt(step));
            let uyumlulukParams = '';
            if(prevSteps.length>0){
                const prevIds = prevSteps.map(s=>{ 
                    const arr = Array.isArray(selectedUrun[s]) ? selectedUrun[s] : [selectedUrun[s]]; 
                    return arr.map(u=>u.id); 
                }).flat();
                if(prevIds.length>0) uyumlulukParams = '?selected_urun_id='+prevIds.join(',');
            }

            // Yükleniyor ikonu göster
            div.innerHTML = `<div class="empty-state" style="grid-column: 1/-1;"><i class="fas fa-spinner fa-spin"></i><p>Ürünler yükleniyor...</p></div>`;

            // Fetch İsteği
            fetch(`/wizard/urunler/${altKategoriId}${uyumlulukParams}`)
            .then(res=>res.json())
            .then(data=>{
                div.innerHTML=''; // İçeriği temizle
                
                if(data.length===0){ 
                    div.innerHTML = `<div class="empty-state" style="grid-column: 1 / -1; padding: 1rem;"><i class="fas fa-exclamation-circle"></i><p>Uyumlu parça bulunamadı.</p></div>`;
                    return; 
                }

                data.forEach(urun=>{
                    // Kart HTML Elemanını Oluştur
                    const item = document.createElement('div');
                    item.className='product-card';
                    
                    // --- MODERN KART HTML ---
                    // Not: urun.resim veritabanından geliyor. Eğer null ise placeholder kullanılır.
                    const imgSrc = urun.resim ? urun.resim : 'https://via.placeholder.com/150?text=Resim+Yok';
                    
                    item.innerHTML=`
                        <div class="compatibility-badge">✓ Uyumlu</div>
                        <div class="product-img-area">
                            <img src="${imgSrc}" alt="${urun.urun_ad}" loading="lazy">
                        </div>
                        <div class="product-details">
                            <div class="product-brand">${urun.marka || ''}</div>
                            <div class="product-title" title="${urun.urun_ad}">${urun.urun_ad}</div>
                            
                            <div class="product-bottom">
                                <div class="price-tag">${urun.fiyat} ₺</div>
                                <button class="btn-select">
                                    <i class="fas fa-plus"></i> Ekle
                                </button>
                            </div>
                        </div>
                    `;
                    
                    // Ekle Butonu Dinleyici
                    item.querySelector('button').addEventListener('click', (e)=>{
                        e.stopPropagation(); // Kartın kendisine tıklanmasını engelle
                        
                        // Buton animasyonu
                        const btn = item.querySelector('.btn-select');
                        btn.style.transform = 'scale(0.95)';
                        setTimeout(() => btn.style.transform = '', 150);

                        urun.kategoriAd = kategoriAd; 

                        // Tekli Seçim mi Çoklu Seçim mi?
                        if(singleSelectCategories.includes(kategoriAd)){
                            selectedUrun[step] = {...urun, adet:1};
                            // Tekli seçimse otomatik sonraki adıma geç
                            setTimeout(() => goToStep(parseInt(step)+1), 300);
                        } else {
                            if(!Array.isArray(selectedUrun[step])) selectedUrun[step] = [];
                            const existing = selectedUrun[step].find(u=>u.id===urun.id);
                            if(existing) {
                                existing.adet++;
                            } else {
                                selectedUrun[step].push({...urun, adet:1});
                            }
                        }
                        updateSelectedBox();
                        
                        // Sonraki adımları yenile (Uyumluluk değişmiş olabilir)
                        document.querySelectorAll('.urun-list').forEach(d=> {
                            if (d.dataset.step > step) {
                                d.dispatchEvent(new Event('reloadUrun'));
                            }
                        });
                    });
                    
                    div.appendChild(item);
                });
            })
            .catch(error => {
                console.error(error);
                div.innerHTML = `<div class="empty-state" style="grid-column: 1 / -1;"><p class="text-danger">Veri yüklenirken hata oluştu!</p></div>`;
            });
        }

        // Custom Event Dinleyici
        div.addEventListener('reloadUrun', loadUrunler);
        
        // Eğer bu adım şu an aktifse hemen yükle
        if (div.closest('.wizard-step').classList.contains('active')) {
            loadUrunler();
        }
    });

    // Başlangıç Ayarı
    updateStepper();
</script>
@endsection