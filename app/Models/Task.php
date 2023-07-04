<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Task extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'client_name', 'activation_date', 'payment_status', 'client_phone', 'service_number', 'address', 'compound_id', 'user_id', 'central_id', 'comment_id', 'task_date', 'end_date', 'box', 'db', 'cab', 'status', 'cable_type', 'cable_length', 'connectors', 'face_split', 'type',
    ];

    public function compound()
    {
        return $this->belongsTo(Compound::class);
    }

    public function central()
    {
        return $this->belongsTo(Central::class);
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function images()
    {
        return $this->hasMany(TaskImage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWhenSearch($query, $search)
    {
        return $query->when($search, function ($q) use ($search) {
            return $q->where('name_ar', 'like', "%$search%")
                ->orWhere('name_en', 'like', "%$search%");
        });
    }

    public function scopeWhenStatus($query, $status)
    {
        return $query->when($status, function ($q) use ($status) {
            return $q->where('Status', 'like', "$status");
        });
    }



    public function scopeWhenCentral($query, $central_id)
    {
        return $query->when($central_id, function ($q) use ($central_id) {
            return $q->where('central_id', 'like', "$central_id");
        });
    }

    public function scopeWhenCompound($query, $compound_id)
    {
        return $query->when($compound_id, function ($q) use ($compound_id) {
            return $q->where('compound_id', 'like', "$compound_id");
        });
    }


    public function scopeWhenComment($query, $comment_id)
    {
        return $query->when($comment_id, function ($q) use ($comment_id) {
            return $q->where('comment_id', 'like', "$comment_id");
        });
    }


    public function scopeWhenPaymentStatus($query, $payment_status)
    {
        return $query->when($payment_status, function ($q) use ($payment_status) {
            return $q->where('payment_status', $payment_status);
        });
    }


    public static function getTasks($status = null, $payment_status = null, $from = null, $to = null, $activation_from = null, $activation_to = null)
    {


        $tasks = self::select('client_name', 'client_phone', 'service_number', 'address', 'compound_id', 'central_id', 'user_id', 'task_date', 'type', 'db', 'box', 'cab', 'cable_length', 'cable_type', 'connectors', 'face_split', 'comment_id', 'status', 'activation_date', 'payment_status')
            ->whereDate('task_date', '>=', $from)
            ->whereDate('task_date', '<=', $to)
            ->where(function ($q) use ($activation_from) {
                $q->whereDate('activation_date', '>=', $activation_from)
                    ->orWhereNull('activation_date');
            })
            ->where(function ($q) use ($activation_to) {
                $q->whereDate('activation_date', '<=', $activation_to)
                    ->orWhereNull('activation_date');
            })
            ->whenStatus($status)
            ->whenPaymentStatus($payment_status)
            ->latest()
            ->get()
            ->toArray();


        foreach ($tasks as $index => $task) {

            if ($task['compound_id'] != null) {
                $compound = Compound::findOrFail($task['compound_id']);
                $compound = getName($compound);
                $tasks[$index]['compound_id'] = $compound;
            }

            if ($task['central_id'] != null) {
                $central = Central::findOrFail($task['central_id']);
                $central = getName($central);
                $tasks[$index]['central_id'] = $central;
            }

            if ($task['user_id'] != null) {
                $user = User::findOrFail($task['user_id']);
                $user = $user->name;
                $tasks[$index]['user_id'] = $user;
            }

            if ($task['comment_id'] != null) {
                $comment = Comment::findOrFail($task['comment_id']);
                $comment = getName($comment);
                $tasks[$index]['comment_id'] = $comment;
            }


            if ($task['payment_status'] != null) {
                $status = $task['payment_status'] == '2' ? __('unpaid') : __('paid');
                $tasks[$index]['payment_status'] = $status;
            }
        }


        return $tasks;
    }
}
