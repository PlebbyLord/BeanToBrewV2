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
<style>
    #filterCard {
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
            <input type="checkbox" id="pesticide_spraying" name="schedule_type[]" value="PesticideSpraying">
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
                <h5>Farm 4 History</h5>
                <a href="{{ route('completed') }}" class="btn btn-primary">Completed Schedules</a>
                <a href="{{ route('calendar') }}" class="btn btn-primary">Calendar</a>
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
                                <th>Action</th>
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
                                    @if($schedule->progress_status == 0 && $schedule->Date_Set > now())
                                        Waiting
                                    @elseif($schedule->progress_status == 0 && $schedule->Date_Set == now()) 
                                        Today
                                    @elseif($schedule->progress_status == 0 && $schedule->Date_Set < now()) 
                                        Delayed
                                    @elseif($schedule->progress_status == 1)
                                        In Progress   
                                    @endif
                                </td>
                                <td>                                
                                    @if($schedule->progress_status == 0 && $schedule->Date_Set <= now())
                                        <form action="{{ route('schedStart', $schedule->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">Start Task</button>
                                        </form>
                                    @elseif($schedule->progress_status == 1)
                                        @if($schedule->Schedule_Type === 'Harvesting')
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#harvestModal{{ $schedule->id }}">
                                                Complete
                                            </button>
                                            <!-- Harvest Modal -->
                                                <div class="modal fade" id="harvestModal{{ $schedule->id }}" tabindex="-1" role="dialog" aria-labelledby="harvestModalLabel{{ $schedule->id }}" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="harvestModalLabel{{ $schedule->id }}">Complete Harvesting Schedule</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <!-- Harvesting completion form -->
                                                                <form action="{{ route('updateProgress', $schedule->id) }}" method="POST">
                                                                    @csrf
                                                                    <div class="form-group">
                                                                        <label for="kilosHarvested">Enter Kilos Harvested:</label>
                                                                        <input type="number" class="form-control" id="kilosHarvested" name="kilos_harvested" required>
                                                                    </div>
                                                                    <button type="submit" class="btn btn-primary">Complete</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        @else
                                            <!-- Directly mark as completed -->
                                            <form action="{{ route('updateProgress', $schedule->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="kilos_harvested" value="0"> <!-- Dummy value or hidden field if needed -->
                                                <button type="submit" class="btn btn-primary">Complete</button>
                                            </form>
                                        @endif
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
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            if (checkbox.id === "all" && checkbox.checked) {
                window.location.href = '{{ route("history4") }}';
            } else {
                const selectedTypes = Array.from(document.querySelectorAll('input[type="checkbox"]:checked')).map(checkbox => checkbox.value);
                const urlParams = new URLSearchParams(window.location.search);
                
                // Remove existing schedule type parameters
                urlParams.delete('schedule_type[]');

                // Append the selected schedule types
                selectedTypes.forEach(type => {
                    urlParams.append('schedule_type[]', type);
                });

                // Reload the page with updated URL parameters
                window.location.href = '?' + urlParams.toString();
            }
        });
    });
</script>
@endsection
