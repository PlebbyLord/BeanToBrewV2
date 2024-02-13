@extends('layouts.app')

@section('content')

@if(session('error'))
<div style="color: red; display: flex; justify-content: center;">
    {{ session('error') }}
</div>
@endif
@if ($carts->where('user_id', auth()->id())->where('checkout_status', 1)->isEmpty() && auth()->check())
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 mb-4">
                <div class="card-body text-center">
                    <h3>Your cart is currently empty or all items are checked out.</h3>
                </div>
            </div>
        </div>
@else
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center"
                        style="font-size: 15px;">
                        <span style="width: 30%;">{{ __('Product') }}</span>
                        <span style="width: 15%;">{{ __('Unit Price') }}</span>
                        <span style="width: 15%;">{{ __('Available Stock') }}</span>
                        <span style="width: 15%;">{{ __('Quantity') }}</span>
                        <span style="width: 15%;">{{ __('Item Subtotal') }}</span>
                        <span style="width: 10%;">{{ __('Action') }}</span>
                    </div>
                </div>
            </div>
            @php
                $totalPricesSum = 0;
            @endphp
            @foreach ($carts->where('user_id', auth()->id())->where('checkout_status', 1)->groupBy('purchase.user') as $companyName => $companyCarts)
                <div class="col-md-10 mb-3">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between"
                            style="font-size: 15px;">
                            Bean to Brew Coffee Bean Shop
                        </div>
                        <div class="card-body">
                            @foreach ($companyCarts as $cart)
                                <div class="row mb-3 align-items-center">
                                    <div class="col-md-2">
                                        <img src="{{ asset('storage/' . $cart->item_image) }}"
                                            alt="{{ $cart->item_name }}" class="img-fluid"
                                            style="max-width: 75px; max-height: 75px;">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="item_name" style="margin-left: -100px"
                                            class="col-form-label">{{ $cart->item_name }}</label>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="item_price" style="margin-left: -35px"
                                            class="col-form-label">₱{{ $cart->item_price }}</label>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="item_stock" style="margin-left: 70px"
                                            class="col-form-label">{{ $cart->purchase->item_stock }}</label>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="quantity" style="margin-left: 110px"
                                            class="col-form-label">{{ $cart->quantity }}</label>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="total_price" style="margin-left: 190px"
                                            class="col-form-label">₱{{ $cart->item_price * $cart->quantity }}</label>
                                    </div>
                                    <div class="col-md-2"> 
                                        <form action="{{ route('cart.destroy', $cart->id) }}" style="margin-left: 220px"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-danger btn-sm ml-2 btn-action"
                                                onclick="return confirm('Are you sure you want to delete this item?')">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-primary btn-sm ml-2 btn-action" style="width: 55px; margin-left: 220px;" data-bs-toggle="modal" data-bs-target="#updateQuantityModal_{{ $cart->id }}">
                                            Edit
                                        </button>
                                    </div>
                                    @php
                                        $totalPricesSum += $cart->item_price * $cart->quantity;
                                    @endphp
                                </div>
                                <!-- Your blade file code for the modal -->
                                <div class="modal fade"
                                    id="updateQuantityModal_{{ $cart->id }}"
                                    data-bs-backdrop="static"
                                    data-bs-keyboard="true" tabindex="-1"
                                    aria-labelledby="updateQuantityModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5"
                                                    id="updateQuantityModalLabel">Update
                                                    Quantity</h1>
                                                <button type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post"
                                                    action="{{ route('cart.updateQuantity') }}"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label for="quantity"
                                                            class="form-label">New
                                                            Quantity</label>
                                                        <input type="number"
                                                            class="form-control"
                                                            id="quantity"
                                                            name="quantity"
                                                            required>
                                                    </div>
                                                    <input type="hidden"
                                                        id="cartItemId"
                                                        name="cartItemId"
                                                        value="{{ $cart->id }}">
                                                    @if ($cart->purchase->item_stock < $cart->quantity)
                                                        <p class="text-danger">Warning:
                                                            Quantity exceeds
                                                            available stock.</p>
                                                    @endif
                                                    <button type="submit"
                                                        class="btn btn-primary"
                                                        {{ $cart->purchase->item_stock < $cart->quantity ? 'disabled' : '' }}>
                                                        Submit
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center"
                        style="font-size: 20px;">
                        <div class="d-flex">
                            <span class="badge badge-secondary text-dark" id="merchandiseSubtotalLabel" style="margin-left: 500px; font-size: 20px;">Total:</span>
                            <span class="badge badge-secondary text-dark" id="merchandiseSubtotalBadge" style="margin-left: 20px; font-size: 20px;">₱{{ number_format($totalPricesSum, 2) }}</span>
                        </div>
                        <form method="GET" action="{{ route('checkout') }}" enctype="multipart/form-data" onsubmit="return confirm('Are you sure you want to check out all your items?');">  
                            <button type="submit"
                                class="btn btn-primary short-btn"
                                style="margin-left: 55px;">
                                Checkout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
