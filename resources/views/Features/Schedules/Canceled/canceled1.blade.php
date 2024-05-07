@extends('layouts.app')

@section('content')

@if(session('error'))
<div style="background-color: black; color: red; display: flex; justify-content: center; padding: 20px; font-size: 20px;">
    {{ session('error') }}
</div>
@endif

@if(session('success'))
<div style="background-color: black; color: green; display: flex; justify-content: center; padding: 20px; font-size: 20px;">
    {{ session('success') }}
</div>
@endif

<div id="filterCard">
    <form id="filterForm">
        <div>
            <label for="schedule_type">Filter by Schedule Type:</label><br>
            <input type="checkbox" id="all" name="schedule_type[]" value="all">
            <label for="all">All</label><br>
            <!-- List of schedule types -->
            <input type="checkbox" id="planting" name="schedule_type[]" value="Planting">
            <label for="planting">Planting</label><br>
            <input type="checkbox" id="watering" name="schedule_type[]" value="Watering">
            <label for="watering">Watering</label><br>
            <input type="checkbox" id="monthly_checks" name="schedule_type[]" value="MonthlyChecks">
            <label for="monthly_checks">Monthly Checks</label><br>
            <input type="checkbox" id="pesticide_spraying" name="schedule_type[]" value="Pesticide Spraying">
            <label for="pesticide_spraying">Pesticide Spraying</label><br>
            <input type="checkbox" id="harvesting" name="schedule_type[]" value="Harvesting">
            <label for="harvesting">Harvesting</label><br>
            <input type="checkbox" id="pulping" name="schedule_type[]" value="Pulping">
            <label for="pulping">Pulping</label><br>
            <input type="checkbox" id="fermenting" name="schedule_type[]" value="Fermenting">
            <label for="fermenting">Fermenting</label><br>
            <input type="checkbox" id="drying" name="schedule_type[]" value="Drying">
            <label for="drying">Drying</label><br>
            <input type="checkbox" id="hulling" name="schedule_type[]" value="Hulling">
            <label for="hulling">Hulling</label><br>
            <input type="checkbox" id="sorting" name="schedule_type[]" value="Sorting">
            <label for="sorting">Sorting</label><br>
            <input type="checkbox" id="pruning" name="schedule_type[]" value="Pruning">
            <label for="pruning">Pruning</label><br>
            <input type="checkbox" id="packaging" name="schedule_type[]" value="Packaging">
            <label for="packaging">Packaging</label><br>
            <input type="checkbox" id="roasting" name="schedule_type[]" value="Roasting">
            <label for="roasting">Roasting</label><br>
            <input type="checkbox" id="grinding" name="schedule_type[]" value="Grinding">
            <label for="grinding">Grinding</label><br>
            <!-- End of list -->
        </div>
    </form>
</div>

<div id="mainContent" style="margin-left: 240px; /* Adjust margin-left to accommodate the filter card width */">
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Canceled Schedules Farm 1</h5>
                <a href="{{ route('history1') }}" class="btn btn-primary">View History</a>
            </div>               
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Coffee Type</th>
                                <th>Coffee Age</th>
                                <th>Farm Location</th>
                                <th>Batch Number</th>
                                <th>Date Set</th>
                                <th>Schedule Type</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedules as $schedule)
                            <tr>
                                <td>{{ $schedule->coffee_species }}</td>
                                <td>{{ $schedule->age }}</td>
                                <td>{{ $schedule->location }}</td>
                                <td>{{ $schedule->batch_number }}</td>
                                <td>{{ $schedule->Date_Set }}</td>
                                <td>{{ $schedule->Schedule_Type }}</td>
                                <td>
                                    @if($schedule->progress_status == 3)
                                        <span class="canceled">Canceled</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Function to handle filter changes and reset pagination to page 1
    function handleFilterChange() {
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        const selectedTypes = Array.from(checkboxes)
            .filter(checkbox => checkbox.checked && checkbox.value !== "all")
            .map(checkbox => checkbox.value);

        const urlParams = new URLSearchParams(window.location.search);

        // Reset pagination to page 1
        urlParams.set('page', '1');

        // Remove existing schedule type parameters
        urlParams.delete('schedule_type[]');

        // Append the selected schedule types to URL parameters
        selectedTypes.forEach(type => {
            urlParams.append('schedule_type[]', type);
        });

        // Construct the new URL with filters
        const baseUrl = window.location.href.split('?')[0];
        const newUrl = baseUrl + '?' + urlParams.toString();

        // Navigate to the new URL
        window.location.href = newUrl;
    }

    // Attach change event listener to filter checkboxes
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', handleFilterChange);
    });

    // Function to handle pagination clicks while preserving filters
    function handlePaginationClick(pageNumber) {
        const urlParams = new URLSearchParams(window.location.search);

        // Update the 'page' parameter with the clicked page number
        urlParams.set('page', pageNumber);

        // Construct the new URL with updated pagination
        const baseUrl = window.location.href.split('?')[0];
        const newUrl = baseUrl + '?' + urlParams.toString();

        // Navigate to the new URL
        window.location.href = newUrl;
    }

    // Attach click event listener to pagination links
    const paginationLinks = document.querySelectorAll('.pagination a');
    paginationLinks.forEach(link => {
        link.addEventListener('click', event => {
            event.preventDefault();
            const pageNumber = link.getAttribute('href').split('page=')[1];
            handlePaginationClick(pageNumber);
        });
    });
</script>
@endsection
