@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h5>History</h5>
        </div> 
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Coffee Type</th>
                            <th>Batch Number</th>
                            <th>Date Set</th>
                            <th>Harvest Date</th>
                            <th>Schedule Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule->coffee_species }}</td>
                            <td>{{ $schedule->batch_number }}</td>
                            <td>{{ $schedule->Date_Set }}</td>
                            <td>{{ $schedule->Harvest_Date }}</td>
                            <td>{{ $schedule->Schedule_Type }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection