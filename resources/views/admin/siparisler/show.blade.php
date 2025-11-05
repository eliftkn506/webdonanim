@extends('layouts.admin')

@section('title', 'Sipariş Detayı - Admin Panel')

@section('content')
<div class="container py-4">

    <h2 class="mb-4">Sipariş Detayı: #{{ $siparis->siparis_no }}</h2>

    <div class="card mb-4">
        <div class="card-header">
            <strong>Müşteri Bilgileri</strong>
        </div>
        <div class="card-body">
            <p><strong>Ad Soyad:</strong> {{ $siparis->user->name ?? '-' }}</p>
            <p><strong>Email:</strong> {{ $siparis->user->email ?? '-' }}</p>
            <p><strong>Telefon:</strong> {{ $siparis->user->telefon ?? '-' }}</p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <strong>Sipariş Bilgileri</strong>
        </div>
        <div class="card-body">
            <p><strong>Toplam Tutar:</strong> {{ number_format($siparis->toplam_tutar, 2) }} ₺</p>
            <p><strong>KDV:</strong> {{ number_format($siparis->kdv_tutari, 2) }} ₺</p>
            <p><strong>İndirim:</strong> {{ number_format($siparis->indirim_tutari, 2) }} ₺</p>
            <p><strong>Durum:</strong> <span id="currentDurum">{{ $siparis->durum }}</span></p>
            <p><strong>Ödeme Durumu:</strong> <span id="currentOdeme">{{ $siparis->odeme_durumu }}</span></p>
        </div>
    </div>

    <!-- Sipariş Durumu Güncelleme -->
    <div class="card mb-4">
        <div class="card-header">
            <strong>Sipariş Durumu Güncelle</strong>
        </div>
        <div class="card-body">
            <label for="durum" class="form-label">Sipariş Durumu</label>
            <select id="durum" class="form-select mb-2">
                <option value="beklemede" {{ $siparis->durum == 'beklemede' ? 'selected' : '' }}>Beklemede</option>
                <option value="onaylandi" {{ $siparis->durum == 'onaylandi' ? 'selected' : '' }}>Onaylandı</option>
                <option value="hazirlaniyor" {{ $siparis->durum == 'hazirlaniyor' ? 'selected' : '' }}>Hazırlanıyor</option>
                <option value="kargoda" {{ $siparis->durum == 'kargoda' ? 'selected' : '' }}>Kargoda</option>
                <option value="teslim_edildi" {{ $siparis->durum == 'teslim_edildi' ? 'selected' : '' }}>Teslim Edildi</option>
                <option value="iptal_edildi" {{ $siparis->durum == 'iptal_edildi' ? 'selected' : '' }}>İptal Edildi</option>
            </select>

            <label for="durum_not" class="form-label mt-2">Not (Opsiyonel)</label>
            <textarea id="durum_not" class="form-control mb-2" placeholder="Not ekleyin..."></textarea>

            <button id="durumGuncelleBtn" class="btn btn-primary mt-2">Güncelle</button>
        </div>
    </div>

    <!-- Sipariş Notları -->
    <div class="card">
        <div class="card-header">
            <strong>Sipariş Notları</strong>
        </div>
        <div class="card-body">
            <pre style="white-space: pre-wrap;">{{ $siparis->notlar ?? 'Henüz not eklenmemiş' }}</pre>
        </div>
    </div>

</div>

@section('scripts')
<script>
document.getElementById('durumGuncelleBtn').addEventListener('click', function() {
    let siparisId = {{ $siparis->id }};
    let durum = document.getElementById('durum').value;
    let not = document.getElementById('durum_not').value;

    fetch("{{ route('admin.siparisler.durumGuncelle', $siparis->id) }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ durum: durum, not: not })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            alert(data.message);
            document.getElementById('currentDurum').innerText = data.durum;
            document.getElementById('durum_not').value = '';
        } else {
            alert("Bir hata oluştu");
        }
    })
    .catch(err => {
        console.error(err);
        alert("Sunucu hatası oluştu");
    });
});
</script>
@endsection

@endsection
