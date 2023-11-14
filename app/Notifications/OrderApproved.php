<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderApproved extends Notification implements ShouldQueue
{
    use Queueable;
    protected $order_id;
    protected $level;
    /**
     * Create a new notification instance.
     */
    public function __construct($order_id, $level)
    {
        $this->order_id = $order_id;
        $this->level = $level;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $order = Order::findOrFail($this->order_id);
        $url = url('/orders/'.$order->id);
        return (new MailMessage)
                    ->subject('Kết quả duyệt đơn đặt hàng')
                    ->markdown('email.order.approved', ['order' => $order, 'url' => $url, 'level' => $this->level]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
