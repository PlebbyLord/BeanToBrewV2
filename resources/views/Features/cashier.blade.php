@extends('layouts.app')

@section('content')
@if(session('error'))
<div style="color: red; display: flex; justify-content: center;">
    {{ session('error') }}
</div>
@endif
@if(session('success'))
<div style="color: green; display: flex; justify-content: center;">
    {{ session('success') }}
</div>
@endif
<style>
    #filterCard {
        position: fixed;
        top: 100px;
        left: 50px;
        width: 200px; /* Adjust width as needed */
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
</style>

<div id="filterCard">
    <form id="filterForm">
        <div>
            <label for="coffee_type">Filter by Coffee Type:</label><br>
            <input type="checkbox" id="all" name="coffee_type[]" value="all">
            <label for="all">All</label><br>
            <input type="checkbox" id="green_coffee" name="coffee_type[]" value="green">
            <label for="green_coffee">Green Coffee Beans</label><br>
            <input type="checkbox" id="roasted_coffee" name="coffee_type[]" value="roasted">
            <label for="roasted_coffee">Roasted Coffee Beans</label><br>
            <input type="checkbox" id="grinded_coffee" name="coffee_type[]" value="grinded">
            <label for="grinded_coffee">Grinded Coffee Beans</label><br>
        </div>
    </form>
</div>

<div id="mainContent" style="margin-left: 240px; /* Adjust margin-left to accommodate the filter card width */">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Available Items
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($purchases as $purchase)
                                @if($purchase->branch == auth()->user()->branch)
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/' . $purchase->item_image) }}" alt="{{ $purchase->item_name }}" style="max-width: 70px;" class="me-3">
                                            <div>
                                                <div>{{ $purchase->item_name }}</div>
                                                <div>Stock: {{ $purchase->item_stock }}</div>
                                                <div>Price /kilo: {{ $purchase->item_price }}</div>
                                            </div>
                                            <div class="ms-auto">
                                                <!-- Add button here -->
                                                <form action="{{ route('addToTempCash') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
                                                    <input type="hidden" name="item_name" value="{{ $purchase->item_name }}">
                                                    <input type="hidden" name="item_image" value="{{ asset('storage/' . $purchase->item_image) }}">
                                                    <input type="hidden" name="item_price" value="{{ $purchase->item_price }}">
                                                    <button type="submit" class="btn btn-primary">Add</button>
                                                </form>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>                
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Items for Checkout
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @php
                                $totalSale = 0;
                            @endphp
                            @foreach($tempCashes as $tempCash)
                                @php
                                    $itemSubtotal = $tempCash->item_price * $tempCash->quantity;
                                    $totalSale += $itemSubtotal;
                                @endphp
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $tempCash->item_image }}" alt="{{ $tempCash->item_name }}" style="max-width: 70px;" class="me-3">
                                        <div>
                                            <div>{{ $tempCash->item_name }}</div>
                                            <div>Price /kilo: {{ $tempCash->item_price }}</div>
                                            <div>Quantity: {{ $tempCash->quantity }}</div>
                                            <div>Item Subtotal: ₱{{ number_format($itemSubtotal, 2) }}</div>
                                        </div>
                                        <div class="ms-auto">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#quantityModal{{ $tempCash->id }}">Change Quantity</button>
                                            <form action="{{ route('cashier.remove') }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="temp_cash_id" value="{{ $tempCash->id }}">
                                                <button type="submit" class="btn btn-danger">Remove</button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                                <!-- Quantity Modal -->
                                <div class="modal fade" id="quantityModal{{ $tempCash->id }}" tabindex="-1" aria-labelledby="quantityModalLabel{{ $tempCash->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="quantityModalLabel{{ $tempCash->id }}">Change Quantity</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Your form to change quantity -->
                                                <form action="{{ route('cashier.changeQuantity') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="temp_cash_id" value="{{ $tempCash->id }}">
                                                    <label for="quantity">Quantity:</label>
                                                    <input type="number" id="quantity" name="quantity" value="{{ $tempCash->quantity }}">
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col">
                                <div class="text-start">
                                    <strong>Total Sale: ₱{{ number_format($totalSale, 2) }}</strong>
                                </div>
                            </div>
                            <div class="col">
                                <div class="text-end">
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#getChangeModal">
                                        Checkout
                                    </button>
            
                                    <!-- Modal -->
                                    <div class="modal fade" id="getChangeModal" tabindex="-1" aria-labelledby="getChangeModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="getChangeModalLabel">Checkout</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('cashier.checkout') }}" method="POST">
                                                        @csrf
                                                        <label for="changeAmount">Enter the amount paid by the buyer:</label>
                                                        <input type="number" id="changeAmount" name="change" class="form-control" required>
                                                        <!-- Pass the total sale to the controller -->
                                                        <input type="hidden" name="total_sale" value="{{ $totalSale }}">
                                                        <button type="submit" class="btn btn-primary mt-3">Checkout</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>                            
        </div>
    </div>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-EVSTQN3/azgB0/pWqTGzFdsQX5qX04q7BDk2Jl/bi5z9Jq1+VqpCJNbALVFpvpG1" crossorigin="anonymous"></script>

<script>
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            if (checkbox.id === "all" && checkbox.checked) {
                window.location.href = '{{ route("features.cashier") }}';
            } else {
                const selectedTypes = Array.from(document.querySelectorAll('input[type="checkbox"]:checked')).map(checkbox => checkbox.value);
                const urlParams = new URLSearchParams(window.location.search);
                
                // Remove existing coffee type parameters
                urlParams.delete('coffee_type[]');

                // Append the selected coffee types
                selectedTypes.forEach(type => {
                    urlParams.append('coffee_type[]', type);
                });

                // Reload the page with updated URL parameters
                window.location.href = '?' + urlParams.toString();
            }
        });
    });
</script>
@endsection
