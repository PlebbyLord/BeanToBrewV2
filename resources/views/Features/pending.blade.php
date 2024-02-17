@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h5>Pending Deliveries</h5>
        </div> 
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
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
                        @if($order->cart->delivery_status != 3)
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
                            <td>
                                @if($order->cart->delivery_status == 1)
                                    <form id="deliverNowForm-{{ $order->cart_id }}" method="POST" action="{{ route('deliver.send', ['cartId' => $order->cart_id]) }}" onsubmit="return validateDeliveryForm({{ $order->cart_id }})">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm" id="submitBtn-{{ $order->cart_id }}">Deliver Now</button>
                                        <div id="loadingSpinner-{{ $order->cart_id }}" class="d-none text-center mt-2">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </form>
                                @elseif($order->cart->delivery_status == 2)
                                    Out For Delivery
                                @endif
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    var isSubmitting = {};

    function validateDeliveryForm(cartId) {
        if (isSubmitting[cartId]) {
            return false; // Ignore additional clicks while submitting
        }

        isSubmitting[cartId] = true; // Set to true to indicate the form is being submitted
        var submitBtn = document.getElementById('submitBtn-' + cartId);
        var loadingSpinner = document.getElementById('loadingSpinner-' + cartId);

        submitBtn.setAttribute('disabled', 'disabled'); // Disable the button to prevent multiple submissions
        loadingSpinner.classList.remove('d-none'); // Show the loading spinner

        // You can optionally remove the disabled attribute and hide the spinner after processing the submission
        // This would typically be done in the success or error callback of your form submission
        // For simplicity, I'm just simulating a 5-second delay here
        setTimeout(function () {
            submitBtn.removeAttribute('disabled');
            loadingSpinner.classList.add('d-none');
            isSubmitting[cartId] = false; // Reset the flag when submission is complete
        }, 9000); // Adjust the time as needed (5 seconds in this example)

        return true; // Allow the form to be submitted
    }
</script>

@endsection
