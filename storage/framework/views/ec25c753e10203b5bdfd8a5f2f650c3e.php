<?php $__env->startSection('content'); ?>

<div class="container mt-4">
    <div class="card mx-auto" style="max-width: 600px;">
        <div class="card-header">
            <div class="row">
                <div class="col-2">
                    <a href="<?php echo e(route('features.schedule')); ?>" class="btn btn-sm btn-primary btn-lg px-4" style="font-size: 15px;">Back</a>
                </div>
                <div class="col-9 text-center">
                    <h5>Watering Schedule</h5>
                </div>
            </div>
        </div> 
        <div class="card-body">
            <?php if(session('success')): ?>
            <div class="alert alert-success" role="alert">
                <?php echo e(session('success')); ?>

            </div>
            <?php endif; ?>
            <form method="post" action="<?php echo e(route('water-schedule')); ?>">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label for="coffeeType">Select Coffee Species:</label>
                    <select class="form-control" id="coffeeType" name="coffeeType">
                        <option value="arabica">Arabica</option>
                        <option value="excelsa">Excelsa</option>
                        <option value="liberica">Liberica</option>
                        <option value="robusta">Robusta</option>
                    </select>
                    <?php $__errorArgs = ['coffeeType'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label for="location">Select Location:</label>
                    <select class="form-control" id="location" name="location">
                        <option value="Farm 1">Farm 1</option>
                        <option value="Farm 2">Farm 2</option>
                        <option value="Farm 3">Farm 3</option>
                        <option value="Farm 4">Farm 4</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="batchNumber">Enter Batch Number:</label>
                    <input type="number" class="form-control" id="batchNumber" name="batchNumber" placeholder="Enter batch number...">

                    <?php $__errorArgs = ['batchNumber'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label for="calendar">Select Date:</label>
                    <input type="date" class="form-control" id="calendar" name="calendar">
                    <?php $__errorArgs = ['calendar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\billy\Desktop\Laravel-Projects\BeanToBrewV2\resources\views/Features/Schedules/watering.blade.php ENDPATH**/ ?>