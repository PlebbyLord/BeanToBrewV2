@extends('layouts.app')

@section('content')
<p class="Name text-center" style="font-size: 50px; text-decoration: underline; font-weight: bold; margin-left: 700px; color: rgb(0, 0, 0); margin-top: 50px; font-family: 'Monotype Corsiva', cursive; background-color: rgba(255, 255, 255, 0.8); padding: 10px; display: inline;">
    Onsite Sales
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
                <form id="exportForm" action="{{ route('export.onsite.sales') }}" method="GET" class="text-center">
                    <div class="form-group">
                        <label for="start_date">Start Date:</label>
                        <input type="date" id="start_date" name="start_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="end_date">End Date:</label>
                        <input type="date" id="end_date" name="end_date" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to export Onsite sales data?')">
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

    @foreach($cashiers->groupBy(function($cashier) { return $cashier->created_at->format('F Y'); }) as $month => $cashiersByMonth)
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Sales - {{ $month }}</h5>
            </div>
            <div class="card-body">
                @foreach($cashiersByMonth->groupBy(function($cashier) { return $cashier->created_at->format('Y-m-d'); }) as $day => $cashiersByDay)
                    <div class="card">
                        <div class="card-header">
                            <h6>{{ $day }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Item Name</th>
                                            <th>Item Image</th>
                                            <th>Item Price</th>
                                            <th>Quantity Sold</th>
                                            <th>Total Sales</th>
                                            <th>Sale Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $dailyTotal = 0; // Initialize daily total
                                        @endphp
                                        @foreach($cashiersByDay as $cashier)
                                            <tr>
                                                <td>{{ $cashier->id }}</td>
                                                <td>{{ $cashier->item_name }}</td>
                                                <td><img src="{{ asset('storage/' . $itemImages[$cashier->purchase_id]) }}" alt="Item Image" class="img-fluid" style="max-width: 70px; border: 1px solid black;"></td>
                                                <td>₱ {{ $cashier->item_price }}</td>
                                                <td>{{ $cashier->quantity }}</td>
                                                <td>₱ {{ $cashier->item_price * $cashier->quantity }}</td>
                                                <td>{{ $cashier->created_at->format('Y-m-d') }}</td>
                                            </tr>
                                            @php
                                                $dailyTotal += $cashier->item_price * $cashier->quantity; // Accumulate daily total
                                            @endphp
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
        window.location.href = '{{ route("features.onsitesales") }}';
    });
</script>

@endsection
