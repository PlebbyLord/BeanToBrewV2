<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Bean to Brew</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <style>
        /* Set background image and transparency */
        body {
            /* Background image URL */
            background-image: url('{{ asset("storage/images/background.png") }}');
            /* Background properties */
            background-size: cover; /* Cover the entire viewport */
            background-repeat: repeat; /* Prevent background image from repeating */
            background-position: center; /* Center the image */
            /* Background color with transparency */
            background-color: rgba(255, 255, 255, 0.0); /* 50% white transparency */
            /* Font family and size */
            font-family: 'Nunito', sans-serif;
            font-size: 16px;
            /* Padding for navbar */
            padding-top: 0px; /* Adjust based on navbar height */
            margin: 0; /* Remove default body margin */
            height: 100vh; /* Set body height to full viewport height */
            overflow: auto; /* Enable scrolling if content exceeds viewport */
        }
        /* Styling for .card-body */
        .card-body {
            background-color: rgba(23, 161, 189, 0.405);
            border: 2px solid black;
        }

        /* Styling for .card-header */
        .card-header {
            background-color: rgba(23, 161, 189, 0.405);
            border: 2px solid black;
        }
        .card-footer {
            background-color: rgba(23, 161, 189, 0.405);
            border: 2px solid black;
        }
        #filterCard {
            position: fixed;
            top: 10;
            left: 0;
            width: 200px; /* Adjust width as needed */
            padding: 20px;
            background-color: rgba(23, 161, 189, 0.0);
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.1);
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light shadow-sm" style="background-color: rgb(137, 211, 225);">
            <div class="container">
                <img src="{{ asset('storage/images/Bean-to-Brew.png') }}" alt="Logo" width="50" height="50">
                <a class="navbar-brand" href="/">
                    Bean to Brew
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <div class="navbar-nav">
                            <a class="nav-link" href="/">Home</a>
                        </div>
                        @auth
                        @if(auth()->user()->role == 1 || auth()->user()->role == 2)
                            <div class="navbar-nav">
                                @if(auth()->user()->role == 2)
                                <a class="nav-link" href="{{ route('features.users') }}">{{ __('Users') }}</a>
                                <a class="nav-link" href="{{ route('features.schedule') }}">{{ __('Schedule') }}</a>
                                @endif
                                <a class="nav-link" href="{{ route('features.inventory') }}">{{ __('Inventory') }}</a>
                                <a class="nav-link" href="{{ route('features.sales') }}">{{ __('Sales') }}</a>
                                @if(auth()->user()->role == 1)
                                <a class="nav-link" href="{{ route('features.cashier') }}">{{ __('Cashier') }}</a>
                                @endif
                            </div>
                        @endif
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <div class="navbar-nav">
                            <a class="nav-link" href="{{ route('features.mapping') }}">{{ __('Find A Store') }}</a>
                            <a class="nav-link" href="{{ route('features.purchase') }}">{{ __('Purchase') }}</a>
                            @auth
                            @if(auth()->user()->role == 0)
                                    <a class="nav-link" href="{{ route('features.cart') }}">{{ __('Cart') }}</a>
                                    <a class="nav-link" href="{{ route('features.orders') }}">{{ __('Orders') }}</a>
                                @endif
                            @endauth
                        </div>
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                @if(auth()->user()->profile && auth()->user()->profile->profile_picture)
                                    <img src="{{ asset('storage/' . auth()->user()->profile->profile_picture) }}" alt="Profile Picture" width="40" height="40" style="border-radius: 50%; border: 2px solid black;">
                                @else
                                    <!-- You can use a default picture here if no profile picture is available -->
                                    <img src="{{ asset('storage/users/default-avatar.jpg') }}" alt="Default Picture" width="40" height="40" style="border-radius: 50%;">
                                @endif
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                                </a>
                                
                                
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('profile') }}">
                                    {{ __('Profile') }}
                                    </a> 

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    @yield('scripts')
</body>
</html>
