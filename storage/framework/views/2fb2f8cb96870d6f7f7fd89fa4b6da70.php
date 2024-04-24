<?php $__env->startComponent('mail::message'); ?>
# Order Confirmation

Dear Bean to Brew,

We are pleased to inform you that Order: <?php echo e($order->id); ?> with the following details has been recieved by the buyer:

- **Order ID:** <?php echo e($order->id); ?> 
- **Item Name:** <?php echo e($cart->item_name); ?>

- **Quantity:** <?php echo e($cart->quantity); ?>

- **Shipping Option:** <?php echo e($order->shipping_option == 1 ? 'Standard' : 'Express'); ?>

- **Total Payment:** <?php echo e($order->total_payment); ?>

- **Address:** <?php echo e($order->address); ?>


Best regards,<br>
Bean to Brew
<?php echo $__env->renderComponent(); ?>
<?php /**PATH C:\Users\billy\Desktop\Laravel-Projects\BeanToBrewV2\resources\views/emails/order_delivered.blade.php ENDPATH**/ ?>