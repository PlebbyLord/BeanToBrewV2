@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-2">
                    <a href="{{ route('features.schedule') }}" class="btn btn-sm btn-primary btn-lg px-4" style="font-size: 15px;">Back</a>
                </div>
                <div class="col-9 text-center">
                    <h5>Planting Schedule</h5>
                </div>
            </div>
        </div> 
        <div class="card-body">
            <form method="post" action="{{ route('plant-schedule') }}">
                @csrf
                <div class="form-group">
                    <label for="coffeeType">Select Coffee Species:</label>
                    <select class="form-control" id="coffeeType" name="coffeeType">
                        <option value="arabica">Arabica</option>
                        <option value="excelsa">Excelsa</option>
                        <option value="liberica">Liberica</option>
                        <option value="robusta">Robusta</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="calendar"><strong>Select Date:</strong></label>
                    <div id="calendar"></div>
                    <input type="hidden" id="selectedDate" name="calendar">
                </div>
                <div id="bestHarvestYear"></div> <!-- Display the best harvest year here -->
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
</div>

<!-- FullCalendar CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />

<!-- jQuery and Moment.js -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<!-- FullCalendar JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>

<!-- Initialize FullCalendar -->
<script>
    $(document).ready(function () {
        // Initialize the calendar
        $('#calendar').fullCalendar({
            selectable: true,
            height: 'auto', // Adjust the height as needed
            select: function (start, end, jsEvent, view) {
                // Set the selected date in the hidden input field
                $('#selectedDate').val(start.format('YYYY-MM-DD'));

                // Calculate the best year for harvest using linear regression
                var bestYear = calculateBestHarvestYear(start);
                // Display the best year for harvest
                $('#bestHarvestYear').text('Best Year for Harvest: ' + bestYear);
            }
        });
    });

    // Function to calculate the best year for harvest using linear regression
    function calculateBestHarvestYear(selectedDate) {
        // Implement your linear regression algorithm here
        // For demonstration, let's assume the best year is 5 years after planting
        var plantingYear = selectedDate.year();
        var bestYear = plantingYear + 4; // Adjust this based on your algorithm
        return bestYear;
    }
</script>

@endsection
