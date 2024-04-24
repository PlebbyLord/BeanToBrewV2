<?php $__env->startSection('content'); ?>
<p class="Name text-center" style="font-size: 50px; text-decoration: underline; font-weight: bold; margin-left: 700px; color: rgb(0, 0, 0); margin-top: 50px; font-family: 'Monotype Corsiva', cursive; background-color: rgba(255, 255, 255, 0.8); padding: 10px; display: inline;">
    Online Sales
</p>   
<style>
    #filterCard2 {
        position: fixed;
        top: 66px;
        left: 0px; /* Position on the left side */
        width: 150px;
        padding: 20px;
        background-color: #f4d693; /* Set background color to white */
        border: 1px solid rgba(0, 0, 0, 0.1); /* Add a border for visibility */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2); /* Add a subtle shadow */
        z-index: 1000; /* Ensure it appears above other content */
    }
    .container {
        margin-left: 150px; /* Adjust margin-left to move the content to the right */
    }
    .card-columns {
        margin-top: 20px; /* Adjust top margin for spacing */
    }
    .card-footer {
        background-color: #f8f9fa;
        text-align: right;
        font-weight: bold;
    }
    .form-group {
        margin-bottom: 10px; /* Adjust margin bottom for spacing between form elements */
    }
    .form-control {
        width: 600px; /* Adjust width of form controls */
    }
    .btn-primary {
        padding: 5px 10px; /* Adjust padding of the button */
        font-size: 14px; /* Adjust font size of the button text */
    }
</style>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <?php if(auth()->user()->role == 1): ?>
                <form id="exportForm" action="<?php echo e(route('export.online.sales')); ?>" method="GET" class="text-center">
                    <div class="form-group">
                        <label for="start_date">Start Date:</label>
                        <input type="date" id="start_date" name="start_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="end_date">End Date:</label>
                        <input type="date" id="end_date" name="end_date" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to export Online sales data?')">
                        Export Excel
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="container mt-4">
    <!-- Month Filter Form -->
    <div id="filterCard2">
        <form id="filterForm2">
            <div>
                <label for="month">Filter by Month:</label><br>
                <input type="checkbox" id="all" name="month[]" value="all">
                <label for="all">All</label><br>
                <?php $__currentLoopData = [
                    'January', 'February', 'March', 'April', 'May', 'June',
                    'July', 'August', 'September', 'October', 'November', 'December'
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <input type="checkbox" id="<?php echo e(strtolower($month)); ?>" name="month[]" value="<?php echo e($month); ?>">
                    <label for="<?php echo e(strtolower($month)); ?>"><?php echo e($month); ?></label><br>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </form>
    </div>

    <hr>

    <?php $__currentLoopData = $orders->groupBy(function($order) { return $order->created_at->format('F Y'); }); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month => $ordersByMonth): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Sales - <?php echo e($month); ?></h5>
            </div>
            <div class="card-body">
                <?php $__currentLoopData = $ordersByMonth->groupBy(function($order) { return $order->created_at->format('Y-m-d'); }); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day => $ordersByDay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="card">
                        <div class="card-header">
                            <h6><?php echo e($day); ?></h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Item Image</th>
                                            <th>Item Name</th>
                                            <th>Quantity</th>
                                            <th>Name</th>
                                            <th>Address</th>
                                            <th>Number</th>
                                            <th>Shipping Option</th>
                                            <th>Payment Option</th>
                                            <th>Total Payment</th>
                                            <th>Order Date</th>
                                            <th>Delivery Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $dailyTotal = 0; // Initialize daily total
                                        ?>
                                        <?php $__currentLoopData = $ordersByDay; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($order->cart->delivery_status == 3): ?>
                                                <tr>
                                                    <td><?php echo e($order->id); ?></td>
                                                    <td><img src="<?php echo e(asset('storage/' . $order->cart->item_image)); ?>" alt="<?php echo e($order->cart->item_image); ?>" style="max-width: 75px; border: 1px solid black;"></td>
                                                    <td><?php echo e($order->cart->item_name); ?></td>
                                                    <td><?php echo e($order->cart->quantity); ?></td>
                                                    <td><?php echo e($order->name); ?></td>
                                                    <td><?php echo e($order->address); ?></td>
                                                    <td><?php echo e($order->number); ?></td>
                                                    <td><?php echo e($order->shipping_option == 1 ? 'Standard' : 'Express'); ?></td>
                                                    <td><?php echo e($order->payment_option == 1 ? 'COD' : 'GCash'); ?></td>
                                                    <td>₱<?php echo e($order->total_payment); ?></td>
                                                    <td><?php echo e($order->created_at); ?></td>
                                                    <td>Delivered</td>
                                                </tr>
                                                <?php
                                                    $dailyTotal += $order->total_payment; // Accumulate daily total payment
                                                ?>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            Total Sales for <?php echo e($day); ?>: ₱ <?php echo e($dailyTotal); ?>

                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<script>
    // JavaScript for handling month filtering
    const checkboxes = document.querySelectorAll('input[name="month[]"]');
    const allCheckbox = document.getElementById('all');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const selectedMonths = Array.from(document.querySelectorAll('input[name="month[]"]:checked')).map(checkbox => checkbox.value);
            const urlParams = new URLSearchParams(window.location.search);

            // Remove existing month parameters
            urlParams.delete('month[]');

            // Append the selected months
            selectedMonths.forEach(month => {
                urlParams.append('month[]', month);
            });

            // Reload the page with updated URL parameters
            window.location.href = '?' + urlParams.toString();
        });
    });

    // Handle "All" checkbox
    allCheckbox.addEventListener('change', () => {
        if (allCheckbox.checked) {
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
        } else {
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
        }

        // Reload the page with no month parameters when "All" is checked
        window.location.href = '<?php echo e(route("features.onlinesales")); ?>';
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\billy\Desktop\Laravel-Projects\BeanToBrewV2\resources\views/features/onlinesales.blade.php ENDPATH**/ ?>