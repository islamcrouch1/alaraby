<?php

namespace App\Exports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class TasksExports implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */



    use Exportable;

    protected $status, $from, $to, $payment_status, $activation_from, $activation_to;

    public function __construct($status, $payment_status, $from, $to, $activation_from, $activation_to)
    {
        $this->status    = $status;
        $this->payment_status    = $payment_status;
        $this->from     = $from;
        $this->to     = $to;
        $this->activation_from = $activation_from;
        $this->activation_to = $activation_to;
    }


    public function headings(): array
    {
        return [
            'client_name',
            'client_phone',
            'service_number',
            'address',
            'compound',
            'central',
            'tech_name',
            'task_date',
            'type',
            'db',
            'box',
            'cab',
            'cable_length',
            'cable_type',
            'connectors',
            'face_split',
            'comment',
            'status',
            'activation_date',
            'payment_status',
        ];
    }
    public function collection()
    {
        return collect(Task::getTasks($this->status, $this->payment_status, $this->from, $this->to, $this->activation_from, $this->activation_to));
    }
}
