<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h1>Users</h1>
                <?php if(auth()->user()->role == 2): ?>
                <div class="d-flex justify-content-end mb-3"> <!-- Use flex utilities to align content to the end -->           
                    <!-- Button to go to features.admins route -->
                    <a href="<?php echo e(route('features.admins')); ?>" class="btn btn-success">Admins</a>
                </div>

                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Profile</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($user->id); ?></td>
                        <td>
                            <?php
                                $profile = App\Models\Profile::where('user_id', $user->id)->first();
                            ?>
                            <?php if($profile && $profile->profile_picture): ?>
                                <img src="<?php echo e(asset('storage/' . $profile->profile_picture)); ?>" alt="Profile Picture" width="40" height="40" style="border-radius: 50%;">
                            <?php else: ?>
                                <!-- You can use a default picture here if no profile picture is available -->
                                <img src="<?php echo e(asset('storage/users/default-avatar.jpg')); ?>" alt="Default Picture" width="40" height="40" style="border-radius: 50%;">
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?></td>
                        <td><?php echo e($user->email); ?></td>
                        <td>
                            <?php if($user->role == 0): ?>
                            Customer
                            <?php elseif($user->role == 1): ?>
                            Admin
                            <?php elseif($user->role == 2): ?>
                            Super Admin
                            <?php else: ?>
                            Unknown
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\billy\Desktop\Laravel-Projects\BeanToBrewV2\resources\views/features/users.blade.php ENDPATH**/ ?>