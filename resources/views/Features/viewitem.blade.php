<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5>Selected Item Details</h5>
                            <!-- Display the selected item details -->
                        </div>
                        @php
                        // Calculate the average rating for the specific item
                            $ratings = App\Models\Rating::where('purchase_id', $selectedItem->id)->pluck('rating')->toArray();
                            $totalRatings = count($ratings);
                            $averageRating = $totalRatings > 0 ? array_sum($ratings) / $totalRatings : 0;
                            
                            // Determine the integer and fractional parts of the average rating
                            $integerPart = floor($averageRating);
                            $fractionalPart = $averageRating - $integerPart;
                        @endphp
                    
                    <div class="col-md-6 d-flex justify-content-end align-items-center">
                        <!-- Display the "Rating" text and coffee cup icons -->
                        <h6 class="mr-2">Rating:</h6>
                        @for ($i = 1; $i <= 5; $i++)
                            @php
                                // Determine the glow effect based on the position and fractional part of the average rating
                                if ($i <= $integerPart) {
                                    $glowClass = 'text-warning'; // Full glowing cup
                                } elseif ($i == $integerPart + 1 && $fractionalPart > 0) {
                                    $glowClass = 'text-warning-half'; // Half-glowing cup
                                } else {
                                    $glowClass = ''; // No glow
                                }
                            @endphp
                            <i class="fas fa-coffee fa-1x coffee-icon {{ $glowClass }}"></i>
                        @endfor
                        <span>({{ $totalRatings }})</span> <!-- Display the total number of ratings -->
                    </div>                    
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-header d-flex justify-content-end align-items-center" style="font-size: 15px;">
                    <form action="{{ route('cart.addToCart') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <img src="{{ asset('storage/' . $selectedItem->item_image) }}" alt="{{ $selectedItem->item_name }}" class="img-fluid">
                            </div>                          
                            <div class="col-md-8">
                                <!-- Display item details -->
                                <div class="row mb-3">
                                    <label for="item_name" class="col-md-4 col-form-label text-md-end">{{ __('Item Name:') }}</label>
                                    <div class="col-md-8">
                                        {{ $selectedItem->item_name }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="item_price" class="col-md-4 col-form-label text-md-end">{{ __('Item Price:') }}</label>
                                    <div class="col-md-8">
                                        {{ $selectedItem->item_price }}
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="expiry_date" class="col-md-4 col-form-label text-md-end">{{ __('Expiry Date:') }}</label>
                                    <div class="col-md-8">
                                        {{ $selectedItem->expiry_date }}
                                    </div>
                                </div>
                                <!-- Add to cart section -->
                                <div class="row mb-3">
                                    <!-- Check if item is in stock -->
                                    @if($selectedItem->item_stock > 0)
                                        <label for="quantity" class="col-md-4 col-form-label text-md-end">{{ __('Quantity') }}</label>
                                        <div class="col-md-4">
                                            <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="{{ $selectedItem->item_stock }}">
                                            Kilo/s
                                        </div>
                                        <div class="col-md-4">
                                            <label for="item_stock" class="col-form-label">{{ __('Item Stock:') }}</label>
                                            {{ $selectedItem->item_stock }}
                                        </div>
                                        <input type="hidden" name="purchase_id" value="{{ $selectedItem->id }}">
                                        <input type="hidden" name="item_image" value="{{ $selectedItem->item_image }}">
                                        <input type="hidden" name="item_name" value="{{ $selectedItem->item_name }}">
                                        <input type="hidden" name="item_stock" value="{{ $selectedItem->item_stock }}">
                                        <input type="hidden" name="item_price" value="{{ $selectedItem->item_price }}">
                                        <input type="hidden" name="expiry_date" value="{{ $selectedItem->expiry_date }}">
                                        <div class="col-md-8 offset-md-4">
                                            @auth
                                                @if(auth()->user()->email != 'beantobrew24@gmail.com')
                                                    <button type="submit" class="btn btn-primary">
                                                        {{ __('Add To Cart') }}
                                                    </button>
                                                @endif
                                            @endauth      
                                        </div>
                                    @else
                                        <div class="col-md-12">
                                            <p class="text-danger">Out of stock</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h5>Description</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-12">
                        {{ $selectedItem->item_description }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h5>Comments</h5>
                </div>
                <div class="card-body comments-container">
                    @if($comments->isNotEmpty())
                        @foreach($comments as $comment)
                            <div class="col-md-12">
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <!-- Check if the user relationship exists -->
                                        {{ $comment->user->name }}
                                    </div>
                                    <div class="card-body">
                                        <!-- Display the comment -->
                                        {{ $comment->comment }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        {{-- Pagination --}}
                        <div class="d-flex justify-content-center">
                            {{ $comments->appends(['id' => $selectedItem->id])->links() }}
                        </div>
                    @else
                        <p>No comments available for this item.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
