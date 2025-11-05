@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Favori Ürünlerim</h3>
    <div class="row">
        @forelse($favoriler as $fav)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <img src="{{ $fav->urun->resim_url }}" class="card-img-top" alt="{{ $fav->urun->urun_ad }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $fav->urun->urun_ad }}</h5>
                        <p class="card-text">{{ $fav->urun->fiyat }} ₺</p>
                        <button class="btn btn-warning btn-favorite" data-id="{{ $fav->urun->id }}">
                            Favoriden Çıkar
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <p>Henüz favori ürününüz yok.</p>
        @endforelse
    </div>
</div>

<script>
document.querySelectorAll('.btn-favorite').forEach(button => {
    button.addEventListener('click', function() {
        let urunId = this.dataset.id;
        fetch("{{ route('favori.toggle') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ urun_id: urunId })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                if(data.is_favorite){
                    this.textContent = 'Favoriden Çıkar';
                } else {
                    this.textContent = 'Favorilere Ekle';
                    this.closest('.col-md-4').remove(); // listeden kaldır
                }
                alert(data.message);
            } else {
                alert(data.message);
            }
        });
    });
});
</script>
@endsection
