<?php

namespace App\Mail;

use App\Models\Cart;
use App\Models\Orders;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderDelivered extends Mailable
{
    use Queueable, SerializesModels;

    public $cart;
    public $order;

    /**
     * Create a new message instance.
     *
     * @param Cart $cart
     * @param Orders $order
     * @return void
     */
    public function __construct(Cart $cart, Orders $order)
    {
        $this->cart = $cart;
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('beantobrew24@gmail.com', 'Bean to Brew')
                    ->markdown('emails.order_delivered');
    }
}
