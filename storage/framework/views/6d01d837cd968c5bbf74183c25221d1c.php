<?php $__env->startSection('content'); ?>
<?php if(session('success')): ?>
    <div class="alert alert-success">
        <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <!-- Carousel (Image Slider) -->
            <div style="background-color: rgba(255, 255, 255, 0.529); padding: 20px;">
                <h1 style="font-size: 36px; font-weight: bold; font-family: 'Monotype Corsiva', cursive;">PRODUCTS</h1>
            </div>
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000" data-bs-pause="hover">
                <div class="carousel-inner">
                    <!-- First Set of Images -->
                    <div class="carousel-item active">
                        <div class="d-flex justify-content-between">
                            <img src="<?php echo e(asset('storage/slide/Slide12.png')); ?>" class="d-block img-fluid" style="max-width: 200px; height: 300px; opacity: 0.7;" alt="Slide 12">
                            <img src="<?php echo e(asset('storage/slide/Slide1.png')); ?>" class="d-block img-fluid" style="max-width: 300px; height: 450px;" alt="Slide 1">
                            <img src="<?php echo e(asset('storage/slide/Slide2.png')); ?>" class="d-block img-fluid" style="max-width: 200px; height: 300px; opacity: 0.7;" alt="Slide 2">
                        </div>
                    </div>
            
                    <!-- Second Set of Images -->
                    <div class="carousel-item">
                        <div class="d-flex justify-content-between">
                            <img src="<?php echo e(asset('storage/slide/Slide1.png')); ?>" class="d-block img-fluid" style="max-width: 200px; height: 300px; opacity: 0.7;" alt="Slide 1">
                            <img src="<?php echo e(asset('storage/slide/Slide2.png')); ?>" class="d-block img-fluid" style="max-width: 300px; height: 450px;" alt="Slide 2">
                            <img src="<?php echo e(asset('storage/slide/Slide3.png')); ?>" class="d-block img-fluid" style="max-width: 200px; height: 300px; opacity: 0.7;" alt="Slide 3">
                        </div>
                    </div>
            
                    <!-- Third Set of Images -->
                    <div class="carousel-item">
                        <div class="d-flex justify-content-between">
                            <img src="<?php echo e(asset('storage/slide/Slide2.png')); ?>" class="d-block img-fluid" style="max-width: 200px; height: 300px; opacity: 0.7;" alt="Slide 2">
                            <img src="<?php echo e(asset('storage/slide/Slide3.png')); ?>" class="d-block img-fluid" style="max-width: 300px; height: 450px;" alt="Slide 3">
                            <img src="<?php echo e(asset('storage/slide/Slide4.png')); ?>" class="d-block img-fluid" style="max-width: 200px; height: 300px; opacity: 0.7;" alt="Slide 4">
                        </div>
                    </div>
            
                    <!-- Fourth Set of Images -->
                    <div class="carousel-item">
                        <div class="d-flex justify-content-between">
                            <img src="<?php echo e(asset('storage/slide/Slide3.png')); ?>" class="d-block img-fluid" style="max-width: 200px; height: 300px; opacity: 0.7;" alt="Slide 3">
                            <img src="<?php echo e(asset('storage/slide/Slide4.png')); ?>" class="d-block img-fluid" style="max-width: 300px; height: 450px;" alt="Slide 4">
                            <img src="<?php echo e(asset('storage/slide/Slide1.png')); ?>" class="d-block img-fluid" style="max-width: 200px; height: 300px; opacity: 0.7;" alt="Slide 5">
                        </div>
                    </div>
                                
                    <!-- Fifth Set of Images -->
                    <div class="carousel-item">
                        <div class="d-flex justify-content-between">
                            <img src="<?php echo e(asset('storage/slide/Slide4.png')); ?>" class="d-block img-fluid" style="max-width: 200px; height: 300px; opacity: 0.7;" alt="Slide 4">
                            <img src="<?php echo e(asset('storage/slide/Slide5.png')); ?>" class="d-block img-fluid" style="max-width: 300px; height: 450px;" alt="Slide 5">
                            <img src="<?php echo e(asset('storage/slide/Slide6.png')); ?>" class="d-block img-fluid" style="max-width: 200px; height: 300px; opacity: 0.7;" alt="Slide 6">
                        </div>
                    </div>

                    <!-- Sixth Set of Images -->
                    <div class="carousel-item">
                        <div class="d-flex justify-content-between">
                            <img src="<?php echo e(asset('storage/slide/Slide5.png')); ?>" class="d-block img-fluid" style="max-width: 200px; height: 300px; opacity: 0.7;" alt="Slide 5">
                            <img src="<?php echo e(asset('storage/slide/Slide6.png')); ?>" class="d-block img-fluid" style="max-width: 300px; height: 450px;" alt="Slide 6">
                            <img src="<?php echo e(asset('storage/slide/Slide7.png')); ?>" class="d-block img-fluid" style="max-width: 200px; height: 300px; opacity: 0.7;" alt="Slide 7">
                        </div>
                    </div>                    

                    <!-- Seventh Set of Images -->
                    <div class="carousel-item">
                        <div class="d-flex justify-content-between">
                            <img src="<?php echo e(asset('storage/slide/Slide6.png')); ?>" class="d-block img-fluid" style="max-width: 200px; height: 300px; opacity: 0.7;" alt="Slide 6">
                            <img src="<?php echo e(asset('storage/slide/Slide7.png')); ?>" class="d-block img-fluid" style="max-width: 300px; height: 450px;" alt="Slide 7">
                            <img src="<?php echo e(asset('storage/slide/Slide8.png')); ?>" class="d-block img-fluid" style="max-width: 200px; height: 300px; opacity: 0.7;" alt="Slide 8">
                        </div>
                    </div>  
                    
                     <!-- Eighth Set of Images -->
                    <div class="carousel-item">
                        <div class="d-flex justify-content-between">
                            <img src="<?php echo e(asset('storage/slide/Slide7.png')); ?>" class="d-block img-fluid" style="max-width: 200px; height: 300px; opacity: 0.7;" alt="Slide 7">
                            <img src="<?php echo e(asset('storage/slide/Slide8.png')); ?>" class="d-block img-fluid" style="max-width: 300px; height: 450px;" alt="Slide 8">
                            <img src="<?php echo e(asset('storage/slide/Slide9.png')); ?>" class="d-block img-fluid" style="max-width: 200px; height: 300px; opacity: 0.7;" alt="Slide 9">
                        </div>
                    </div>    
                    
                    <!-- Ninth Set of Images -->
                    <div class="carousel-item">
                        <div class="d-flex justify-content-between">
                            <img src="<?php echo e(asset('storage/slide/Slide8.png')); ?>" class="d-block img-fluid" style="max-width: 200px; height: 300px; opacity: 0.7;" alt="Slide 8">
                            <img src="<?php echo e(asset('storage/slide/Slide9.png')); ?>" class="d-block img-fluid" style="max-width: 300px; height: 450px;" alt="Slide 9">
                            <img src="<?php echo e(asset('storage/slide/Slide10.png')); ?>" class="d-block img-fluid" style="max-width: 200px; height: 300px; opacity: 0.7;" alt="Slide 10">
                        </div>
                    </div> 
                    
                    <!-- Tenth Set of Images -->
                    <div class="carousel-item">
                        <div class="d-flex justify-content-between">
                            <img src="<?php echo e(asset('storage/slide/Slide9.png')); ?>" class="d-block img-fluid" style="max-width: 200px; height: 300px; opacity: 0.7;" alt="Slide 9">
                            <img src="<?php echo e(asset('storage/slide/Slide10.png')); ?>" class="d-block img-fluid" style="max-width: 300px; height: 450px;" alt="Slide 10">
                            <img src="<?php echo e(asset('storage/slide/Slide11.png')); ?>" class="d-block img-fluid" style="max-width: 200px; height: 300px; opacity: 0.7;" alt="Slide 11">
                        </div>
                    </div>
                    
                    <!-- Eleventh Set of Images -->
                    <div class="carousel-item">
                        <div class="d-flex justify-content-between">
                            <img src="<?php echo e(asset('storage/slide/Slide10.png')); ?>" class="d-block img-fluid" style="max-width: 200px; height: 300px; opacity: 0.7;" alt="Slide 10">
                            <img src="<?php echo e(asset('storage/slide/Slide11.png')); ?>" class="d-block img-fluid" style="max-width: 300px; height: 450px;" alt="Slide 11">
                            <img src="<?php echo e(asset('storage/slide/Slide12.png')); ?>" class="d-block img-fluid" style="max-width: 200px; height: 300px; opacity: 0.7;" alt="Slide 12">
                        </div>
                    </div> 
                    
                    <!-- Twelfth Set of Images -->
                    <div class="carousel-item">
                        <div class="d-flex justify-content-between">
                            <img src="<?php echo e(asset('storage/slide/Slide11.png')); ?>" class="d-block img-fluid" style="max-width: 200px; height: 300px; opacity: 0.7;" alt="Slide 11">
                            <img src="<?php echo e(asset('storage/slide/Slide12.png')); ?>" class="d-block img-fluid" style="max-width: 300px; height: 450px;" alt="Slide 12">
                            <img src="<?php echo e(asset('storage/slide/Slide1.png')); ?>" class="d-block img-fluid" style="max-width: 200px; height: 300px; opacity: 0.7;" alt="Slide 1">
                        </div>
                    </div>                    
                </div>
            
                <!-- Carousel Controls -->
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>                         
        </div>
    </div>
</div>

<div class="container custom-container">
    <div class="row align-items-center">
        <!-- Left column for the image (6 columns) -->
        <div class="col-md-6 p-0">
            <img src="<?php echo e(asset('storage/images/4.png')); ?>" class="img-fluid custom-img-size" alt="4">
        </div>
        <!-- Right column for the About Us content (6 columns) -->
        <div class="col-md-6 p-0">
            <!-- Wrap the anchor tag around the image -->
            <a href="<?php echo e(route('features.purchase')); ?>">
                <img src="<?php echo e(asset('storage/images/8.png')); ?>" class="img-fluid custom-img-size" alt="8">
            </a>
        </div>
    </div>
</div>

<div class="container custom-container" style="margin-top: 30px; margin-bottom: 30px;">
    <div class="row align-items-center">
        <!-- Right column for the About Us content (6 columns) -->
        <div class="col-md-6 p-0">
                    <img src="<?php echo e(asset('storage/images/12.png')); ?>" class="img-fluid custom-img-size" alt="12">
        </div>
        <div class="col-md-6 p-0">
            <a href="<?php echo e(route('features.mapping')); ?>">
                <img src="<?php echo e(asset('storage/images/14.png')); ?>" class="img-fluid custom-img-size" alt="14">
            </a>
        </div>
    </div>
</div>
        
<footer class="bg-dark text-white py-0"> <!-- Adjusted margin-top to move the footer higher -->
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-3">
                <!-- Left side with image -->
                <img src="<?php echo e(asset('storage/images/Bean-to-Brew.png')); ?>" class="img-fluid" alt="Footer Image" style="margin-left: -10px;">
                <p class="card-text" style="font-size: 14px;"> Mobile Number: 09983743285 </p>
                <p class="card-text" style="font-size: 14px;"> Email: beantobrew24@gmail.com </p>
                <p class="card-text" style="font-size: 14px;">
                    <a href="<?php echo e(route('terms')); ?>">Terms and Conditions</a>
                </p>
            </div>
            <div class="col-md-9">
                <!-- Right side with About Us content -->
                <div class="card bg-transparent border-0">
                    <div class="card-body text-white">
                        <h5 class="card-title" style="font-size: 24px; font-family: 'Monotype Corsiva', cursive;">About Us</h5>
                        <p class="card-text" style="font-size: 16px;">At Bean to Brew, our mission is simple: to deliver unparalleled solutions that exceed expectations and drive success for our clients. Whether it's through cutting-edge technology, groundbreaking research, or forward-thinking strategies, we are constantly striving to elevate the standards of excellence in everything we do. Our team of seasoned professionals, each an expert in their respective fields, brings together a wealth of knowledge, experience, and passion to every project we undertake. What sets us apart is not just what we do, but how we do it. We believe in fostering strong partnerships built on transparency, collaboration, and mutual respect. By truly understanding our clients' needs, challenges, and aspirations, we tailor our solutions to deliver maximum value and impact. Our unwavering dedication to innovation, coupled with our client-centric approach, has earned us a reputation for reliability, trustworthiness, and excellence.</p>
                        <p class="card-text" style="font-size: 16px;">As we continue to grow and evolve, we remain steadfast in our commitment to pushing the boundaries of what's possible and driving positive change in agriculture and ecommerce. Whether you're a startup looking to disrupt the status quo or an established enterprise seeking to stay ahead of the curve, Bean to Brew is your trusted partner for success. Join us on our journey as we continue to innovate, inspire, and lead the way in Agriculture and ecommerce industries. Welcome to Bean to Brew. Welcome to the future of Agriculture and Ecommerce Industries.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Custom CSS for Carousel Transition -->
<style>
    .carousel-item {
        transition: transform 0.1s ease; /* Customize the transition duration and easing */
    }
    footer {
        height: 540px; /* Adjust the height as needed */
    }
    /* Custom styles for the container */
    .custom-container {
        margin-left: 100px;
        margin-right: 100px;
    }

    /* Ensure both columns are flex containers */
    .row.align-items-center {
        display: flex;
    }

    /* Set the right column to match the height of the left column */
    .col-md-6 {
        display: flex;
        align-items: stretch;
    }

    /* Custom styles for the card */
    .card {
        background-color: #4b9ef6; /* Change to desired background color */
        color: #fff; /* Change to desired text color */
        height: 100%; /* Set the height of the card to match the image column */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 50px; /* Adjust padding as needed */
    }

    /* Increase font size of button */
    .custom-img-size {
        width: 1000px; /* Set the width to 100px */
        height: 550px; /* Set the height to 100px */
        object-fit: cover; /* Maintain aspect ratio and cover container */
    }
</style>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\billy\Desktop\Laravel-Projects\BeanToBrewV2\resources\views/home.blade.php ENDPATH**/ ?>