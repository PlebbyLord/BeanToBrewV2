<?php $__env->startSection('content'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bean to Brew</title>
</head>
<body>
    <div id="filterCard">
        <form id="filterForm">
            <div>
                <label for="coffee_type">Filter by Coffee Type:</label><br>
                <input type="checkbox" id="all" name="coffee_type[]" value="all">
                <label for="all">All</label><br>
                <input type="checkbox" id="green_coffee" name="coffee_type[]" value="green">
                <label for="green_coffee">Green Coffee Beans</label><br>
                <input type="checkbox" id="roasted_coffee" name="coffee_type[]" value="roasted">
                <label for="roasted_coffee">Roasted Coffee Beans</label><br>
                <input type="checkbox" id="grinded_coffee" name="coffee_type[]" value="grinded">
                <label for="grinded_coffee">Grinded Coffee Beans</label><br>
            </div>
        </form>
    </div>

    <div id="mainContent" style="margin-left: 240px; /* Adjust margin-left to accommodate the filter card width */">
        <div id="itemsContainer">
            <?php $__currentLoopData = $userItems->take(1); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $buy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="item <?php echo e($buy->coffee_type); ?>">
                    <div class="col" style="margin-bottom: 20px;"> 
                        <p class="Name text-center" style="font-size: 40px; text-decoration: underline; font-weight: bold; margin-left: 470px; color: rgb(0, 0, 0); margin-top: 50px; font-family: 'Monotype Corsiva', cursive; background-color: rgba(255, 255, 255, 0.8); padding: 10px; display: inline;">
                            Bean To Brew Shop
                        </p>                                                                                   
                        <?php echo $__env->make("components.cardcolumn", ['buy' => $buy], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <script>
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                if (checkbox.id === "all" && checkbox.checked) {
                    window.location.href = '<?php echo e(route("features.purchase")); ?>';
                } else {
                    const selectedTypes = Array.from(document.querySelectorAll('input[type="checkbox"]:checked')).map(checkbox => checkbox.value);
                    const urlParams = new URLSearchParams(window.location.search);
                    
                    // Remove existing coffee type parameters
                    urlParams.delete('coffee_type[]');

                    // Append the selected coffee types
                    selectedTypes.forEach(type => {
                        urlParams.append('coffee_type[]', type);
                    });

                    // Reload the page with updated URL parameters
                    window.location.href = '?' + urlParams.toString();
                }
            });
        });
    </script>
</body>
</html>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\billy\Desktop\Laravel-Projects\BeanToBrewV2\resources\views/Features/purchase.blade.php ENDPATH**/ ?>