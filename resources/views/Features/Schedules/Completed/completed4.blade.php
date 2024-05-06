@extends('layouts.app')

@section('content')

<style>
    .completed {
    color: green;
}
</style>
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h5>Completed Schedules Farm 4</h5>
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
                                @if($schedule->progress_status == 2)
                                    <span class="completed">Completed</span>
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