<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'New Order #' . $this->order->order_number . ' received.',
            'link' => route('admin.orders.show', $this->order->id),
            'icon' => 'fa-solid fa-cart-shopping',
            'icon_bg' => 'bg-primary'
        ];
    }
}
