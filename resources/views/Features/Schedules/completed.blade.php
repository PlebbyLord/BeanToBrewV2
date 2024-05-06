@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="card mx-auto" style="max-width: 900px;">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span style="font-size: 18px; font-weight: bold;">Completed Schedule History</span>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6 mb-2 mx-auto text-center">
                            <a href="{{ route('completed1') }}" class="btn btn-primary btn-lg btn-block" style="width: 250px; height: 50px;">Farm 1 Schedules</a>
                        </div>
                        <div class="col-md-6 mb-2 mx-auto text-center">
                            <a href="{{ route('completed2') }}" class="btn btn-primary btn-lg btn-block" style="width: 250px; height: 50px;">Farm 2 Schedules</a>
                        </div>
                        <div class="col-md-6 mb-2 mx-auto text-center">
                            <a href="{{ route('completed3') }}" class="btn btn-primary btn-lg btn-block" style="width: 250px; height: 50px;">Farm 3 Schedules</a>
                        </div>
                        <div class="col-md-6 mb-2 mx-auto text-center">
                            <a href="{{ route('completed3') }}" class="btn btn-primary btn-lg btn-block" style="width: 250px; height: 50px;">Farm 4 Schedules</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection