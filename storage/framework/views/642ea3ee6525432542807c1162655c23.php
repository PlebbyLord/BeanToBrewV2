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
                                <?php if($schedule->progress_status == 0 && $schedule->Date_Set > now()): ?>
                                    Waiting
                                <?php elseif($schedule->progress_status == 0 && $schedule->Date_Set < now()): ?> 
                                    Today/Delayed
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
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#harvestModal<?php echo e($schedule->id); ?>">
                                    Complete
                                </button>                           

                                    <!-- Harvest Modal -->
                                    <div class="modal fade" id="harvestModal<?php echo e($schedule->id); ?>" tabindex="-1" role="dialog" aria-labelledby="harvestModalLabel<?php echo e($schedule->id); ?>" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="harvestModalLabel<?php echo e($schedule->id); ?>">Complete Harvesting Schedule</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Harvesting completion form -->
                                                    <form action="<?php echo e(route('updateProgress', $schedule->id)); ?>" method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <div class="form-group">
                                                            <label for="kilosHarvested">Enter Kilos Harvested:</label>
                                                            <input type="number" class="form-control" id="kilosHarvested" name="kilos_harvested" required>
                                                        </div>
                                                        <button type="submit" class="btn btn-primary">Complete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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

<?php $__env->startSection('scripts'); ?>
<!-- CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<!-- Ensure jQuery and Bootstrap JS scripts are loaded -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<!-- Your script to close modal after form submission -->
<script>
    $(document).ready(function() {
        $('form').on('submit', function() {
            $(this).closest('.modal').modal('hide');
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\billy\Desktop\Laravel-Projects\BeanToBrewV2\resources\views/Features/Schedules/history.blade.php ENDPATH**/ ?>