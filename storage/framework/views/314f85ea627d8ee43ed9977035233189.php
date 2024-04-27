<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">



<?php $__env->startSection('content'); ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5>Selected Item Details</h5>
                            <!-- Display the selected item details -->
                        </div>
                        <?php
                        // Calculate the average rating for the specific item
                            $ratings = App\Models\Rating::where('purchase_id', $selectedItem->id)->pluck('rating')->toArray();
                            $totalRatings = count($ratings);
                            $averageRating = $totalRatings > 0 ? array_sum($ratings) / $totalRatings : 0;
                            
                            // Determine the integer and fractional parts of the average rating
                            $integerPart = floor($averageRating);
                            $fractionalPart = $averageRating - $integerPart;
                        ?>
                    
                    <div class="col-md-6 d-flex justify-content-end align-items-center">
                        <!-- Display the "Rating" text and coffee cup icons -->
                        <h6 class="mr-2">Rating:</h6>
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <?php
                                // Determine the glow effect based on the position and fractional part of the average rating
                                if ($i <= $integerPart) {
                                    $glowClass = 'text-warning'; // Full glowing cup
                                } elseif ($i == $integerPart + 1 && $fractionalPart > 0) {
                                    $glowClass = 'text-warning-half'; // Half-glowing cup
                                } else {
                                    $glowClass = ''; // No glow
                                }
                            ?>
                            <i class="fas fa-coffee fa-1x coffee-icon <?php echo e($glowClass); ?>"></i>
                        <?php endfor; ?>
                        <span>(<?php echo e($totalRatings); ?>)</span> <!-- Display the total number of ratings -->
                    </div>                    
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-header d-flex justify-content-end align-items-center" style="font-size: 15px;">
                    <form action="<?php echo e(route('cart.addToCart')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <img src="<?php echo e(asset('storage/' . $selectedItem->item_image)); ?>" alt="<?php echo e($selectedItem->item_name); ?>" class="img-fluid">
                            </div>                            
                            <div class="col-md-8">
                                <!-- Display item details -->
                                <div class="row mb-3">
                                    <label for="item_name" class="col-md-4 col-form-label text-md-end"><?php echo e(__('Item Name:')); ?></label>
                                    <div class="col-md-8">
                                        <?php echo e($selectedItem->item_name); ?>

                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="item_price" class="col-md-4 col-form-label text-md-end"><?php echo e(__('Item Price:')); ?></label>
                                    <div class="col-md-8">
                                        <?php echo e($selectedItem->item_price); ?>

                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="expiry_date" class="col-md-4 col-form-label text-md-end"><?php echo e(__('Best before:')); ?></label>
                                    <div class="col-md-8">
                                        <?php echo e($selectedItem->expiry_date); ?>

                                    </div>
                                </div>
                                <!-- Add to cart section -->
                                <div class="row mb-3">
                                    <!-- Check if item is in stock -->
                                    <?php if($selectedItem->item_stock > 0): ?>
                                        <label for="quantity" class="col-md-4 col-form-label text-md-end"><?php echo e(__('Quantity')); ?></label>
                                        <div class="col-md-4">
                                            <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="<?php echo e($selectedItem->item_stock); ?>">
                                            Kilo/s
                                        </div>
                                        <div class="col-md-4">
                                            <label for="item_stock" class="col-form-label"><?php echo e(__('Item Stock:')); ?></label>
                                            <?php echo e($selectedItem->item_stock); ?>

                                        </div>
                                        <input type="hidden" name="purchase_id" value="<?php echo e($selectedItem->id); ?>">
                                        <input type="hidden" name="item_image" value="<?php echo e($selectedItem->item_image); ?>">
                                        <input type="hidden" name="item_name" value="<?php echo e($selectedItem->item_name); ?>">
                                        <input type="hidden" name="item_stock" value="<?php echo e($selectedItem->item_stock); ?>">
                                        <input type="hidden" name="item_price" value="<?php echo e($selectedItem->item_price); ?>">
                                        <input type="hidden" name="expiry_date" value="<?php echo e($selectedItem->expiry_date); ?>">
                                        <div class="col-md-8 offset-md-4">
                                            <?php if(auth()->guard()->check()): ?>
                                                <?php if(auth()->user()->role != 1 && auth()->user()->role != 2): ?>
                                                    <button type="submit" class="btn btn-primary">
                                                        <?php echo e(__('Add To Cart')); ?>

                                                    </button>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <a href="<?php echo e(route('login')); ?>" class="btn btn-primary">
                                                    <?php echo e(__('Login to Order')); ?>

                                                </a>
                                            <?php endif; ?>      
                                        </div>
                                    <?php else: ?>
                                        <div class="col-md-12">
                                            <p class="text-danger">Out of stock</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h5>Description</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <?php echo e($selectedItem->item_description); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h5>Comments</h5>
                </div>
                <div class="card-body comments-container">
                    <?php if($comments->isNotEmpty()): ?>
                        <?php $__currentLoopData = $comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($comment->comment_status == 0): ?>
                                <div class="col-md-12">
                                    <div class="card mb-3">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <!-- Check if the user relationship exists -->
                                            <div>
                                                <?php echo e(substr($comment->user->first_name, 0, 1) . str_repeat('*', strlen($comment->user->first_name) - 2) . substr($comment->user->first_name, -1)); ?> <?php echo e(substr($comment->user->last_name, 0, 1) . str_repeat('*', strlen($comment->user->last_name) - 2) . substr($comment->user->last_name, -1)); ?>

                                            </div>
                                            
                                            <!-- Coffee cups -->
                                            <div class="d-flex align-items-center flex-grow-1"> <!-- Add flex-grow-1 class to make the container grow and push the button to the right -->
                                                <?php
                                                    $rating = $comment->rating; // Get the rating from the comment
                                                ?>
                                                <?php for($i = 1; $i <= 5; $i++): ?>
                                                    <?php
                                                        // Determine the class for the coffee cup (filled or empty)
                                                        $cupClass = $i <= $rating ? 'fas' : 'far';
                                                    ?>
                                                    <i class="<?php echo e($cupClass); ?> fa-coffee text-warning"></i>
                                                <?php endfor; ?>
                                            </div>
                                            
                                            <!-- "Hide Comment" button -->
                                            <?php if(auth()->check() && auth()->user()->role == 2): ?>
                                                <form action="<?php echo e(route('comments.hide', ['id' => $comment->id])); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PUT'); ?>
                                                    <button type="submit" class="btn btn-danger">Hide Comment</button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                        <div class="card-body">
                                            <!-- Display the censored comment -->
                                            <?php echo e($comment->censored_comment ?? $comment->comment); ?>

                                        </div>                                        
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                        <div class="d-flex justify-content-center">
                            <?php echo e($comments->appends(['id' => $selectedItem->id])->links()); ?>

                        </div>
                    <?php else: ?>
                        <p>No comments available for this item.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\billy\Desktop\Laravel-Projects\BeanToBrewV2\resources\views/Features/viewitem.blade.php ENDPATH**/ ?>