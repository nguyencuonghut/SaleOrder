<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\OrdersProducts;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderFinalApproved extends Notification implements ShouldQueue
{
    use Queueable;
    protected $order_id;

    /**
     * Create a new notification instance.
     */
    public function __construct($order_id)
    {
        $this->order_id = $order_id;
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
        $ordersproducts = OrdersProducts::where('order_id', $this->order_id)->where('is_deleted', false)->get();
        $url = url('/orders/'.$order->id);

        return (new MailMessage)
                ->subject('Đơn hàng ' . $ordersproducts->count() . ' sản phẩm, tổng trọng lượng ' . number_format($order->total_weight, 0, '.', ',') . ' KG đã được giám đốc phê duyệt')
                ->line($order->schedule->title . ' :')
                ->line('Đơn hàng ' . $ordersproducts->count() . ' sản phẩm, tổng trọng lượng ' . number_format($order->total_weight, 0, '.', ',') . ' KG đã được giám đốc phê duyệt.')
                ->line('Bạn hãy ấn nút dưới đây để xem chi tiết.')
                ->action('Xem', url($url))
                ->line('Xin cảm ơn!');

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
