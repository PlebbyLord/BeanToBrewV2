<?php $__env->startSection('content'); ?>

<div class="container mt-1">
    <div class="card mx-auto" style="max-width: 600px;">
        <div class="card">
            <div class="card-header" style="font-size: 24px;"> <!-- Adjusted font size for the card header -->
                Features
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <a href="<?php echo e(route('features.pending')); ?>" class="btn btn-lg btn-primary btn-block" style="font-size: 20px; width: 250px;">Pending Deliveries</a> <!-- Adjusted font size for the buttons -->
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="<?php echo e(route('features.stats')); ?>" class="btn btn-lg btn-primary btn-block" style="font-size: 20px; width: 250px;">Statistics</a> <!-- Adjusted font size for the buttons -->
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="<?php echo e(route('features.onlinesales')); ?>" class="btn btn-lg btn-primary btn-block" style="font-size: 20px; width: 250px;">Online Sales</a> <!-- Adjusted font size for the buttons -->
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="<?php echo e(route('features.onsitesales')); ?>" class="btn btn-lg btn-primary btn-block" style="font-size: 20px; width: 250px;">Onsite Sales</a> <!-- Adjusted font size for the buttons -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\billy\Desktop\Laravel-Projects\BeanToBrewV2\resources\views/Features/sales.blade.php ENDPATH**/ ?>