@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="card mx-auto" style="max-width: 900px;">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span style="font-size: 18px; font-weight: bold;">Canceled Schedule History</span>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6 mb-2 mx-auto text-center">
                            <a href="{{ route('canceled1') }}" class="btn btn-primary btn-lg btn-block" style="width: 250px; height: 70px;">Canceled Farm 1 Schedules</a>
                        </div>
                        <div class="col-md-6 mb-2 mx-auto text-center">
                            <a href="{{ route('canceled2') }}" class="btn btn-primary btn-lg btn-block" style="width: 250px; height: 70px;">Canceled Farm 2 Schedules</a>
                        </div>
                        <div class="col-md-6 mb-2 mx-auto text-center">
                            <a href="{{ route('canceled3') }}" class="btn btn-primary btn-lg btn-block" style="width: 250px; height: 70px;">Canceled Farm 3 Schedules</a>
                        </div>
                        <div class="col-md-6 mb-2 mx-auto text-center">
                            <a href="{{ route('canceled4') }}" class="btn btn-primary btn-lg btn-block" style="width: 250px; height: 70px;">Canceled Farm 4 Schedules</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection