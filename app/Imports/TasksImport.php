<?php

namespace App\Imports;

use App\Models\Central;
use App\Models\Compound;
use App\Models\Task;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Carbon\Carbon;



class TasksImport implements
    WithValidation,
    WithHeadingRow,
    ToCollection,
    SkipsOnError,
    SkipsOnFailure,
    SkipsEmptyRows
{


    use Importable, SkipsErrors, SkipsFailures, RegistersEventListeners;

    public function rules(): array
    {
        return [
            'client_name' => "required|string|max:255",
            'client_phone' => "required|numeric",
            'service_number' => "required|numeric_or_string|unique:tasks",
            'address' => "required|string|max:255",
            'compound' => "required|string",
            'central' => "required|string",
            'tech_name' => "required|string",
            'task_date' => "required|numeric_or_string",
            'type' => "nullable|string",
            'db' => "nullable|numeric",
            'box' => "nullable|string",
            'cab' => "nullable|string",
            'cable_length' => "nullable|numeric",
            'cable_type' => "nullable|string",
            'connectors' => "nullable|string",
            'face_split' => "nullable|numeric",
            'comment' => "nullable|string",


        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function collection(Collection $rows)
    {

        foreach ($rows as $row) {

            $validator = Validator::make($row->toArray(), [
                'client_name' => "required|string|max:255",
                'client_phone' => "required|numeric",
                'service_number' => "required|numeric_or_string|unique:tasks",
                'address' => "required|string|max:255",
                'compound' => "required|string",
                'central' => "required|string",
                'tech_name' => "required|string",
                'task_date' => "required|numeric_or_string",
                'type' => "nullable|numeric_or_string",
                'db' => "nullable|numeric",
                'box' => "nullable|string",
                'cab' => "nullable|string",
                'cable_length' => "nullable|numeric",
                'cable_type' => "nullable|numeric_or_string",
                'connectors' => "nullable|string",
                'face_split' => "nullable|numeric",
                'comment' => "nullable|string",

                
            ])->validate();




            $tech_name = $row['tech_name'];
            $user = User::where('name', 'like', "%$tech_name%")->whereHas('roles', function ($q) {
                $q->where('name', 'tech');
            })->first();


            if (isset($user->id)) {
                $user_id = $user->id;
            } else {
                alertError('technician not exist', 'الفني غير موجود على النظام');
                return redirect()->back();
            }

            $compound = Compound::where('name_ar', $row['compound'])->orWhere('name_en', $row['compound'])->first();
            if (!isset($compound->id)) {
                $compound = Compound::create([
                    'name_ar' => $row['compound'],
                    'name_en' => $row['compound'],
                ]);
            }
            $compound_id = $compound->id;

            $central = Central::where('name_ar', $row['central'])->orWhere('name_en', $row['central'])->first();
            if (!isset($central->id)) {
                $central = Central::create([
                    'name_ar' => $row['central'],
                    'name_en' => $row['central'],
                ]);
            }
            $central_id = $central->id;

            $task_date = Carbon::parse($row['task_date']);
            $task_date = $task_date->toDateTimeString();


            $end_date = Carbon::parse($row['task_date']);
            $end_date = $end_date->addDay();
            $end_date = $end_date->toDateTimeString();


            $task = Task::create([
                'client_name' => $row['client_name'],
                'client_phone' => $row['client_phone'],
                'service_number' => $row['service_number'],
                'address' => $row['address'],
                'compound_id' => $compound_id,
                'user_id' => $user_id,
                'central_id' => $central_id,
                'task_date' => $task_date,
                'end_date' => $end_date,
                'type' => $row['type'],
                'db' => $row['db'],
                'box' =>$row['box'],
                'cab' => $row['cab'],
                'cable_length' => $row['cable_length'],
                'cable_type' => $row['cable_type'],
                'connectors' => $row['connectors'],
                'face_split' =>$row['face_split'],
                'comment' => $row['comment'],

            ]);
        }
    }
}
