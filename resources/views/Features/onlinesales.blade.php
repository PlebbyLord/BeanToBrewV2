@extends('layouts.app')

@section('content')
<p class="Name text-center" style="font-size: 50px; text-decoration: underline; font-weight: bold; margin-left: 700px; color: rgb(0, 0, 0); margin-top: 50px; font-family: 'Monotype Corsiva', cursive; background-color: rgba(255, 255, 255, 0.8); padding: 10px; display: inline;">
    Online Sales
</p>   
<style>
    #filterCard2 {
        position: fixed;
        top: 66px;
        left: 0px; /* Position on the left side */
        width: 150px;
        padding: 20px;
        background-color: #f4d693; /* Set background color to white */
        border: 1px solid rgba(0, 0, 0, 0.1); /* Add a border for visibility */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2); /* Add a subtle shadow */
        z-index: 1000; /* Ensure it appears above other content */
    }
    .container {
        margin-left: 150px; /* Adjust margin-left to move the content to the right */
    }
    .card-columns {
        margin-top: 20px; /* Adjust top margin for spacing */
    }
    .card-footer {
        background-color: #f8f9fa;
        text-align: right;
        font-weight: bold;
    }
    .form-group {
        margin-bottom: 10px; /* Adjust margin bottom for spacing between form elements */
    }
    .form-control {
        width: 600px; /* Adjust width of form controls */
    }
    .btn-primary {
        padding: 5px 10px; /* Adjust padding of the button */
        font-size: 14px; /* Adjust font size of the button text */
    }
</style>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            @if (auth()->user()->role == 1)
                <form id="exportForm" action="{{ route('export.online.sales') }}" method="GET" class="text-center">
                    <div class="form-group">
                        <label for="start_date">Start Date:</label>
                        <input type="date" id="start_date" name="start_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="end_date">End Date:</label>
                        <input type="date" id="end_date" name="end_date" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to export Online sales data?')">
                        Export Excel
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<div class="container mt-4">
    <!-- Month Filter Form -->
    <div id="filterCard2">
        <form id="filterForm2">
            <div>
                <label for="month">Filter by Month:</label><br>
                <input type="checkbox" id="all" name="month[]" value="all">
                <label for="all">All</label><br>
                @foreach([
                    'January', 'February', 'March', 'April', 'May', 'June',
                    'July', 'August', 'September', 'October', 'November', 'December'
                ] as $month)
                    <input type="checkbox" id="{{ strtolower($month) }}" name="month[]" value="{{ $month }}">
                    <label for="{{ strtolower($month) }}">{{ $month }}</label><br>
                @endforeach
            </div>
        </form>
    </div>

    <hr>

    @foreach($orders->groupBy(function($order) { return $order->created_at->format('F Y'); }) as $month => $ordersByMonth)
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Sales - {{ $month }}</h5>
            </div>
            <div class="card-body">
                @foreach($ordersByMonth->groupBy(function($order) { return $order->created_at->format('Y-m-d'); }) as $day => $ordersByDay)
                    <div class="card">
                        <div class="card-header">
                            <h6>{{ $day }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
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
                                        @php
                                            $dailyTotal = 0; // Initialize daily total
                                        @endphp
                                        @foreach($ordersByDay as $order)
                                            @if ($order->cart->delivery_status == 3)
                                                <tr>
                                                    <td>{{ $order->id }}</td>
                                                    <td><img src="{{ asset('storage/' . $order->cart->item_image) }}" alt="{{ $order->cart->item_image }}" style="max-width: 75px; border: 1px solid black;"></td>
                                                    <td>{{ $order->cart->item_name }}</td>
                                                    <td>{{ $order->cart->quantity }}</td>
                                                    <td>{{ $order->name }}</td>
                                                    <td>{{ $order->address }}</td>
                                                    <td>{{ $order->number }}</td>
                                                    <td>{{ $order->shipping_option == 1 ? 'Standard' : 'Express' }}</td>
                                                    <td>{{ $order->payment_option == 1 ? 'COD' : 'GCash' }}</td>
                                                    <td>₱{{ $order->total_payment }}</td>
                                                    <td>{{ $order->created_at }}</td>
                                                    <td>Delivered</td>
                                                </tr>
                                                @php
                                                    $dailyTotal += $order->total_payment; // Accumulate daily total payment
                                                @endphp
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            Total Sales for {{ $day }}: ₱ {{ $dailyTotal }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

<script>
    // JavaScript for handling month filtering
    const checkboxes = document.querySelectorAll('input[name="month[]"]');
    const allCheckbox = document.getElementById('all');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const selectedMonths = Array.from(document.querySelectorAll('input[name="month[]"]:checked')).map(checkbox => checkbox.value);
            const urlParams = new URLSearchParams(window.location.search);

            // Remove existing month parameters
            urlParams.delete('month[]');

            // Append the selected months
            selectedMonths.forEach(month => {
                urlParams.append('month[]', month);
            });

            // Reload the page with updated URL parameters
            window.location.href = '?' + urlParams.toString();
        });
    });

    // Handle "All" checkbox
    allCheckbox.addEventListener('change', () => {
        if (allCheckbox.checked) {
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
        } else {
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
        }

        // Reload the page with no month parameters when "All" is checked
        window.location.href = '{{ route("features.onlinesales") }}';
    });
</script>

@endsection
