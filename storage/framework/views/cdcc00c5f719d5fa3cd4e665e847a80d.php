<?php $__env->startSection('content'); ?>

<?php if(session('error')): ?>
<div style="color: red; display: flex; justify-content: center;">
    <?php echo e(session('error')); ?>

</div>
<?php endif; ?>
<?php if($carts->where('user_id', auth()->id())->where('checkout_status', 1)->isEmpty() && auth()->check()): ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 mb-4">
                <div class="card-body text-center">
                    <h3>Your cart is currently empty or all items are checked out.</h3>
                </div>
            </div>
        </div>
<?php else: ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center"
                        style="font-size: 15px;">
                        <span style="width: 30%;"><?php echo e(__('Product')); ?></span>
                        <span style="width: 15%;"><?php echo e(__('Unit Price')); ?></span>
                        <span style="width: 15%;"><?php echo e(__('Available Stock')); ?></span>
                        <span style="width: 15%;"><?php echo e(__('Quantity')); ?></span>
                        <span style="width: 15%;"><?php echo e(__('Item Subtotal')); ?></span>
                        <span style="width: 10%;"><?php echo e(__('Action')); ?></span>
                    </div>
                </div>
            </div>
            <?php
                $totalPricesSum = 0;
            ?>
            <?php $__currentLoopData = $carts->where('user_id', auth()->id())->where('checkout_status', 1)->groupBy('purchase.user'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $companyName => $companyCarts): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-10 mb-3">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between"
                            style="font-size: 15px;">
                            Bean to Brew Coffee Bean Shop
                        </div>
                        <div class="card-body">
                            <?php $__currentLoopData = $companyCarts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cart): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="row mb-3 align-items-center">
                                    <div class="col-md-2">
                                        <img src="<?php echo e(asset('storage/' . $cart->item_image)); ?>"
                                            alt="<?php echo e($cart->item_name); ?>" class="img-fluid"
                                            style="max-width: 75px; max-height: 75px; border: 1px solid black;">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="item_name" style="margin-left: -100px"
                                            class="col-form-label"><?php echo e($cart->item_name); ?></label>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="item_price" style="margin-left: -35px"
                                            class="col-form-label">₱<?php echo e($cart->item_price); ?></label>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="item_stock" style="margin-left: 70px"
                                            class="col-form-label"><?php echo e($cart->purchase->item_stock); ?></label>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="quantity" style="margin-left: 110px"
                                            class="col-form-label"><?php echo e($cart->quantity); ?></label>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="total_price" style="margin-left: 190px"
                                            class="col-form-label">₱<?php echo e($cart->item_price * $cart->quantity); ?></label>
                                    </div>
                                    <div class="col-md-2"> 
                                        <form action="<?php echo e(route('cart.destroy', $cart->id)); ?>" style="margin-left: 220px"
                                            method="POST">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit"
                                                class="btn btn-danger btn-sm ml-2 btn-action"
                                                onclick="return confirm('Are you sure you want to delete this item?')">
                                                <?php echo e(__('Delete')); ?>

                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-primary btn-sm ml-2 btn-action" style="width: 55px; margin-left: 220px;" data-bs-toggle="modal" data-bs-target="#updateQuantityModal_<?php echo e($cart->id); ?>">
                                            Edit
                                        </button>
                                    </div>
                                    <?php
                                        $totalPricesSum += $cart->item_price * $cart->quantity;
                                    ?>
                                </div>
                                <!-- Your blade file code for the modal -->
                                <div class="modal fade"
                                    id="updateQuantityModal_<?php echo e($cart->id); ?>"
                                    data-bs-backdrop="static"
                                    data-bs-keyboard="true" tabindex="-1"
                                    aria-labelledby="updateQuantityModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5"
                                                    id="updateQuantityModalLabel">Update
                                                    Quantity</h1>
                                                <button type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post"
                                                    action="<?php echo e(route('cart.updateQuantity')); ?>"
                                                    enctype="multipart/form-data">
                                                    <?php echo csrf_field(); ?>
                                                    <div class="mb-3">
                                                        <label for="quantity"
                                                            class="form-label">New
                                                            Quantity</label>
                                                        <input type="number"
                                                            class="form-control"
                                                            id="quantity"
                                                            name="quantity"
                                                            required>
                                                    </div>
                                                    <input type="hidden"
                                                        id="cartItemId"
                                                        name="cartItemId"
                                                        value="<?php echo e($cart->id); ?>">
                                                    <?php if($cart->purchase->item_stock < $cart->quantity): ?>
                                                        <p class="text-danger">Warning:
                                                            Quantity exceeds
                                                            available stock.</p>
                                                    <?php endif; ?>
                                                    <button type="submit"
                                                        class="btn btn-primary"
                                                        <?php echo e($cart->purchase->item_stock < $cart->quantity ? 'disabled' : ''); ?>>
                                                        Submit
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center"
                        style="font-size: 20px;">
                        <div class="d-flex">
                            <span class="badge badge-secondary text-dark" id="merchandiseSubtotalLabel" style="margin-left: 500px; font-size: 20px;">Total:</span>
                            <span class="badge badge-secondary text-dark" id="merchandiseSubtotalBadge" style="margin-left: 20px; font-size: 20px;">₱<?php echo e(number_format($totalPricesSum, 2)); ?></span>
                        </div>
                        <form method="GET" action="<?php echo e(route('checkout')); ?>" enctype="multipart/form-data" onsubmit="return confirm('Are you sure you want to check out all your items?');">  
                            <button type="submit"
                                class="btn btn-primary short-btn"
                                style="margin-left: 55px;">
                                Checkout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\billy\Desktop\Laravel-Projects\BeanToBrewV2\resources\views/Features/cart.blade.php ENDPATH**/ ?>