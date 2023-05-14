<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class centralModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name_en', 'name_ar' , 'country_id', 'parent_id'
    ];

    // public function products()
    // {
    //     // return $this->belongsToMany(Product::class);
    // }

    // public function Centrals()
    // {
    //     return Central::where('parent_id', $this->id)->get();
    // }

    // public function children()
    // {
    //     return $this->hasMany(self::class, 'parent_id', 'id');
    // }

    // public function parent()
    // {
    //     return $this->belongsTo(self::class, 'parent_id');
    // }

    // public function country()
    // {
    //     return $this->belongsTo(Country::class);
    // }

    public function scopeWhenSearch($query, $search)
    {
        return $query->when($search, function ($q) use ($search) {
            return $q->where('name_ar', 'like', "%$search%")
                ->orWhere('name_en', 'like', "%$search%");
        });
    }

    public function scopeWhenCountry($query, $country_id)
    {
        return $query->when($country_id, function ($q) use ($country_id) {
            return $q->where('country_id', 'like', "%$country_id%");
        });
    }

    public function scopeWhenParent($query, $parent)
    {
        if($parent == null){
            return $query->when(function ($q){
                return $q->whereNull('parent_id');
            });
        }else{
            return $query->when($parent, function ($q) use ($parent) {
                return $q->where('parent_id', 'like', "$parent");
            });
        }
    }
}


