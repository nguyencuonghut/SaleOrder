<?php

namespace App\Livewire;

use App\Models\Schedule;
use App\Models\User;
use App\Notifications\ScheduleCreated;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class SchedulesCreate extends Component
{
    public $title;
    public $period;
    public $start_time;
    public $end_time;

    public function mount()
    {
        $this->title = null;
        $this->period = null;
        $this->start_time = null;
        $this->end_time = null;
    }

    public function addSchedule()
    {
        //Validate
        $rules = [
            'title'  => 'required',
            'period'  => 'required',
            'start_time'  => 'required',
            'end_time'  => 'required',
        ];
        $messages = [
            'title.required' => 'Bạn phải nhập tiêu đề.',
            'period.required' => 'Bạn phải chọn kỳ.',
            'start_time.required' => 'Bạn phải nhập thời gian bắt đầu.',
            'end_time.required' => 'Bạn phải nhập thời gian kết thúc.',
        ];

        $this->validate($rules, $messages);
        $schedule = new Schedule();
        $schedule->title = $this->title;
        $schedule->period = $this->period;
        $schedule->start_time = Carbon::createFromFormat('d/m/Y', $this->start_time);
        $schedule->end_time = Carbon::createFromFormat('d/m/Y', $this->end_time);
        $schedule->save();

        //Send email notification
        $users = User::all();
        foreach($users as $user){
            Notification::route('mail' , $user->email)->notify(new ScheduleCreated($schedule->id));
        }

        $this->reset(['title', 'period', 'start_time', 'end_time']);
        Session::flash('success_message', 'Tạo mới thành công!');
        return $this->redirect('/schedules');

    }

    public function render()
    {
        return view('livewire.schedules-create')->layout('layouts.base');
    }
}
