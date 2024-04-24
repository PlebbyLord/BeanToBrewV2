<?php $__env->startSection('content'); ?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-2">
                    <a href="<?php echo e(route('features.schedule')); ?>" class="btn btn-sm btn-primary btn-lg px-4" style="font-size: 15px;">Back</a>
                </div>
                <div class="col-9 text-center">
                    <h5>Schedules</h5>
                </div>
            </div>
        </div> 
        <div class="card-body">
            <?php if(session('success')): ?>
            <div class="alert alert-success" role="alert">
                <?php echo e(session('success')); ?>

            </div>
            <?php endif; ?>
            <div id="calendar"></div>
        </div>
    </div>
</div>

<!-- FullCalendar CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />

<!-- jQuery and Moment.js -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<!-- FullCalendar JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>

<!-- Initialize FullCalendar -->
<script>
    $(document).ready(function () {
        // Initialize the calendar
        $('#calendar').fullCalendar({
            selectable: true,
            height: 'auto', // Adjust the height as needed
            events: <?php echo json_encode($events); ?>, // Events data passed from the controller
            eventRender: function(event, element) {
                // Customize event rendering if needed
                // For example, add tooltips or other customizations
            }
        });
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\billy\Desktop\Laravel-Projects\BeanToBrewV2\resources\views/Features/Schedules/calendar.blade.php ENDPATH**/ ?>