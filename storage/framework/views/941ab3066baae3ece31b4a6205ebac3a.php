<!-- In your layout file or HTML head section -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- In your content view -->


<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="card mx-auto" style="max-width: 400px;">
        <div class="card-header" style="font-size: 20px;">Rate Item</div>
        <div class="card-body">
            <div class="row">
                <form method="POST" action="<?php echo e(route('rate.save')); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="rating" name="rating">
                    <input type="hidden" id="cart_id" name="cart_id" value="<?php echo e($cart->id); ?>">
                    <!-- Left column for name and coffee beans -->
                    <div class="col-md-4">
                        <div class="mb-2">
                            <img src="<?php echo e(asset('storage/' . $cart->item_image)); ?>" alt="<?php echo e($cart->item_name); ?>" style="max-width: 100px; border: 1px solid black;" class="img-fluid">
                        </div>
                    </div>
                    <!-- Right column for the image -->
                    <div class="col-md-8">
                        <h5><?php echo e($cart->item_name); ?></h5>

                        <div class="mb-1">
                            <!-- FontAwesome coffee bean icons with larger size -->
                            <i class="fas fa-coffee fa-2x coffee-icon" data-value="1"></i>
                            <i class="fas fa-coffee fa-2x coffee-icon" data-value="2"></i>
                            <i class="fas fa-coffee fa-2x coffee-icon" data-value="3"></i>
                            <i class="fas fa-coffee fa-2x coffee-icon" data-value="4"></i>
                            <i class="fas fa-coffee fa-2x coffee-icon" data-value="5"></i>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="comment" class="form-label">Comment</label>
                    <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
                </div>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-end">
                <button id="rateButton" type="submit" class="btn btn-primary mr-2">Rate</button>
                <a href="<?php echo e(route('features.orders')); ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
        </form>
    </div>
</div>

<script>
    const coffeeIcons = document.querySelectorAll('.coffee-icon');

    coffeeIcons.forEach(icon => {
        icon.addEventListener('click', () => {
            const selectedValue = parseInt(icon.dataset.value);

            // Set the selected rating value in the hidden input field
            document.getElementById('rating').value = selectedValue;

            coffeeIcons.forEach(coffeeIcon => {
                const value = parseInt(coffeeIcon.dataset.value);
                coffeeIcon.classList.toggle('text-warning', value <= selectedValue);
            });
        });
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\billy\Desktop\Laravel-Projects\BeanToBrewV2\resources\views/features/rate.blade.php ENDPATH**/ ?>