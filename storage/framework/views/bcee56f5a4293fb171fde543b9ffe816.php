<?php $__env->startSection('content'); ?>
<style>
    .btn-action {
        width: 300%; /* Set the desired width for the buttons */
    }
</style>

<?php if($carts->where('user_id', auth()->id())->where('checkout_status', 2)->isEmpty() && auth()->check()): ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 mb-4">
                <div class="card-body text-center">
                    <h3>You have no orders.</h3>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-20 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center" style="font-size: 15px;">
                        <span style="width: 1%;"></span>
                        <span style="width: 15%;"><?php echo e(__('Product')); ?></span>
                        <span style="width: 10%;"><?php echo e(__('Unit Price')); ?></span>
                        <span style="width: 10%;"><?php echo e(__('Quantity')); ?></span>
                        <span style="width: 10%;"><?php echo e(__('Name')); ?></span>
                        <span style="width: 8%;"><?php echo e(__('Number')); ?></span>
                        <span style="width: 7%;"><?php echo e(__('Address')); ?></span>
                        <span style="width: 10%;"><?php echo e(__('Shipping Option')); ?></span>
                        <span style="width: 10%;"><?php echo e(__('Payment Option')); ?></span>
                        <span style="width: 9%;"><?php echo e(__('Item Subtotal')); ?></span>
                        <span style="width: 14%;"><?php echo e(__('Delivery Status')); ?></span>
                    </div>
                </div>
            </div>
            <?php $__currentLoopData = $carts->where('user_id', auth()->id())->where('checkout_status', 2)->groupBy('purchase.user'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $companyName => $companyCarts): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-20 mb-3"> <!-- Adjust the width as needed -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between" style="font-size: 15px;">
                        Bean to Brew Coffee Bean Shop
                    </div>
                    <div class="card-body">
                        <?php
                            $totalPricesSum = 0; // Reset the total price for each group
                        ?>
                        <?php $__currentLoopData = $companyCarts->groupBy(function ($item) {
                            return $item->orders->first()->created_at->format('H:i'); // Group by hours, minutes, and seconds
                        }); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $time => $timeCarts): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card">
                            <div class="card-header d-flex justify-content-between" style="font-size: 15px;">
                                <?php echo e($time); ?>

                            </div>
                            <div class="card-body">
                                <?php $__currentLoopData = $timeCarts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cart): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="row mb-3 align-items-center">
                                    <div class="col-md-1">
                                        <img src="<?php echo e(asset('storage/' . $cart->item_image)); ?>" alt="<?php echo e($cart->item_name); ?>"
                                            class="img-fluid" style="max-width: 75px; max-height: 75px; margin-left: 10px; border: 1px solid black;">
                                    </div>
                                    <div class="col-md-1">
                                        <label for="item_name" style="margin-left: 1px;" class="col-form-label"><?php echo e($cart->item_name); ?></label>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="item_price" style="margin-left: -20px;" class="col-form-label">₱<?php echo e($cart->item_price); ?></label>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="quantity" style="margin-left: 5px;" class="col-form-label"><?php echo e($cart->quantity); ?></label>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="name" style="margin-left: -25px;" class="col-form-label"><?php echo e($cart->orders->first()->name); ?></label>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="number" style="margin-left: 10px;" class="col-form-label"><?php echo e($cart->orders->first()->number); ?></label>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="address" style="margin-left: 30px;" class="col-form-label"><?php echo e($cart->orders->first()->address); ?></label>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="shipping_option" style="margin-left: 50px;" class="col-form-label">
                                            <?php echo e($cart->orders->first()->shipping_option == 1 ? 'Standard Shipping' : 'Express Shipping'); ?>

                                        </label>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="payment_option" style="margin-left: 60px;" class="col-form-label">
                                            <?php echo e($cart->orders->first()->payment_option == 1 ? 'COD' : 'GCash'); ?>

                                        </label>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="total_price" style="margin-left: 70px;" class="col-form-label total-price">₱<?php echo e($cart->item_price * $cart->quantity); ?></label>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="delivery_status" style="margin-left: 70px;" class="col-form-label delivery-status">
                                            <?php echo e($cart->delivery_status == 1 ? 'Preparing to Ship' : ($cart->delivery_status == 2 ? 'Out For Delivery' : 'Delivered')); ?>

                                            <?php if($cart->delivery_status == 1 && $cart->orders->first()->shipping_option == 1): ?>
                                                <br>
                                                <small>Expect delivery at <?php echo e(\Carbon\Carbon::parse($cart->orders->first()->created_at)->addDays(3)->format('F j')); ?> - <?php echo e(\Carbon\Carbon::parse($cart->orders->first()->created_at)->addDays(5)->format('j')); ?></small>
                                            <?php elseif($cart->delivery_status == 1 && $cart->orders->first()->shipping_option == 2): ?>
                                                <br>
                                                <small>Expect delivery at <?php echo e(\Carbon\Carbon::parse($cart->orders->first()->created_at)->addDays(1)->format('F j')); ?> - <?php echo e(\Carbon\Carbon::parse($cart->orders->first()->created_at)->addDays(2)->format('j')); ?></small>
                                            <?php endif; ?>
                                        </label>
                                    </div>                                    
                                    <div class="col-md-5">
                                        <div class="d-flex justify-content-start align-items-center" style="margin-top: 0px;">
                                            <?php if($cart->delivery_status == 2 && !$cart->is_received): ?>
                                                <form id="orderReceivedForm-<?php echo e($cart->id); ?>" method="POST" action="<?php echo e(route('orders.updateDeliveryStatus', ['cartId' => $cart->id])); ?>" onsubmit="return validateForm()">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PUT'); ?>
                                                    <button type="submit" class="btn btn-primary btn-sm" onclick="disableButton(<?php echo e($cart->id); ?>)" style="font-size: 15px; margin-left: 1080px; width: 130px;">Order Received</button>
                                                    <div id="loadingSpinner" class="d-none text-center mt-2">
                                                        <div class="spinner-border text-primary" role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>
                                                    </div>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <?php if($cart->delivery_status == 3 && $cart->rate_status == 1): ?>
                                        <a href="<?php echo e(route('features.rate', ['cart_id' => $cart->id])); ?>" class="btn btn-primary" style="font-size: 15px; margin-left: 575px; width: 100px;">Rate</a>
                                        <?php endif; ?>
                                    </div>                                   
                                    <?php
                                    $totalPricesSum += $cart->item_price * $cart->quantity; // Accumulate the item subtotal for each item
                                    if ($cart->orders->first()->shipping_option == 2){
                                        $totalPricesSum += 150;
                                    } else {
                                        $totalPricesSum += 100;
                                    }
                                    ?>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-md-20 mb-4">
                                    <div class="card">
                                        <div class="card-body"> <!-- Added card-body here -->
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge badge-secondary text-dark" id="totalPaymentLabel" style="margin-top: 5px; margin-left: 910px; font-size: 15px;">Total Payment w/shipping:</span> <!-- Adjusted margin-left -->
                                                <span class="badge badge-secondary text-dark" id="totalPaymentBadge" style="font-size: 17px;">₱<?php echo e(number_format($totalPricesSum, 2)); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                            $totalPricesSum = 0; // Reset the total price for each group
                        ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <script>
        var isSubmitting = false;
    
        function validateForm() {
            if (isSubmitting) {
                return false; // Ignore additional clicks while submitting
            }
    
            isSubmitting = true; // Set to true to indicate the form is being submitted
            var submitBtn = document.getElementById('submitBtn');
            var loadingSpinner = document.getElementById('loadingSpinner');
    
            submitBtn.setAttribute('disabled', 'disabled'); // Disable the button to prevent multiple submissions
            loadingSpinner.classList.remove('d-none'); // Show the loading spinner
    
            // You can optionally remove the disabled attribute and hide the spinner after processing the submission
            // This would typically be done in the success or error callback of your form submission
            // For simplicity, I'm just simulating a 5-second delay here
            setTimeout(function () {
                submitBtn.removeAttribute('disabled');
                loadingSpinner.classList.add('d-none');
                isSubmitting = false; // Reset the flag when submission is complete
            }, 5000); // Adjust the time as needed (5 seconds in this example)
    
            return true; // Allow the form to be submitted
        }
    </script>

<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\billy\Desktop\Laravel-Projects\BeanToBrewV2\resources\views/Features/orders.blade.php ENDPATH**/ ?>