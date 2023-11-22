<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\OrdersProducts;
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
        $ordersproducts = OrdersProducts::where('order_id', $this->order_id)->where('is_deleted', false)->get();
        $url = url('/orders/'.$order->id);
        return (new MailMessage)
                    ->subject('Kết quả duyệt đơn đặt hàng ' . $ordersproducts->count() . ' sản phẩm, tổng trọng lượng ' . number_format($order->total_weight, 0, '.', ',') . ' KG')
                    ->markdown('email.order.approved',
                                ['order' => $order,
                                 'order_products_cnt' => $ordersproducts->count(),
                                 'url' => $url, 'level' => $this->level
                                ]);
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
