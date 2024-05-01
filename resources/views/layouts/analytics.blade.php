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
            background-image: url('{{ asset('storage/images/background.png') }}');
            /* Background properties */
            background-size: cover;
            /* Cover the entire viewport */
            background-repeat: repeat;
            /* Prevent background image from repeating */
            background-position: center;
            /* Center the image */
            /* Background color with transparency */
            background-color: rgba(255, 255, 255, 0.0);
            /* 50% white transparency */
            /* Font family and size */
            font-family: 'Nunito', sans-serif;
            font-size: 16px;
            /* Padding for navbar */
            padding-top: 0px;
            /* Adjust based on navbar height */
            margin: 0;
            /* Remove default body margin */
            height: 100vh;
            /* Set body height to full viewport height */
            overflow: auto;
            /* Enable scrolling if content exceeds viewport */
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
            width: 200px;
            /* Adjust width as needed */
            padding: 20px;
            background-color: rgba(23, 161, 189, 0.0);
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.1);
        }
    </style>

    <!-- Scripts -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <link rel="stylesheet" href="{{ asset('build/assets/app-pmJoiQs-.css') }}">
    <script src="{{ asset('build/assets/app-H-rLr14E.js') }}"></script>
</head>

<body>
    <div id="app">


        <nav class="bg-teal-300 border-gray-200 dark:bg-gray-900">
            <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
                <div class="flex justify-center items-center space-x-3 rtl:space-x-reverse">
                    <img src="{{ asset('storage/images/Bean-to-Brew.png') }}" class="h-12" alt="Flowbite Logo" />
                    <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Bean to
                        Brew</span>
                    <a class="nav-link" href="/">Home</a>
                    @auth
                        @if (auth()->user()->role == 1 || auth()->user()->role == 2)
                            <div class="navbar-nav">
                                @if (auth()->user()->role == 2)
                                    <a class="nav-link" href="{{ route('features.users') }}">{{ __('Users') }}</a>
                                    <a class="nav-link" href="{{ route('features.schedule') }}">{{ __('Schedule') }}</a>
                                @endif
                                <a class="nav-link" href="{{ route('features.inventory') }}">{{ __('Inventory') }}</a>
                                <a class="nav-link" href="{{ route('features.sales') }}">{{ __('Sales') }}</a>
                                @if (auth()->user()->role == 1)
                                    <a class="nav-link" href="{{ route('features.cashier') }}">{{ __('Cashier') }}</a>
                                @endif
                            </div>
                        @endif
                    @endauth
                </div>

                <button data-collapse-toggle="navbar-default" type="button"
                    class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                    aria-controls="navbar-default" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M1 1h15M1 7h15M1 13h15" />
                    </svg>
                </button>
                <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                    <ul
                        class="font-medium flex flex-col justify-center items-center p-4 md:p-0 mt-4 border  rounded-lg md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0  dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                        @guest
                            @if (Route::has('login'))
                                <li>
                                    <a href="{{ route('login') }}"
                                        class="block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 dark:text-white md:dark:text-blue-500"
                                        aria-current="page">Login</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li>
                                    <a href="{{ route('register') }}"
                                        class="block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 dark:text-white md:dark:text-blue-500"
                                        aria-current="page">Register</a>
                                </li>
                            @endif
                        @else
                            <a class="nav-link" href="{{ route('features.mapping') }}">{{ __('Find A Store') }}</a>
                            <a class="nav-link" href="{{ route('features.purchase') }}">{{ __('Purchase') }}</a>
                            @auth
                                @if (auth()->user()->role == 0)
                                    <a class="nav-link" href="{{ route('features.cart') }}">{{ __('Cart') }}</a>
                                    <a class="nav-link" href="{{ route('features.orders') }}">{{ __('Orders') }}</a>
                                @endif
                            @endauth
                            <button type="button"
                                class="flex justify-center items-center gap-2 text-sm rounded-full md:me-0"
                                id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown"
                                data-dropdown-placement="bottom">
                                <span class="sr-only">Open user menu</span>
                                @if (auth()->user()->profile && auth()->user()->profile->profile_picture)
                                    <img src="{{ asset('storage/' . auth()->user()->profile->profile_picture) }}"
                                        alt="Profile Picture" width="40" height="40"
                                        style="border-radius: 50%; border: 2px solid black;">
                                @else
                                    <!-- You can use a default picture here if no profile picture is available -->
                                    <img src="{{ asset('storage/users/default-avatar.jpg') }}" alt="Default Picture"
                                        width="40" height="40" style="border-radius: 50%;">
                                @endif
                                {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                            </button>


                            <!-- Dropdown menu -->
                            <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600"
                                id="user-dropdown">
                               
                                <ul class="py-5" aria-labelledby="user-menu-button">
                                    <li>
                                        <a href="{{ route('profile') }}"
                                            class="block px-4 w-20 py-2 text-md text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Profile</a>
                                    </li>
                                    <li class="flex items-center justify-center">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
                                    </li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>


                                </ul>
                            </div>
                        @endguest


                    </ul>
                </div>
            </div>
        </nav>

        {{-- <nav class="navbar navbar-expand-md navbar-light shadow-sm" style="background-color: rgb(137, 211, 225);">
            <div class="container">
                <img src="{{ asset('storage/images/Bean-to-Brew.png') }}" alt="Logo" width="50"
                    height="50">
                <a class="navbar-brand" href="/">
                    Bean to Brew
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <div class="navbar-nav">
                            <a class="nav-link" href="/">Home</a>
                        </div>
                        @auth
                            @if (auth()->user()->role == 1 || auth()->user()->role == 2)
                                <div class="navbar-nav">
                                    @if (auth()->user()->role == 2)
                                        <a class="nav-link" href="{{ route('features.users') }}">{{ __('Users') }}</a>
                                        <a class="nav-link"
                                            href="{{ route('features.schedule') }}">{{ __('Schedule') }}</a>
                                    @endif
                                    <a class="nav-link"
                                        href="{{ route('features.inventory') }}">{{ __('Inventory') }}</a>
                                    <a class="nav-link" href="{{ route('features.sales') }}">{{ __('Sales') }}</a>
                                    @if (auth()->user()->role == 1)
                                        <a class="nav-link"
                                            href="{{ route('features.cashier') }}">{{ __('Cashier') }}</a>
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
                                @if (auth()->user()->role == 0)
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
                                @if (auth()->user()->profile && auth()->user()->profile->profile_picture)
                                    <img src="{{ asset('storage/' . auth()->user()->profile->profile_picture) }}"
                                        alt="Profile Picture" width="40" height="40"
                                        style="border-radius: 50%; border: 2px solid black;">
                                @else
                                    <!-- You can use a default picture here if no profile picture is available -->
                                    <img src="{{ asset('storage/users/default-avatar.jpg') }}" alt="Default Picture"
                                        width="40" height="40" style="border-radius: 50%;">
                                @endif

                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
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

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav> --}}

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    @yield('scripts')
</body>

</html>
