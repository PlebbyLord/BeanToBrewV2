@extends('layouts.app')

@section('content')

<div class="container mt-1">
    <div class="card">
            <a href="{{ route('features.pending') }}" class="btn btn-sm btn-primary btn-lg px-4" style="font-size: 15px;">Pending Deliveries</a>
    </div>
</div>

<div class="container mt-4">
    @foreach($orders->groupBy(function($order) { return $order->created_at->toDateString(); }) as $date => $ordersByDate)
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Sales - {{ $date }}</h5>
        </div> 
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Cart ID</th>
                            <th>Item Image</th>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Number</th>
                            <th>Shipping Option</th>
                            <th>Payment Option</th>
                            <th>Total Payment</th>
                            <th>Order Date</th>
                            <th>Delivery Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        @if ($order->cart->delivery_status == 3)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->cart_id }}</td>
                            <td><img src="{{ asset('storage/' . $order->cart->item_image) }}" alt="{{ $order->cart->item_image }}" style="max-width: 75px;"></td>
                            <td>{{ $order->cart->item_name }}</td>
                            <td>{{ $order->cart->quantity }}</td>
                            <td>{{ $order->name }}</td>
                            <td>{{ $order->address }}</td>
                            <td>{{ $order->number }}</td>
                            <td>{{ $order->shipping_option == 1 ? 'Standard' : 'Express' }}</td>
                            <td>{{ $order->payment_option == 1 ? 'COD' : 'GCash' }}</td>
                            <td>{{ $order->total_payment }}</td>
                            <td>{{ $order->created_at }}</td>
                            <td>Delivered</td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection
