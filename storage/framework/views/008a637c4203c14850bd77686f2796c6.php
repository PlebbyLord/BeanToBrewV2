<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title>Bean to Brew</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <style>
        /* Set background image and transparency */
        body {
            /* Background image URL */
            background-image: url('<?php echo e(asset("storage/images/background.png")); ?>');
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
    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/app.js']); ?>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light shadow-sm" style="background-color: rgb(137, 211, 225);">
            <div class="container">
                <img src="<?php echo e(asset('storage/images/Bean-to-Brew.png')); ?>" alt="Logo" width="50" height="50">
                <a class="navbar-brand" href="/">
                    Bean to Brew
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="<?php echo e(__('Toggle navigation')); ?>">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <div class="navbar-nav">
                            <a class="nav-link" href="/">Home</a>
                        </div>
                        <?php if(auth()->guard()->check()): ?>
                        <?php if(auth()->user()->role == 1 || auth()->user()->role == 2): ?>
                            <div class="navbar-nav">
                                <?php if(auth()->user()->role == 2): ?>
                                <a class="nav-link" href="<?php echo e(route('features.users')); ?>"><?php echo e(__('Users')); ?></a>
                                <a class="nav-link" href="<?php echo e(route('features.schedule')); ?>"><?php echo e(__('Schedule')); ?></a>
                                <?php endif; ?>
                                <a class="nav-link" href="<?php echo e(route('features.inventory')); ?>"><?php echo e(__('Inventory')); ?></a>
                                <a class="nav-link" href="<?php echo e(route('features.sales')); ?>"><?php echo e(__('Sales')); ?></a>
                                <?php if(auth()->user()->role == 1): ?>
                                <a class="nav-link" href="<?php echo e(route('features.cashier')); ?>"><?php echo e(__('Cashier')); ?></a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <?php endif; ?>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <div class="navbar-nav">
                            <a class="nav-link" href="<?php echo e(route('features.mapping')); ?>"><?php echo e(__('Find A Store')); ?></a>
                            <a class="nav-link" href="<?php echo e(route('features.purchase')); ?>"><?php echo e(__('Purchase')); ?></a>
                            <?php if(auth()->guard()->check()): ?>
                            <?php if(auth()->user()->role == 0): ?>
                                    <a class="nav-link" href="<?php echo e(route('features.cart')); ?>"><?php echo e(__('Cart')); ?></a>
                                    <a class="nav-link" href="<?php echo e(route('features.orders')); ?>"><?php echo e(__('Orders')); ?></a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        <!-- Authentication Links -->
                        <?php if(auth()->guard()->guest()): ?>
                            <?php if(Route::has('login')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo e(route('login')); ?>"><?php echo e(__('Login')); ?></a>
                                </li>
                            <?php endif; ?>

                            <?php if(Route::has('register')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo e(route('register')); ?>"><?php echo e(__('Register')); ?></a>
                                </li>
                            <?php endif; ?>
                        <?php else: ?>
                            <li class="nav-item">
                                <?php if(auth()->user()->profile && auth()->user()->profile->profile_picture): ?>
                                    <img src="<?php echo e(asset('storage/' . auth()->user()->profile->profile_picture)); ?>" alt="Profile Picture" width="40" height="40" style="border-radius: 50%; border: 2px solid black;">
                                <?php else: ?>
                                    <!-- You can use a default picture here if no profile picture is available -->
                                    <img src="<?php echo e(asset('storage/users/default-avatar.jpg')); ?>" alt="Default Picture" width="40" height="40" style="border-radius: 50%;">
                                <?php endif; ?>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <?php echo e(Auth::user()->first_name); ?> <?php echo e(Auth::user()->last_name); ?>

                                </a>
                                
                                
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="<?php echo e(route('profile')); ?>">
                                    <?php echo e(__('Profile')); ?>

                                    </a> 

                                    <a class="dropdown-item" href="<?php echo e(route('logout')); ?>"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <?php echo e(__('Logout')); ?>

                                    </a>

                                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                                        <?php echo csrf_field(); ?>
                                    </form>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
</body>
</html>
<?php /**PATH C:\Users\billy\Desktop\Laravel-Projects\BeanToBrewV2\resources\views/layouts/app.blade.php ENDPATH**/ ?>