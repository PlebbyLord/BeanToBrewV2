<div class="container text-center">
    <div class="row">
        @foreach($userItems as $buy)
                <div class="col card-column" style="margin-bottom: 10px;">
                    <div class="card" style="width: 10rem; cursor: pointer;">
                        {{-- Make only the card content clickable --}}
                        <div class="card-body">
                            <a href="{{ route('viewitem.showItem.query', ['id' => $buy->id]) }}" style="text-decoration: none; color: inherit;">
                                <img src="{{ asset('storage/' . $buy->item_image) }}" class="card-img-top" alt="{{ $buy->item_name }}">
                                <h5 class="card-title">{{ $buy->item_name }}</h5>
                                <p class="card-text">{{ $buy->item_price }}/Kilo</p>
                                <p class="card-text">Expiry Date {{ $buy->expiry_date }}</p>
                            </a>
                        </div>
                    </div>
                </div>
        @endforeach
    </div>
</div>
