@extends('layouts.app')
@section('content')
<style>
    .btn-action {
        width: 300%;
    }
</style>
@if(session('error'))
<div style="color: red; display: flex; justify-content: center;">
    {{ session('error') }}
</div>
@endif
@php
    $hasItemsToCheckout = $carts->where('user_id', auth()->id())->where('checkout_status', 1)->isNotEmpty();
@endphp
@if (!$hasItemsToCheckout && auth()->check())
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 mb-4">
                <div class="card-body text-center">
                    <h3>Your cart is currently empty. Nothing to checkout</h3>
                </div>
            </div>
        </div>
    </div>
@else
    <form method="post" action="{{ route('place.order') }}" enctype="multipart/form-data">
        @csrf
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10 mb-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center" style="font-size: 15px;">
                            <span style="width: 1%;"></span>
                            <span style="width: 25%;">{{ __('Product') }}</span>
                            <span style="width: 17%;">{{ __('Unit Price') }}</span>
                            <span style="width: 10%;">{{ __('Quantity') }}</span>
                            <span style="width: 16%;">{{ __('Item Subtotal') }}</span>
                        </div>
                    </div>
                </div>

                @php
                    $totalPricesSum = 0;
                @endphp

                @foreach ($carts->where('user_id', auth()->id())->where('checkout_status', 1)->groupBy('purchase.user') as $companyName => $companyCarts)
                    <div class="col-md-10 mb-0">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between" style="font-size: 15px;">
                                Bean to Brew Coffee Bean Shop
                            </div>
                            <div class="card-body">
                                @foreach ($companyCarts as $cart)
                                    <div class="row mb-3 align-items-center">
                                        <div class="col-md-1">
                                            <img src="{{ asset('storage/' . $cart->item_image) }}" alt="{{ $cart->item_name }}" class="img-fluid" style="max-width: 75px; max-height: 75px; margin-left: 10px;">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="item_name" style="margin-left: 30px;" class="col-form-label">{{ $cart->item_name }}</label>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="item_price" style="margin-left: 65px;" class="col-form-label">₱{{ $cart->item_price }}</label>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="quantity" style="margin-left: 165px;" class="col-form-label">{{ $cart->quantity }}</label>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="total_price" style="margin-left: 165px;" class="col-form-label total-price">₱{{ $cart->item_price * $cart->quantity }}</label>
                                        </div>
                                        @php
                                            $totalPricesSum += $cart->item_price * $cart->quantity;
                                        @endphp
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-md-10 mb-4">
                    <div class="card text-gray bg-light mb-0" style="max-width: 100%; min-height: 75px;">
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-center" style="margin: 2rem auto auto auto;">
                                    <div class="mb-2 row align-items-center">
                                        <div class="col-md-3">
                                            <label for="name">Full Name</label>
                                            <input type="text" name="name" class="form-control" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="number">Mobile Number</label>
                                            <input type="number" name="number" pattern="(\+63|0)\d{3}[-\s]?\d{7}"  class="form-control" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="address">Address</label>
                                            <input type="text" name="address" class="form-control" required>
                                        </div>
                                        <div class="col-md-12 mt-2 d-flex justify-content-center">
                                            <span class="badge badge-secondary text-dark" style="margin-right: 20px; font-size: 15px;">Payment Option:</span>
                                            <select class="form-select" name="payment_option[]" aria-label="Default select example" style="width: 100px; height: 3rem;">
                                                <option value="1">COD</option>
                                                <option value="2">GCash</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-10 mb-4">
                    <div class="card text-black bg-light mb-0" style="max-width: 72rem; min-height: 75px;">
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-end" style="margin: 2rem auto auto auto;">
                                    <span class="badge badge-secondary text-dark" style="margin-right: 20px; font-size: 15px;">Shipping Option:</span>
                                    <select class="form-select shipping-option" name="shipping_option[]" aria-label="Default select example" style="width: 170px;">
                                        <option selected>Select Shipping</option>
                                        <option value="1">Standard Shipping</option>
                                        <option value="2">Express Shipping</option>
                                    </select>
                                    <span class="badge badge-secondary text-dark shipping-fee-badge" id="shippingFeeBadge_" style="margin-right: 70px; font-size: 13px;">Shipping Fee: ₱0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-10 mb-4">
                    <div class="card text-gray bg-light mb-0" style="max-width: 72rem; min-height: 50px;">
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-12">
                                <div class="d-flex flex-column" style="margin: 1rem auto auto auto;">
                                    <div class="d-flex">
                                        <span class="badge badge-secondary text-dark" id="merchandiseSubtotalLabel" style="margin-left: 650px; font-size: 13px;">Merchandise Subtotal:</span>
                                        <span class="badge badge-secondary text-dark" id="merchandiseSubtotalBadge" style="margin-left: 20px; font-size: 13px;">₱{{ number_format($totalPricesSum, 2) }}</span>
                                    </div>
                                    <div class="d-flex">
                                        <span class="badge badge-secondary text-dark" id="shippingTotalLabel" style="margin-left: 650px; font-size: 13px;">Shipping Total:</span>
                                        <span class="badge badge-secondary text-dark order-total-money-badge" id="orderTotalMoneyBadge" style="margin-left: 63px; font-size: 13px;">₱0.00</span>
                                    </div>
                                    <div class="d-flex">
                                        <span class="badge badge-secondary text-dark" id="totalPaymentLabel" style="margin-left: 650px; margin-top: 5px; font-size: 13px;">Total Payment:</span>
                                        <span class="badge badge-secondary text-dark total-payment-badge" id="totalPaymentBadge" style="margin-left: 60px; font-size: 17px;">₱0.00</span>
                                    </div>
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-primary" style="margin-left: 800px;">Place Order</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function () {
            // Handle form submission
            $('form').submit(function (event) {
                // Check if a valid shipping option is selected
                var selectedShippingOption = $('.shipping-option').val();
    
                if (selectedShippingOption === 'Select Shipping') {
                    // Display an alert (you can customize this part)
                    alert('Please select a valid shipping option.');
                    // Prevent form submission
                    event.preventDefault();
                }
            });
    
            // Handle change event for each shipping option
            $('.shipping-option').on('change', function () {
                var selectedValue = $(this).val();
                var shippingFeeBadge = $(this).closest('.card').find('.shipping-fee-badge');
    
                if (selectedValue === '1') {
                    shippingFeeBadge.text('Shipping Fee: ₱100.00');
                } else if (selectedValue === '2') {
                    shippingFeeBadge.text('Shipping Fee: ₱150.00');
                }
                // Update the total shipping cost based on the selected option
                updateTotalShippingCost();
            });
    
            // Function to update the total shipping cost
            function updateTotalShippingCost() {
                // Initialize total shipping cost
                var totalShippingCost = 0.00;
    
                // Loop through each shipping option and update the total shipping cost
                $('.shipping-option').each(function () {
                    var selectedValue = $(this).val();
                    if (selectedValue === '1') {
                        totalShippingCost += 100.00;
                    } else if (selectedValue === '2') {
                        totalShippingCost += 150.00;
                    }
                });
    
                // Update the total shipping cost badge
                $('.order-total-money-badge').text('₱' + totalShippingCost.toFixed(2));
    
                // Update the total payment based on merchandise subtotal and shipping total
                updateTotalPayment();
            }
    
            // Function to update the total payment
            function updateTotalPayment() {
                var merchandiseSubtotal = @json($totalPricesSum);
                var shippingTotal = parseFloat($('.order-total-money-badge').text().replace('₱', ''));
                var totalPayment = merchandiseSubtotal + shippingTotal;
    
                // Update the total payment badge
                $('.total-payment-badge').text('₱' + totalPayment.toFixed(2));
            }
        });
    </script>    
@endif
@endsection
