<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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
                            <p class="card-text">{{ $buy->branch }} Branch</p>
                            
                            {{-- Calculate average rating --}}
                            @php
                                // Fetch all ratings for the current item
                                $ratings = App\Models\Rating::where('purchase_id', $buy->id)->pluck('rating')->toArray();
                                $totalRatings = count($ratings);
                                $averageRating = $totalRatings > 0 ? array_sum($ratings) / $totalRatings : 0;
                            @endphp

                            <!-- Display the "Rating" text and coffee cup icons -->
                            <h6 class="mr-2">Rating:</h6>
                            @for ($i = 1; $i <= 5; $i++)
                                @php
                                    // Determine the glow effect based on the position and fractional part of the average rating
                                    if ($i <= $averageRating) {
                                        $glowClass = 'text-warning'; // Full glowing cup
                                    } else {
                                        $glowClass = ''; // No glow
                                    }
                                @endphp
                                <i class="fas fa-coffee fa-1x coffee-icon {{ $glowClass }}"></i>
                            @endfor
                            <p>({{ $totalRatings }})</p>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
