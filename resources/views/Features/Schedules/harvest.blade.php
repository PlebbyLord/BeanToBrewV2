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
                    <h5>Set A Schedule</h5>
                </div>
            </div>
        </div> 
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
            @endif
            <form method="post" action="{{ route('save-schedule') }}">
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
                    <label for="location">Select Location:</label>
                    <select class="form-control" id="location" name="location">
                        <option value="Farm 1">Farm 1</option>
                        <option value="Farm 2">Farm 2</option>
                        <option value="Farm 3">Farm 3</option>
                        <option value="Farm 4">Farm 4</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="calendar"><strong>Select Date:</strong></label>
                    <div id="calendar"></div>
                    <input type="hidden" id="selectedDate" name="calendar">
                </div>
                <div id="bestHarvestYear" class="text-center" style="font-weight: bold; font-size: large;">
                </div>                              
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
                $('#bestHarvestYear').text('Possible Year for Harvest: ' + bestYear);
            }
        });
    });

    // Function to calculate the best year for harvest using linear regression
    function calculateBestHarvestYear(selectedDate) {
        var plantingYear = selectedDate.year();
        var bestYear = plantingYear + 3; // Adjust this based on your algorithm
        return bestYear;
    }
</script>


@endsection
