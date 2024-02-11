@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="card mx-auto" style="max-width: 900px;">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span style="font-size: 18px; font-weight: bold;">Scheduling Options</span>
            <a href="{{ route('history') }}" class="btn btn-sm btn-primary btn-lg px-4" style="font-size: 15px;">History</a>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6 mb-2 mx-auto text-center">
                            <a href="{{ route('harvest') }}" class="btn btn-primary btn-lg btn-block" style="width: 250px; height: 50px;">Planting/Harvesting</a>
                        </div>
                        <div class="col-md-6 mb-2 mx-auto text-center">
                            <a href="{{ route('pesticide') }}" class="btn btn-primary btn-lg btn-block" style="width: 250px; height: 50px;">Pesticide Spraying</a>
                        </div>
                        <div class="col-md-6 mb-2 mx-auto text-center">
                            <a href="{{ route('water') }}" class="btn btn-primary btn-lg btn-block" style="width: 250px; height: 50px;">Watering</a>
                        </div>
                        <div class="col-md-6 mb-2 mx-auto text-center">
                            <a href="{{ route('prune') }}" class="btn btn-primary btn-lg btn-block" style="width: 250px; height: 50px;">Pruning</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
