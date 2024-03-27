@extends('layouts.app')

@section('content')

<div class="container mt-4">
    @foreach($cashiers->groupBy(function($cashier) { return $cashier->created_at->toDateString(); }) as $date => $cashiersByDate)
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Sales - {{ $date }}</h5>
        </div> 
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Cashier ID</th>
                            <th>Item Name</th>
                            <th>Item Image</th>
                            <th>Item Price</th>
                            <th>Quantity Sold</th>
                            <th>Total Sales</th>
                            <th>Sale Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cashiersByDate as $cashier)
                        <tr>
                            <td>{{ $cashier->id }}</td>
                            <td>{{ $cashier->item_name }}</td>
                            <td><img src="{{ asset('storage/' . $itemImages[$cashier->purchase_id]) }}" alt="Item Image" class="img-fluid" style="max-width: 70px;"></td>
                            <td>₱ {{ $cashier->item_price }}</td>
                            <td>{{ $cashier->quantity}}</td>
                            <td>₱ {{ $cashier->item_price * $cashier->quantity }}</td>
                            <td>{{ $cashier->created_at->format('Y-m-d') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection
