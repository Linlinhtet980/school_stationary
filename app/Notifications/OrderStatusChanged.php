<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusChanged extends Notification
{
    use Queueable;

    public $order;

    /**
     * Create a new notification instance.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $status = ucfirst($this->order->status);
        $message = "Your Order #{$this->order->order_number} is now {$status}.";
        
        if ($this->order->status == 'processing') {
            $message = "Your Order #{$this->order->order_number} is now being processed.";
        } elseif ($this->order->status == 'shipped' || $this->order->status == 'out for delivery') {
            $message = "Great news! Your Order #{$this->order->order_number} has been shipped.";
        } elseif ($this->order->status == 'completed' || $this->order->status == 'delivered') {
            $message = "Your Order #{$this->order->order_number} has been completed successfully.";
        } elseif ($this->order->status == 'cancelled') {
            $message = "Your Order #{$this->order->order_number} has been cancelled.";
        }

        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'status' => $this->order->status,
            'message' => $message,
            'type' => 'order_status'
        ];
    }
}
