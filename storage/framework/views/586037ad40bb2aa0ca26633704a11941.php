<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .item-name-header {
            width: 50%; /* Adjust the width as needed */
        }

        .price-header {
            width: 20%; /* Adjust the width as needed */
        }

        .quantity-header {
            width: 15%; /* Adjust the width as needed */
        }

        .total-price-header {
            width: 15%; /* Adjust the width as needed */
        }
    </style>
</head>
<body>
    <div>
        <h1>Bean to Brew</h1>
        <h3>Receipt</h3>
        <p>Date: <?php echo e(date('m/d/Y (D) (H:i:s)')); ?></p>
        <p>Staff: <?php echo e(auth()->user()->branch); ?> Branch</p>
        <p>Bataan Peninsula State University</p>
        <p>Owned by SD4A Group 4 the Brownies</p>
        <p>Tel #: NULL</p>
        
        <table>
            <thead>
                <tr>
                    <th class="item-name-header">Item Name</th>
                    <th class="price-header">Price</th>
                    <th class="quantity-header">Quantity</th>
                    <th class="total-price-header">Total Price</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $tempCashes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tempCash): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($tempCash->item_name); ?></td>
                    <td><?php echo e($tempCash->item_price); ?></td>
                    <td><?php echo e($tempCash->quantity); ?></td>
                    <td><?php echo e($tempCash->item_price * $tempCash->quantity); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <p>Total Sale: <?php echo e($totalSale); ?></p>
        <p>Paid Amount: <?php echo e($paidAmount); ?></p>
        <p>Change: <?php echo e($change); ?></p>
    </div>
</body>
</html>

<script>
    // Wait for the PDF to load, then trigger print
    window.addEventListener('DOMContentLoaded', (event) => {
        window.print(); // Automatically trigger print when PDF is loaded
    });
</script>
<?php /**PATH C:\Users\billy\Desktop\Laravel-Projects\BeanToBrewV2\resources\views/components/receipt.blade.php ENDPATH**/ ?>