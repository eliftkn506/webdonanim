@extends('layouts.app')

@section('content')
<div class="container-fluid py-5" style="max-width: 1400px; background-color:#ecf0f1;">
    <h1 class="mb-5 text-center fw-bold text-dark">
        PC Toplama Sihirbazı
        <span style="display:block; width:90px; height:4px; background:#007bff; margin:10px auto; border-radius:4px;"></span>
    </h1>

    <div class="row gx-4">
        <!-- Wizard Adımları ve Ürün Seçimi -->
        <div class="col-lg-8">
            <div class="d-flex justify-content-between mb-4" id="wizard-stepper">
                @foreach($kategoriler as $index => $kategori)
                    <div class="step text-center flex-fill" data-step="{{ $index }}">
                        <div class="step-circle {{ $index == 0 ? 'active' : '' }}">{{ $index + 1 }}</div>
                        <div class="step-label">{{ $kategori->kategori_ad }}</div>
                    </div>
                @endforeach
            </div>

            <div id="wizard-steps">
                @foreach($kategoriler as $index => $kategori)
                    <div class="wizard-step {{ $index == 0 ? 'active' : 'd-none' }}" data-step="{{ $index }}">
                        <h4 class="mb-4 fw-semibold text-primary">{{ $kategori->kategori_ad }} Seçiniz</h4>

                        <div class="row g-3">
                            @foreach($kategori->altKategoriler as $alt)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card shadow-sm h-100 border-0 product-card">
                                        <div class="card-header bg-primary text-white fw-bold">
                                            {{ $alt->alt_kategori_ad }}
                                        </div>
                                        <div class="card-body urun-list" data-altkategoriid="{{ $alt->id }}" data-step="{{ $index }}">
                                            <p class="text-muted">Ürünler yükleniyor...</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                @endforeach
            </div>
        </div>

        <!-- Seçilen Ürünler -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-bold">
                    Seçilen Ürünler
                </div>
                <div id="selected-products" class="card-body bg-light" style="min-height:250px;">
                    <p class="text-muted">Henüz ürün seçilmedi.</p>
                </div>
                <div class="card-footer bg-white d-flex flex-column gap-2">
                    <input type="text" id="konfig-isim" class="form-control" placeholder="Konfigürasyonunuza bir isim verin">
                    <button class="btn btn-outline-danger w-100" id="clear-selection">Seçimi Temizle</button>
                    <button class="btn btn-success w-100" id="save-progress">Kaydet ve İlerle</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// --- Mevcut JS Mantığı ---
// (Tüm seçilen ürün güncelleme, adım geçişleri, ürün yükleme ve kaydetme mantığı korunuyor)
let currentStep = 0;
const singleSelectCategories = ['İşlemci','Anakart','RAM','Ekran Kartı'];
let selectedUrun = {};

function updateStepper(){
    document.querySelectorAll('#wizard-stepper .step-circle').forEach((circle, i)=>{
        circle.classList.remove('active', 'completed');
        if(i < currentStep) circle.classList.add('completed');
        if(i === currentStep) circle.classList.add('active');
    });
}

function goToStep(stepIndex){
    document.querySelectorAll('.wizard-step').forEach((s,i)=>{
        s.classList.toggle('d-none', i!==stepIndex);
        s.classList.toggle('active', i===stepIndex);
    });
    currentStep = stepIndex;
    updateStepper();
}

document.querySelectorAll('#wizard-stepper .step').forEach(stepEl=>{
    stepEl.addEventListener('click', ()=> goToStep(parseInt(stepEl.dataset.step)));
});

// Seçilen ürün kutusunu güncelle
function updateSelectedBox(){
    const container = document.getElementById('selected-products');
    container.innerHTML = '';
    const steps = Object.keys(selectedUrun);

    if(steps.length===0){
        container.innerHTML = '<p class="text-muted">Henüz ürün seçilmedi.</p>';
        return;
    }

    steps.forEach(step=>{
        const urunler = Array.isArray(selectedUrun[step]) ? selectedUrun[step] : [selectedUrun[step]];
        urunler.forEach(urun=>{
            const div = document.createElement('div');
            div.className = 'mb-2 p-2 border rounded bg-white d-flex align-items-center shadow-sm justify-content-between';
            div.innerHTML = `
                <div class="d-flex align-items-center">
                    <img src="${urun.resim && urun.resim.trim()!=='' ? urun.resim : 'https://via.placeholder.com/50'}" 
                         alt="resim" class="me-3 rounded border" width="50" height="50">
                    <div>
                        <div class="fw-bold text-dark">${urun.urun_ad}</div>
                        <small class="text-muted">${urun.marka} - ${urun.model}</small><br>
                        <span class="badge bg-secondary">Adet: ${urun.adet || 1}</span>
                        <span class="badge bg-success ms-2">${urun.fiyat || 0} ₺</span>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <button class="btn btn-sm btn-outline-secondary me-1 decrease-adet">-</button>
                    <button class="btn btn-sm btn-outline-secondary me-2 increase-adet">+</button>
                    <button class="btn btn-sm btn-outline-danger remove-urun">X</button>
                </div>
            `;

            div.querySelector('.decrease-adet').addEventListener('click', ()=>{ if(urun.adet>1){ urun.adet--; updateSelectedBox(); } });
            div.querySelector('.increase-adet').addEventListener('click', ()=>{ urun.adet++; updateSelectedBox(); });
            div.querySelector('.remove-urun').addEventListener('click', ()=>{
                if(Array.isArray(selectedUrun[step])){
                    selectedUrun[step] = selectedUrun[step].filter(u=>u.id!==urun.id);
                    if(selectedUrun[step].length===0) delete selectedUrun[step];
                } else delete selectedUrun[step];
                updateSelectedBox();
            });

            container.appendChild(div);
        });
    });
}

// Temizle ve kaydet işlevleri
document.getElementById('clear-selection').addEventListener('click', ()=>{
    selectedUrun = {};
    updateSelectedBox();
    document.querySelectorAll('.urun-list').forEach(div=> div.dispatchEvent(new Event('reloadUrun')));
});

document.getElementById('save-progress').addEventListener('click', ()=>{
    let isim = document.getElementById('konfig-isim').value.trim();
    if(!isim) isim = "Yeni Konfigürasyon";

    let urunler = [];
    Object.keys(selectedUrun).forEach(step=>{
        const urunlerArr = Array.isArray(selectedUrun[step]) ? selectedUrun[step] : [selectedUrun[step]];
        urunlerArr.forEach(u=> urunler.push({id:u.id, adet:u.adet, fiyat:u.fiyat||0}));
    });

    if(urunler.length===0){ alert("Lütfen önce ürün seçin!"); return; }

    fetch('/wizard/konfigurasyon-kaydet', {
        method:'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify({isim, urunler})
    })
    .then(res=>res.json())
    .then(data=>{
        if(data.success){
            alert(data.message);
            selectedUrun = {};
            updateSelectedBox();
            document.querySelectorAll('.urun-list').forEach(div=> div.dispatchEvent(new Event('reloadUrun')));
            document.getElementById('konfig-isim').value = '';
        } else alert("Hata oluştu!");
    });
});

// Ürünleri yükleme
document.querySelectorAll('.urun-list').forEach(div=>{
    const altKategoriId = div.dataset.altkategoriid;
    const step = div.dataset.step;

    function loadUrunler(){
        const kategoriAd = div.closest('.wizard-step').querySelector('h4').innerText.replace(' Seçiniz','');
        const prevSteps = Object.keys(selectedUrun).filter(s=>parseInt(s) < parseInt(step));
        let uyumlulukParams = '';
        if(prevSteps.length>0){
            const prevIds = prevSteps.map(s=>{ const arr = Array.isArray(selectedUrun[s]) ? selectedUrun[s] : [selectedUrun[s]]; return arr.map(u=>u.id); }).flat();
            if(prevIds.length>0) uyumlulukParams = '?selected_urun_id='+prevIds.join(',');
        }

        fetch(`/wizard/urunler/${altKategoriId}${uyumlulukParams}`)
        .then(res=>res.json())
        .then(data=>{
            div.innerHTML='';
            if(data.length===0){ div.innerHTML='<p class="text-muted">Ürün bulunamadı.</p>'; return; }

            data.forEach(urun=>{
                const card = document.createElement('div');
                card.className='card mb-3 shadow-sm hover-scale';
                card.innerHTML=`
                    <div class="row g-0 align-items-center">
                        <div class="col-4 p-2">
                            <img src="${urun.resim && urun.resim.trim()!=='' ? urun.resim : 'https://via.placeholder.com/80'}"
                                 class="img-fluid rounded border" alt="${urun.urun_ad}">
                        </div>
                        <div class="col-8">
                            <div class="card-body p-2">
                                <h6 class="fw-bold text-dark mb-1">${urun.urun_ad}</h6>
                                <small class="text-muted">${urun.marka} - ${urun.model}</small>
                                <div class="fw-bold text-success mt-1">${urun.fiyat || 0} ₺</div>
                                <button class="btn btn-sm btn-primary w-100 mt-2">Seç</button>
                            </div>
                        </div>
                    </div>
                `;
                card.querySelector('button').addEventListener('click', ()=>{
                    if(singleSelectCategories.includes(kategoriAd)){
                        selectedUrun[step] = {...urun, adet:1};
                        goToStep(parseInt(step)+1);
                    } else {
                        if(!Array.isArray(selectedUrun[step])) selectedUrun[step] = [];
                        const existing = selectedUrun[step].find(u=>u.id===urun.id);
                        if(existing) existing.adet++;
                        else selectedUrun[step].push({...urun, adet:1});
                    }
                    updateSelectedBox();
                    document.querySelectorAll('.urun-list').forEach(d=> d.dispatchEvent(new Event('reloadUrun')));
                });
                div.appendChild(card);
            });
        });
    }

    div.addEventListener('reloadUrun', loadUrunler);
    loadUrunler();
});
</script>

<style>
body { font-family:'Segoe UI',sans-serif; }
#wizard-stepper { display:flex; justify-content:space-between; margin-bottom:30px; }
.step { position:relative; flex:1; cursor:pointer; text-align:center; }
.step:not(:last-child)::after { content:''; position:absolute; top:24px; right:-50%; width:100%; height:4px; background:#ccc; z-index:0; transition:background 0.3s; }
.step-circle { width:48px;height:48px;line-height:48px;border-radius:50%; background:#ccc;color:#fff;font-weight:bold;margin:0 auto; z-index:1; position:relative; transition:all 0.3s; }
.step-circle.active { background:#007bff; }
.step-circle.completed { background:#28a745; }
.step-label { margin-top:8px; font-size:0.95rem; font-weight:500; color:#2C3E50; }
.step:hover .step-circle { transform:scale(1.1); }
.step.active .step-label { color:#007bff; }

.wizard-step { border:1px solid #dcdcdc;padding:25px;border-radius:8px;background:#fff;margin-bottom:20px; }
.product-card { border-radius:0.5rem; margin-bottom:20px; }
.urun-list { min-height:150px; display:flex; flex-direction:column; gap:12px; }
.hover-scale { transition: transform 0.2s, box-shadow 0.2s; cursor:pointer; }
.hover-scale:hover { transform: translateY(-5px); box-shadow:0 6px 12px rgba(0,0,0,0.15); }

#selected-products { max-height:500px; overflow-y:auto; }
</style>
@endsection
