@component('mail::message')
# Order Delivery Confirmation

Dear {{ $order->name }},

We are pleased to inform you that your order with the following details is now out for delivery:

- **Order ID:** {{ $order->id }} 
- **Item Name:** {{ $cart->item_name }}
- **Quantity:** {{ $cart->quantity }}
- **Shipping Option:** {{ $order->shipping_option == 1 ? 'Standard' : 'Express' }}
- **Total Payment:** {{ $order->total_payment }}

Your order will be delivered to the following address:
{{ $order->address }}

Thank you for shopping with us. If you have any questions or concerns, please feel free to contact us at beantobrew24@gmail.com

Best regards,<br>
Bean to Brew

@endcomponent
