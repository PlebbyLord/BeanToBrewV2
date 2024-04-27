<?php $__env->startSection('content'); ?>

<div class="container mt-4">
    <div class="card mx-auto" style="max-width: 850px;">
        <div class="card-header text-center" style="font-size: 30px;">Profile</div>
        <div class="card-body">
            <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
            <?php elseif(session('info')): ?>
                <div class="alert alert-info">
                    <?php echo e(session('info')); ?>

                </div>
            <?php endif; ?>
            <form action="<?php echo e(route('update.profile')); ?>" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
                <?php echo csrf_field(); ?>

                <div class="row">
                    
                    <div class="col-md-4">
                        <img id="previewImage" src="<?php echo e(optional(auth()->user()->profile)->profile_picture ? asset('storage/' . optional(auth()->user()->profile)->profile_picture) : asset('storage/users/default-avatar.jpg')); ?>" alt="Profile Picture" class="img-fluid" style="max-width: 250px; max-height: 250px; border: 1px solid black;">
                        <input type="file" name="image1" class="form-control" accept=".jpg, .jpeg, .png" onchange="previewImage(this)">
                        <small id="fileHelp1" class="form-text text-muted">Accepted formats: .jpg, .jpeg, .png. Maximum size: 10MB</small>
                        <div id="fileError1" class="text-danger"></div>
                    </div>                    

                    
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" class="form-control" value="<?php echo e(auth()->user()->first_name); ?>">
                        </div>
                    
                        <div class="mb-3">
                            <label for="last_name">Last Name</label>
                            <input type="text" name="last_name" class="form-control" value="<?php echo e(auth()->user()->last_name); ?>">
                        </div>
                    
                        <div class="mb-3">
                            <label for="mobile_number">Mobile Number</label>
                            <input type="number" name="mobile_number" class="form-control" value="<?php echo e(auth()->user()->mobile_number); ?>">
                        </div>
                    
                        <div class="mb-3">
                            <label for="address">Address</label>
                            <input type="text" name="address" class="form-control" value="<?php echo e(auth()->user()->address); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="birthday">Birthday</label>
                            <input type="text" name="birthday" class="form-control" value="<?php echo e(auth()->user()->birthday); ?>" readonly>
                        </div>                     
                    </div>                                       
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary float-end">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
    
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\billy\Desktop\Laravel-Projects\BeanToBrewV2\resources\views/Features/profile.blade.php ENDPATH**/ ?>