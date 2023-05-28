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
        'client_name', 'client_phone', 'service_number', 'address', 'compound_id', 'user_id', 'central_id', 'comment_id', 'task_date', 'end_date','box','db','cab','status','cable_type','cable_length','connectors','face_split','type',
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
}
