<?php

namespace App\Exports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\FromCollection;

class TasksExports implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    


    use Exportable;

    protected $task, $from, $to;

    public function __construct($task)
    {
        $this->task    = $task;
        
    }

    public function headings(): array
    {
        return [
            'client_name' ,
                'client_phone' ,
                'service_number' ,
                'address' ,
                'compound' ,
                'central' ,
                'tech_name' ,
                'task_date' ,
                'type' ,
                'db' ,
                'box' ,
                'cab' ,
                'cable_length',
                'cable_type' ,
                'connectors' ,
                'face_split' ,
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