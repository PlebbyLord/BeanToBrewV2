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

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
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
                                    <img src="{{ asset('storage/' . auth()->user()->profile->profile_picture) }}" alt="Profile Picture" width="40" height="40" style="border-radius: 50%;">
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
</body>
</html>
