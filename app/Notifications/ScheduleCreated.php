<?php

namespace App\Notifications;

use App\Models\Schedule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ScheduleCreated extends Notification implements ShouldQueue
{
    use Queueable;
    protected $schedule_id;

    /**
     * Create a new notification instance.
     */
    public function __construct($schedule_id)
    {
        $this->schedule_id = $schedule_id;
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
        $schedule = Schedule::findOrFail($this->schedule_id);
        return (new MailMessage)
                    ->subject($schedule->title)
                    ->line('Xin mời anh/chị tạo đơn hàng cho kế hoạch: ' . $schedule->title . '.')
                    ->line('Thời gian: ' . $schedule->start_time . ' đến ' . $schedule->end_time . '.')
                    ->action('Đặt hàng', url('/'))
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
