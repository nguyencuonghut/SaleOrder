<?php

namespace App\Livewire;

use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class SchedulesEdit extends Component
{
    public $scheduleId;
    public $title;
    public $period;
    public $start_time;
    public $end_time;

    public function mount($id)
    {
        $schedule = Schedule::findOrFail($id);
        $this->scheduleId = $schedule->id;
        $this->title = $schedule->title;
        $this->period = $schedule->period;
        $this->start_time = $schedule->start_time;
        $this->end_time = $schedule->end_time;
    }

    public function saveSchedule()
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
        $schedule = Schedule::findOrFail($this->scheduleId);
        $schedule->title = $this->title;
        $schedule->period = $this->period;
        $schedule->start_time = Carbon::createFromFormat('d/m/Y', $this->start_time);
        $schedule->end_time = Carbon::createFromFormat('d/m/Y', $this->end_time);
        $schedule->save();

        $this->reset(['scheduleId', 'title', 'period', 'start_time', 'end_time']);
        Session::flash('success_message', 'Sửa thành công!');
        return $this->redirect('/schedules', navigate: true);
    }

    public function render()
    {
        return view('livewire.schedules-edit')->layout('layouts.base');
    }
}
