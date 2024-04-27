<?php $__env->startSection('content'); ?>
<style>
    .btn-action {
        width: 300%;
    }
</style>
<?php if(session('error')): ?>
<div style="color: red; display: flex; justify-content: center;">
    <?php echo e(session('error')); ?>

</div>
<?php endif; ?>
<?php
    $hasItemsToCheckout = $carts->where('user_id', auth()->id())->where('checkout_status', 1)->isNotEmpty();
?>
<?php if(!$hasItemsToCheckout && auth()->check()): ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 mb-4">
                <div class="card-body text-center">
                    <h3>Your cart is currently empty. Nothing to checkout</h3>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <form method="post" action="<?php echo e(route('place.order')); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10 mb-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center" style="font-size: 15px;">
                            <span style="width: 1%;"></span>
                            <span style="width: 25%;"><?php echo e(__('Product')); ?></span>
                            <span style="width: 17%;"><?php echo e(__('Unit Price')); ?></span>
                            <span style="width: 10%;"><?php echo e(__('Quantity')); ?></span>
                            <span style="width: 16%;"><?php echo e(__('Item Subtotal')); ?></span>
                        </div>
                    </div>
                </div>

                <?php
                    $totalPricesSum = 0;
                ?>

                <?php $__currentLoopData = $carts->where('user_id', auth()->id())->where('checkout_status', 1)->groupBy('purchase.user'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $companyName => $companyCarts): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-10 mb-0">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between" style="font-size: 15px;">
                                Bean to Brew Coffee Bean Shop
                            </div>
                            <div class="card-body">
                                <?php $__currentLoopData = $companyCarts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cart): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="row mb-3 align-items-center">
                                        <div class="col-md-1">
                                            <img src="<?php echo e(asset('storage/' . $cart->item_image)); ?>" alt="<?php echo e($cart->item_name); ?>" class="img-fluid" style="max-width: 75px; max-height: 75px; margin-left: 10px; border: 1px solid black;">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="item_name" style="margin-left: 30px;" class="col-form-label"><?php echo e($cart->item_name); ?></label>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="item_price" style="margin-left: 65px;" class="col-form-label">₱<?php echo e($cart->item_price); ?></label>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="quantity" style="margin-left: 165px;" class="col-form-label"><?php echo e($cart->quantity); ?></label>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="total_price" style="margin-left: 165px;" class="col-form-label total-price">₱<?php echo e($cart->item_price * $cart->quantity); ?></label>
                                        </div>
                                        <?php
                                            $totalPricesSum += $cart->item_price * $cart->quantity;
                                        ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-10 mb-4">
                    <div class="card text-gray mb-0" style="max-width: 100%; min-height: 75px; background-color: rgb(137, 211, 225); border: 2px solid black;">
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-center" style="margin: 2rem auto auto auto;">
                                    <div class="mb-2 row align-items-center">
                                        <div class="col-md-3">
                                            <label for="name">Full Name</label>
                                            <input type="text" name="name" class="form-control" value="<?php echo e(auth()->user()->first_name); ?> <?php echo e(auth()->user()->last_name); ?>" readonly>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="number">Mobile Number</label>
                                            <input type="number" name="number" pattern="(\+63|0)\d{3}[-\s]?\d{7}" class="form-control" value="<?php echo e(auth()->user()->mobile_number); ?>" required readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="address">Address</label>
                                            <input type="text" name="address" class="form-control" value="<?php echo e(auth()->user()->address); ?>" required readonly>
                                        </div>
                                        <div class="col-md-12 mt-2 d-flex justify-content-center">
                                            <span class="badge badge-secondary text-dark" style="margin-right: 20px; font-size: 15px;">Payment Option:</span>
                                            <select class="form-select" name="payment_option[]" aria-label="Default select example" style="width: 100px; height: 3rem;">
                                                <option value="1">COD</option>
                                                <option value="2">GCash</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
                
                <div class="col-md-10 mb-4">
                    <div class="card text-black mb-0" style="max-width: 72rem; min-height: 75px; background-color: rgb(137, 211, 225); border: 2px solid black;">
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-end" style="margin: 2rem auto auto auto;">
                                    <span class="badge badge-secondary text-dark" style="margin-right: 20px; font-size: 15px;">Shipping Option:</span>
                                    <select class="form-select shipping-option" name="shipping_option[]" aria-label="Default select example" style="width: 170px;">
                                        <option selected>Select Shipping</option>
                                        <option value="1">Standard Shipping</option>
                                        <option value="2">Express Shipping</option>
                                    </select>
                                    <span class="badge badge-secondary text-dark shipping-fee-badge" id="shippingFeeBadge_" style="margin-right: 70px; font-size: 13px;">Shipping Fee: ₱0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-10 mb-4">
                    <div class="card text-gray mb-0" style="max-width: 72rem; min-height: 50px; background-color: rgb(137, 211, 225); border: 2px solid black;">
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-12">
                                <div class="d-flex flex-column" style="margin: 1rem auto auto auto;">
                                    <div class="d-flex">
                                        <span class="badge badge-secondary text-dark" id="merchandiseSubtotalLabel" style="margin-left: 650px; font-size: 13px;">Merchandise Subtotal:</span>
                                        <span class="badge badge-secondary text-dark" id="merchandiseSubtotalBadge" style="margin-left: 20px; font-size: 13px;">₱<?php echo e(number_format($totalPricesSum, 2)); ?></span>
                                    </div>
                                    <div class="d-flex">
                                        <span class="badge badge-secondary text-dark" id="shippingTotalLabel" style="margin-left: 650px; font-size: 13px;">Shipping Total:</span>
                                        <span class="badge badge-secondary text-dark order-total-money-badge" id="orderTotalMoneyBadge" style="margin-left: 63px; font-size: 13px;">₱0.00</span>
                                    </div>
                                    <div class="d-flex">
                                        <span class="badge badge-secondary text-dark" id="totalPaymentLabel" style="margin-left: 650px; margin-top: 5px; font-size: 13px;">Total Payment:</span>
                                        <span class="badge badge-secondary text-dark total-payment-badge" id="totalPaymentBadge" style="margin-left: 60px; font-size: 17px;">₱0.00</span>
                                    </div>
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-primary" style="margin-left: 800px;">Place Order</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Handle form submission
            $('form').submit(function (event) {
                // Check if a valid shipping option is selected
                var selectedShippingOption = $('.shipping-option').val();
    
                if (selectedShippingOption === 'Select Shipping') {
                    // Display an alert (you can customize this part)
                    alert('Please select a valid shipping option.');
                    // Prevent form submission
                    event.preventDefault();
                }
            });
    
            // Handle change event for each shipping option
            $('.shipping-option').on('change', function () {
                // Update the shipping fee based on the selected option and quantity
                updateShippingFee();
            });
    
            // Function to update the shipping fee based on the selected option and quantity
            function updateShippingFee() {
            var baseFeeStandard = 100.00; // Base fee for standard shipping
            var baseFeeExpress = 150.00; // Base fee for express shipping
            var incrementStandard = 25.00; // Increment amount for standard shipping per 10 items
            var incrementExpress = 50.00; // Increment amount for express shipping per 10 items

            var selectedValue = $('.shipping-option').val();
            var totalQuantity = getTotalQuantity();

            if (selectedValue === 'Select Shipping') {
                // If 'Select Shipping' is chosen, display shipping fee as ₱0.00
                $('.shipping-fee-badge').text('Shipping Fee: ₱0.00');
            } else {
                var baseFee = selectedValue === '1' ? baseFeeStandard : baseFeeExpress;

                // Calculate additional fees based on total quantity exceeding the threshold (10 items)
                var additionalFees = 0; // Initialize additional fees as 0

                if (totalQuantity > 10) {
                    // Calculate the increment amount based on the selected shipping type
                    var incrementAmount = selectedValue === '1' ? incrementStandard : incrementExpress;
                    
                    // Calculate additional fees only if the total quantity exceeds 10
                    additionalFees = Math.ceil((totalQuantity - 10) / 10) * incrementAmount;
                }

                // Calculate total shipping fee
                var totalShippingFee = baseFee + additionalFees;

                // Update the shipping fee badge
                $('.shipping-fee-badge').text('Shipping Fee: ₱' + totalShippingFee.toFixed(2));

                // Update the total shipping cost and total payment
                updateTotalShippingCost();
            }
        }
    
            // Function to calculate and return the total quantity of items in the cart
            function getTotalQuantity() {
                var totalQuantity = 0;
    
                $('.col-md-2 label[for="quantity"]').each(function () {
                    totalQuantity += parseInt($(this).text());
                });
    
                return totalQuantity;
            }
    
            // Function to update the total shipping cost and total payment
            function updateTotalShippingCost() {
                var totalShippingCost = parseFloat($('.shipping-fee-badge').text().replace('Shipping Fee: ₱', ''));
                $('.order-total-money-badge').text('₱' + totalShippingCost.toFixed(2));
    
                // Update the total payment based on merchandise subtotal and shipping total
                updateTotalPayment();
            }
    
            // Function to update the total payment
            function updateTotalPayment() {
                var merchandiseSubtotal = parseFloat('<?php echo e($totalPricesSum); ?>');
                var shippingTotal = parseFloat($('.order-total-money-badge').text().replace('₱', ''));
                var totalPayment = merchandiseSubtotal + shippingTotal;
    
                $('.total-payment-badge').text('₱' + totalPayment.toFixed(2));
            }
    
            // Initial update when the page loads
            updateShippingFee();
        });
    </script>
    
    
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\billy\Desktop\Laravel-Projects\BeanToBrewV2\resources\views/Features/checkout.blade.php ENDPATH**/ ?>