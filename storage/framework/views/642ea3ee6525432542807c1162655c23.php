<?php $__env->startSection('content'); ?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>History</h5>
            <a href="<?php echo e(route('completed')); ?>" class="btn btn-primary">Completed Schedules</a>
            <a href="<?php echo e(route('calendar')); ?>" class="btn btn-primary">Calendar</a>
        </div>               
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Coffee Type</th>
                            <th>Coffee Age</th>
                            <th>Farm Location</th>
                            <th>Batch Number</th>
                            <th>Date Set</th>
                            <th>Schedule Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($schedule->coffee_species); ?></td>
                            <td><?php echo e($schedule->age); ?></td>
                            <td><?php echo e($schedule->location); ?></td>
                            <td><?php echo e($schedule->batch_number); ?></td>
                            <td><?php echo e($schedule->Date_Set); ?></td>
                            <td><?php echo e($schedule->Schedule_Type); ?></td>
                            <td>
                                <?php if($schedule->progress_status == 0&& $schedule->Date_Set > now()): ?>
                                    Waiting
                                <?php elseif($schedule->progress_status == 0 && $schedule->Date_Set < now()): ?> 
                                    Delayed
                                <?php elseif($schedule->progress_status == 1): ?>
                                    In Progress   
                                <?php endif; ?>
                            </td>
                            <td>                                
                                <?php if($schedule->progress_status == 0 && $schedule->Date_Set <= now()): ?>
                                <form action="<?php echo e(route('schedStart', $schedule->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-danger">Start Task</button>
                                </form>
                            <?php elseif($schedule->progress_status == 1): ?>
                                <form action="<?php echo e(route('updateProgress', $schedule->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-danger">Complete</button>
                                </form>
                            <?php endif; ?>                                                     
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\billy\Desktop\Laravel-Projects\BeanToBrewV2\resources\views/Features/Schedules/history.blade.php ENDPATH**/ ?>