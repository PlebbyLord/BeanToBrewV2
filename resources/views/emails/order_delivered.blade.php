@component('mail::message')
# Order Confirmation

Dear Bean to Brew,

We are pleased to inform you that Order: {{ $order->id }} with the following details has been recieved by the buyer:

- **Order ID:** {{ $order->id }} 
- **Item Name:** {{ $cart->item_name }}
- **Quantity:** {{ $cart->quantity }}
- **Shipping Option:** {{ $order->shipping_option == 1 ? 'Standard' : 'Express' }}
- **Total Payment:** {{ $order->total_payment }}
- **Address:** {{ $order->address }}

Best regards,<br>
Bean to Brew
@endcomponent
