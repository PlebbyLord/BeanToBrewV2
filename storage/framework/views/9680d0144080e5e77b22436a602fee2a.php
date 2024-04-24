<?php $__env->startSection('content'); ?>

<style>
        #filterCard2 {
            position: fixed;
            top: 10;
            left: 0;
            width: 150px; /* Adjust width as needed */
            padding: 20px;
            background-color: rgba(23, 161, 189, 0.0);
            box-shadow: 0 0 50px rgba(0, 0, 0, 0.1);
        }
        .container {
        margin-left: 150px; /* Adjust margin-left to move the content to the right */
    }

    .card-columns {
        margin-top: 20px; /* Adjust top margin for spacing */
    }
</style>

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

    <div class="card-columns">
        <?php $__currentLoopData = $orders->groupBy(function($order) { return $order->created_at->format('F Y'); }); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date => $ordersByMonth): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Sales - <?php echo e($date); ?></h5>
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
                                <?php $__currentLoopData = $ordersByMonth; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\billy\Desktop\Laravel-Projects\BeanToBrewV2\resources\views/Features/onlinesales.blade.php ENDPATH**/ ?>