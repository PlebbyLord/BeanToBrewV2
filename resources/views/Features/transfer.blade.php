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
                        Items in Other Branches
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($otherBranchItems as $item)
                                @if($item->transfer_status != 1)
                                    <li class="list-group-item">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/' . $item->item_image) }}" alt="{{ $item->item_name }}" style="max-width: 70px; border: 1px solid black;" class="me-3">
                                            <div>
                                                <div>{{ $item->item_name }}</div>
                                                <div>Stock: {{ $item->item_stock }}</div>
                                                <div>Price /kilo: {{ $item->item_price }}</div>
                                                <div>Branch: {{ $item->branch }}</div>
                                            </div>
                                            <div class="ms-auto">
                                                <form action="{{ route('addToTempInv') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="purchase_id" value="{{ $item->id }}">
                                                    <input type="hidden" name="item_name" value="{{ $item->item_name }}">
                                                    <input type="hidden" name="item_image" value="{{ asset('storage/' . $item->item_image) }}">
                                                    <input type="hidden" name="item_price" value="{{ $item->item_price }}">
                                                    <input type="hidden" name="branch" value="{{ $item->branch }}">
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
                        Items For Transfer
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($tempInvs as $tempInv)
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $tempInv->item_image }}" alt="{{ $tempInv->item_name }}" style="max-width: 70px; border: 1px solid black;" class="me-3">
                                        <div>
                                            <div>{{ $tempInv->item_name }}</div>
                                            <div>Quantity: {{ $tempInv->quantity }}</div>
                                        </div>
                                        <div class="ms-auto">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#quantityModal{{ $tempInv->id }}">Change Quantity</button>
                                            <form action="{{ route('transfer.remove') }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="temp_inv_id" value="{{ $tempInv->id }}">
                                                <button type="submit" class="btn btn-danger">Remove</button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                                <!-- Quantity Modal -->
                                <div class="modal fade" id="quantityModal{{ $tempInv->id }}" tabindex="-1" aria-labelledby="quantityModalLabel{{ $tempInv->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="quantityModalLabel{{ $tempInv->id }}">Change Quantity</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Your form to change quantity -->
                                                <form action="{{ route('transfer.changeQuantity') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="temp_inv_id" value="{{ $tempInv->id }}">
                                                    <label for="quantity">Quantity:</label>
                                                    <input type="number" id="quantity" name="quantity" value="{{ $tempInv->quantity }}">
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
                                <div class="text-end">
                                    <form action="{{ route('transfer.request') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success">Send Request</button>
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

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-EVSTQN3/azgB0/pWqTGzFdsQX5qX04q7BDk2Jl/bi5z9Jq1+VqpCJNbALVFpvpG1" crossorigin="anonymous"></script>

<script>
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            if (checkbox.id === "all" && checkbox.checked) {
                window.location.href = '{{ route("features.transfer") }}';
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
