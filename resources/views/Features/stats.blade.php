@extends('layouts.app')

@section('content')

<div class="card mx-auto" style="max-width: 600px;">
    <div class="card-header">
        Statistics
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Year</th>
                    <th>Sales</th>
                    <th>Predicted Sales for Next Years</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($salesData as $index => $data)
                <tr>
                    <td>{{ $data['Year'] }}</td>
                    <td>&#8369; {{ number_format($data['Sales'], 2) }}</td> <!-- Display with 2 decimals and Philippine Peso sign -->
                    <td>
                        @if($index >= 3) <!-- Only show the projected sales after the first 3 years -->
                            &#8369; {{ number_format($data['Projected_Sales'], 2) }} <!-- Display with 2 decimals and Philippine Peso sign -->
                        @endif
                    </td>
                </tr>
                @endforeach            
            </tbody>
        </table>
    </div>
</div>

@endsection
