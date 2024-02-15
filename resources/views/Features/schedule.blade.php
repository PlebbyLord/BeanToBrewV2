@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="card mx-auto" style="max-width: 900px;">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span style="font-size: 18px; font-weight: bold;">Scheduling Options</span>
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
                            <a href="{{ route('prune') }}" class="btn btn-primary btn-lg btn-block" style="width: 250px; height: 50px;">Pruning</a>
                        </div>
                        <div class="col-md-6 mb-2 mx-auto text-center">
                            <a href="{{ route('dry') }}" class="btn btn-primary btn-lg btn-block" style="width: 250px; height: 50px;">Drying</a>
                        </div>
                        <div class="col-md-6 mb-2 mx-auto text-center">
                            <a href="{{ route('ferment') }}" class="btn btn-primary btn-lg btn-block" style="width: 250px; height: 50px;">Fermenting</a>
                        </div>
                        <div class="col-md-6 mb-2 mx-auto text-center">
                            <a href="{{ route('grind') }}" class="btn btn-primary btn-lg btn-block" style="width: 250px; height: 50px;">Grinding</a>
                        </div>
                        <div class="col-md-6 mb-2 mx-auto text-center">
                            <a href="{{ route('hull') }}" class="btn btn-primary btn-lg btn-block" style="width: 250px; height: 50px;">Hulling</a>
                        </div>
                        <div class="col-md-6 mb-2 mx-auto text-center">
                            <a href="{{ route('pack') }}" class="btn btn-primary btn-lg btn-block" style="width: 250px; height: 50px;">Packaging</a>
                        </div>
                        <div class="col-md-6 mb-2 mx-auto text-center">
                            <a href="{{ route('pulp') }}" class="btn btn-primary btn-lg btn-block" style="width: 250px; height: 50px;">Pulping</a>
                        </div>
                        <div class="col-md-6 mb-2 mx-auto text-center">
                            <a href="{{ route('roast') }}" class="btn btn-primary btn-lg btn-block" style="width: 250px; height: 50px;">Roasting</a>
                        </div>
                        <div class="col-md-6 mb-2 mx-auto text-center">
                            <a href="{{ route('sort') }}" class="btn btn-primary btn-lg btn-block" style="width: 250px; height: 50px;">Sorting</a>
                        </div>
                        <div class="col-md-6 mb-2 mx-auto text-center">
                            <a href="{{ route('history') }}" class="btn btn-primary btn-lg btn-block" style="width: 250px; height: 50px;">History</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
