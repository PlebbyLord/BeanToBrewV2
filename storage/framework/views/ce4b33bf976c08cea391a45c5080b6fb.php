<?php $__env->startSection('content'); ?>

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
                            <a href="<?php echo e(route('harvest')); ?>" class="btn btn-primary btn-lg btn-block" style="width: 250px; height: 50px;">Set A Schedule</a>
                        </div>
                        <div class="col-md-6 mb-2 mx-auto text-center">
                            <a href="<?php echo e(route('grind')); ?>" class="btn btn-primary btn-lg btn-block" style="width: 250px; height: 50px;">Grinding</a>
                        </div>
                        <div class="col-md-6 mb-2 mx-auto text-center">
                            <a href="<?php echo e(route('pack')); ?>" class="btn btn-primary btn-lg btn-block" style="width: 250px; height: 50px;">Packaging</a>
                        </div>
                        <div class="col-md-6 mb-2 mx-auto text-center">
                            <a href="<?php echo e(route('roast')); ?>" class="btn btn-primary btn-lg btn-block" style="width: 250px; height: 50px;">Roasting</a>
                        </div>
                        <div class="col-md-6 mb-2 mx-auto text-center">
                            <a href="<?php echo e(route('history')); ?>" class="btn btn-primary btn-lg btn-block" style="width: 250px; height: 50px;">History</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\billy\Desktop\Laravel-Projects\BeanToBrewV2\resources\views/Features/schedule.blade.php ENDPATH**/ ?>