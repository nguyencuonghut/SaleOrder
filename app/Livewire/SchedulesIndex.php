<?php

namespace App\Livewire;

use App\Models\Schedule;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Component;

class SchedulesIndex extends Component
{
    public $search;
    public $sortField;
    public $sortAsc;
    public $editScheduleIndex;
    public $deletedScheduleIndex;

    public function mount()
    {
        $this->search = '';
        $this->sortField = 'id';
        $this->sortAsc = false;
        $this->editScheduleIndex = null;
        $this->deletedScheduleIndex = null;
    }


    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = ! $this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function cancel()
    {
        $this->reset('deletedScheduleIndex');
        $this->resetErrorBag();
    }

    public function confirmDestroy($id)
    {
        $this->deletedScheduleIndex = $id;
    }

    public function destroy()
    {
        // Destroy schedule
        $schedule = Schedule::findOrFail($this->deletedScheduleIndex);
        $schedule->destroy($this->deletedScheduleIndex);

        $this->reset('deletedScheduleIndex');
        Session::flash('success_message', 'Xóa thành công!');
    }

    public function render()
    {
        $schedules = Schedule::where('id', 'like', '%'.$this->search.'%')
                                ->orWhere('title', 'like', '%'.$this->search.'%')
                                ->orWhere('period', 'like', '%'.$this->search.'%')
                                ->orWhere('start_time', 'like', '%'.$this->search.'%')
                                ->orWhere('end_time', 'like', '%'.$this->search.'%')
                                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                                ->get();

        return view('livewire.schedules-index', ['schedules' => $schedules])->layout('layouts.base');
    }
}
