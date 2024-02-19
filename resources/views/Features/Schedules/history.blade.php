@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>History</h5>
            <a href="{{ route('completed') }}" class="btn btn-primary">Completed Schedules</a>
        </div>               
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Coffee Type</th>
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
                            <td>{{ $schedule->batch_number }}</td>
                            <td>{{ $schedule->Date_Set }}</td>
                            <td>{{ $schedule->Schedule_Type }}</td>
                            <td>
                                @if($schedule->progress_status == 0)
                                    Waiting
                                @elseif($schedule->progress_status == 1)
                                    In Progress
                                @endif
                            </td>
                            <td>                                
                                @if($schedule->progress_status == 0)
                                <form action="{{ route('schedStart', $schedule->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Start Task</button>
                                </form>
                            @elseif($schedule->progress_status == 1)
                                <form action="{{ route('updateProgress', $schedule->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Complete</button>
                                </form>
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
