<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
    .fixed-height-card {
        height: 445px; /* Set your desired fixed height here */
        overflow: hidden; /* Hide overflow content if necessary */
    }
</style>
<div class="container text-center">
    <div class="row">
        <?php $__currentLoopData = $userItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $buy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col card-column" style="margin-bottom: 0px; margin-top: 20px;">
                <div class="card fixed-height-card" style="width: 10rem; cursor: pointer;">
                    
                    <div class="card-body">
                        <a href="<?php echo e(route('viewitem.showItem.query', ['id' => $buy->id])); ?>" style="text-decoration: none; color: inherit;">
                            <img src="<?php echo e(asset('storage/' . $buy->item_image)); ?>" class="card-img-top" alt="<?php echo e($buy->item_name); ?>">
                            <h5 class="card-title"><?php echo e($buy->item_name); ?></h5>
                            <p class="card-text"><?php echo e($buy->item_price); ?>/Kilo</p>
                            <p class="card-text"><?php echo e($buy->branch); ?> Branch</p>
                            
                            
                            <?php
                                // Fetch all ratings for the current item
                                $ratings = App\Models\Rating::where('purchase_id', $buy->id)->pluck('rating')->toArray();
                                $totalRatings = count($ratings);
                                $averageRating = $totalRatings > 0 ? array_sum($ratings) / $totalRatings : 0;
                            ?>

                            <!-- Display the "Rating" text and coffee cup icons -->
                            <h6 class="mr-2">Rating:</h6>
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <?php
                                    // Determine the glow effect based on the position and fractional part of the average rating
                                    if ($i <= $averageRating) {
                                        $glowClass = 'text-warning'; // Full glowing cup
                                    } else {
                                        $glowClass = ''; // No glow
                                    }
                                ?>
                                <i class="fas fa-coffee fa-1x coffee-icon <?php echo e($glowClass); ?>"></i>
                            <?php endfor; ?>
                            <p>(<?php echo e($totalRatings); ?>)</p>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php /**PATH C:\Users\billy\Desktop\Laravel-Projects\BeanToBrewV2\resources\views/components/cardcolumn.blade.php ENDPATH**/ ?>