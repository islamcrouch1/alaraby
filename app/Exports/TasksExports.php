<?php

namespace App\Exports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class TasksExports implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */



    use Exportable;

    protected $status, $from, $to;

    public function __construct($status, $from, $to)
    {
        $this->status    = $status;
        $this->from     = $from;
        $this->to     = $to;
    }
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

        ];
    }
    // public function collection()
    // {
    //     return collect(Product::getProducts($this->status, $this->category_id));
    // }
    public function collection()
    {
        return Task::all();
    }
}
