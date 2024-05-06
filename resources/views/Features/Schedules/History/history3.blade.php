@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Farm 3 History</h5>
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
                                @elseif($schedule->progress_status == 0 && $schedule->Date_Set < now()) 
                                    Today/Delayed
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
                                @else
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
                                        <!-- If Schedule_Type is not 'Harvesting', directly update progress_status -->
                                        <form action="{{ route('updateProgress', $schedule->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="kilos_harvested" value="0"> <!-- No need for input, set a dummy value -->
                                            <button type="submit" class="btn btn-primary">Mark as Completed</button>
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
<!-- Ensure Bootstrap JS script is loaded -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<!-- Your script to close modal after form submission -->
<script>
    $(document).ready(function() {
        $('form').on('submit', function() {
            $(this).closest('.modal').modal('hide');
        });
    });
</script>
@endsection
