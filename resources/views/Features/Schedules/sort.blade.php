@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <div class="card mx-auto" style="max-width: 600px;">
        <div class="card-header">
            <div class="row">
                <div class="col-2">
                    <a href="{{ route('features.schedule') }}" class="btn btn-sm btn-primary btn-lg px-4" style="font-size: 15px;">Back</a>
                </div>
                <div class="col-9 text-center">
                    <h5>Sorting Schedule</h5>
                </div>
            </div>
        </div> 
        <div class="card-body">
            <form method="post" action="{{ route('pesticide-schedule') }}">
                @csrf
                <div class="form-group">
                    <label for="coffeeType">Select Coffee Species:</label>
                    <select class="form-control" id="coffeeType" name="coffeeType">
                        <option value="arabica">Arabica</option>
                        <option value="excelsa">Excelsa</option>
                        <option value="liberica">Liberica</option>
                        <option value="robusta">Robusta</option>
                    </select>
                    @error('coffeeType')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="batchNumber">Enter Batch Number:</label>
                    <input type="number" class="form-control" id="batchNumber" name="batchNumber" placeholder="Enter batch number...">

                    @error('batchNumber')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="calendar">Select Date:</label>
                    <input type="date" class="form-control" id="calendar" name="calendar">
                    @error('calendar')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
</div>

@endsection
