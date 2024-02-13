@extends('layouts.app')

@section('content')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bean to Brew</title>
</head>

<body>
    @foreach ($userItems->take(1) as $buy)
        <div class="col" style="margin-bottom: 20px;"> 
            <p class="Name text-center" style="font-size: 18px; text-decoration: underline;">Bean To Brew Shop</p>
            @include("components.cardcolumn", ['buy' => $buy])
        </div>
    @endforeach
</body>
</html>
@endsection
