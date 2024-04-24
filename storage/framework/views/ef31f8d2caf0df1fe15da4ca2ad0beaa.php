<?php $__env->startSection('content'); ?>

<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h5>Pending Deliveries</h5>
        </div> 
        <div class="card-body">
            <?php if(session('success')): ?>
                <div class="alert alert-success"><?php echo e(session('success')); ?></div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
            <?php endif; ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Cart ID</th>
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
                        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($order->cart->delivery_status != 3): ?>
                        <tr>
                            <td><?php echo e($order->id); ?></td>
                            <td><?php echo e($order->cart_id); ?></td>
                            <td><img src="<?php echo e(asset('storage/' . $order->cart->item_image)); ?>" alt="<?php echo e($order->cart->item_image); ?>" style="max-width: 75px; border: 1px solid black;"></td>
                            <td><?php echo e($order->cart->item_name); ?></td>
                            <td><?php echo e($order->cart->quantity); ?></td>
                            <td><?php echo e($order->name); ?></td>
                            <td><?php echo e($order->address); ?></td>
                            <td><?php echo e($order->number); ?></td>
                            <td><?php echo e($order->shipping_option == 1 ? 'Standard' : 'Express'); ?></td>
                            <td><?php echo e($order->payment_option == 1 ? 'COD' : 'GCash'); ?></td>
                            <td><?php echo e($order->total_payment); ?></td>
                            <td><?php echo e($order->created_at); ?></td>
                            <td>
                                <?php if($order->cart->delivery_status == 1): ?>
                                    <form id="deliverNowForm-<?php echo e($order->cart_id); ?>" method="POST" action="<?php echo e(route('deliver.send', ['cartId' => $order->cart_id])); ?>" onsubmit="return validateDeliveryForm(<?php echo e($order->cart_id); ?>)">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-primary btn-sm" id="submitBtn-<?php echo e($order->cart_id); ?>">Deliver Now</button>
                                        <div id="loadingSpinner-<?php echo e($order->cart_id); ?>" class="d-none text-center mt-2">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </form>
                                <?php elseif($order->cart->delivery_status == 2): ?>
                                    Out For Delivery
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    var isSubmitting = {};

    function validateDeliveryForm(cartId) {
        if (isSubmitting[cartId]) {
            return false; // Ignore additional clicks while submitting
        }

        isSubmitting[cartId] = true; // Set to true to indicate the form is being submitted
        var submitBtn = document.getElementById('submitBtn-' + cartId);
        var loadingSpinner = document.getElementById('loadingSpinner-' + cartId);

        submitBtn.setAttribute('disabled', 'disabled'); // Disable the button to prevent multiple submissions
        loadingSpinner.classList.remove('d-none'); // Show the loading spinner

        // You can optionally remove the disabled attribute and hide the spinner after processing the submission
        // This would typically be done in the success or error callback of your form submission
        // For simplicity, I'm just simulating a 5-second delay here
        setTimeout(function () {
            submitBtn.removeAttribute('disabled');
            loadingSpinner.classList.add('d-none');
            isSubmitting[cartId] = false; // Reset the flag when submission is complete
        }, 9000); // Adjust the time as needed (5 seconds in this example)

        return true; // Allow the form to be submitted
    }
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\billy\Desktop\Laravel-Projects\BeanToBrewV2\resources\views/Features/pending.blade.php ENDPATH**/ ?>