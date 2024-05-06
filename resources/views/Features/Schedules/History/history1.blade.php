@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Farm 1 History</h5>
            <a href="{{ route('completed') }}" class="btn btn-primary">Completed Schedules</a>
            <a href="{{ route('calendar') }}" class="btn btn-primary">Calendar</a>
        </div>
        <!-- Schedule Type Filter Card -->
        <div class="card-body">
            <div id="filterCard">
                <form id="filterForm">
                    <div>
                        <label for="schedule_type">Filter by Schedule Type:</label><br>
                        <input type="checkbox" id="all" name="schedule_type[]" value="all">
                        <label for="all">All</label><br>
                        @foreach([
                            'Planting', 'Watering', 'MonthlyChecks', 'Pesticide Spraying', 'Harvesting',
                            'Pulping', 'Fermenting', 'Drying', 'Hulling', 'Sorting', 'Pruning', 'Packaging',
                            'Roasting', 'Grinding'
                        ] as $type)
                            <input type="checkbox" id="{{ strtolower(str_replace(' ', '', $type)) }}" name="schedule_type[]" value="{{ $type }}">
                            <label for="{{ strtolower(str_replace(' ', '', $type)) }}">{{ $type }}</label><br>
                        @endforeach
                    </div>
                </form>
            </div>
            <!-- Table for displaying filtered schedules -->
            <div class="table-responsive mt-4">
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

@endsection

@section('scripts')
<!-- CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<!-- Ensure jQuery and Bootstrap JS scripts are loaded -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<!-- JavaScript for handling schedule type filtering -->
<script>
    // Handle schedule type filtering
    const checkboxes = document.querySelectorAll('input[name="schedule_type[]"]');
    const allCheckbox = document.getElementById('all');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const selectedTypes = Array.from(document.querySelectorAll('input[name="schedule_type[]"]:checked')).map(checkbox => checkbox.value);
            const urlParams = new URLSearchParams(window.location.search);

            // Remove existing schedule_type parameters
            urlParams.delete('schedule_type[]');

            // Append the selected schedule types
            selectedTypes.forEach(type => {
                urlParams.append('schedule_type[]', type);
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

        // Reload the page with no schedule_type parameters when "All" is checked
        window.location.href = '{{ route("history1") }}';
    });
</script>
<script>
    $(document).ready(function() {
        $('form').on('submit', function() {
            $(this).closest('.modal').modal('hide');
        });
    });
</script>
@endsection
