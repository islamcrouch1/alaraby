<?php

namespace App\Imports;

use App\Models\Central;
use App\Models\Comment;
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
            'central' => "required|string",
            'compound' => "required|string",
            'client_phone' => "required|numeric",
            'service_number' => "required|numeric_or_string|unique:tasks",
            'client_name' => "required|string|max:255",
            'address' => "required|string|max:255",
            // 'task_date' => "required|numeric_or_string",
            'cab' => "nullable|numeric_or_string",
            'box' => "nullable|numeric_or_string",
            'db' => "nullable|numeric",
            'type' => "nullable|string",
            'comment' => "nullable|string",
            'connectors' => "nullable|numeric_or_string",
            'cable_length' => "nullable|numeric",
            'cable_type' => "nullable|string",
            'face_split' => "nullable|numeric",
            'tech_name' => "required|string",

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
                'central' => "required|string",
            'compound' => "required|string",
            'client_phone' => "required|numeric",
            'service_number' => "required|numeric_or_string|unique:tasks",
            'client_name' => "required|string|max:255",
            'address' => "required|string|max:255",
            // 'task_date' => "required|numeric_or_string",
            'cab' => "nullable|numeric_or_string",
            'box' => "nullable|numeric_or_string",
            'db' => "nullable|numeric",
            'type' => "nullable|string",
            'comment' => "nullable|string",
            'connectors' => "nullable|numeric_or_string",
            'cable_length' => "nullable|numeric",
            'cable_type' => "nullable|string",
            'face_split' => "nullable|numeric",
            'tech_name' => "required|string",

            ])->validate();




            $tech_name = $row['tech_name'];
            $user = User::where('name', 'like', "%$tech_name%")->whereHas('roles', function ($q) {
                $q->where('name', 'tech');
            })->first();


            if (isset($user->id)) {
                $user_id = $user->id;
            } else {
                alertError('technician not exist' . ' - ' . $tech_name, 'الفني غير موجود على النظام' . ' - ' . $tech_name);
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


            if (isset($row['comment'])) {
                $comment = Comment::where('name_ar', $row['comment'])->orWhere('name_en', $row['comment'])->first();
                if (!isset($comment->id)) {
                    $comment = Comment::create([
                        'name_ar' => $row['comment'],
                        'name_en' => $row['comment'],
                    ]);
                }
                $comment_id = $comment->id;
            } else {
                $comment_id = null;
            }




            $task_date = Carbon::now();
            $task_date = $task_date->toDateTimeString();


            $end_date = Carbon::now();
            $end_date = $end_date->addDay();
            $end_date = $end_date->toDateTimeString();


            $task = Task::create([
                'central_id' => $central_id,
                'compound_id' => $compound_id,
                'client_phone' => $row['client_phone'],
                'service_number' => $row['service_number'],
                'client_name' => $row['client_name'],
                'address' => $row['address'],
                'user_id' => $user_id,
                'task_date' => $task_date,
                'end_date' => $end_date,
                'type' => isset($row['type']) ? $row['type'] : null,
                'db' => isset($row['db']) ? $row['db'] : null,
                'box' => isset($row['box']) ? $row['box'] : null,
                'cab' => isset($row['cab']) ? $row['cab'] : null,
                'cable_length' => isset($row['cable_length']) ? $row['cable_length'] : null,
                'cable_type' => isset($row['cable_type']) ? $row['cable_type'] : null,
                'connectors' => isset($row['connectors']) ? $row['connectors'] : null,
                'face_split' => isset($row['face_split']) ? $row['face_split'] : null,

                'comment_id' => $comment_id,

            ]);
        }
    }
}
