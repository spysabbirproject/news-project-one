<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use HasFactory;

    use SoftDeletes;
    protected $guarded = [];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'news_tag');
    }

    function relationtocategory(){
        return $this->hasOne(Category::class, 'id', 'news_category_id');
    }

    function relationtouser(){
        return $this->hasOne(Admin::class, 'id', 'created_by');
    }

    function relationtocountry(){
        return $this->hasOne(Country::class, 'id', 'country_id');
    }
    function relationtodivision(){
        return $this->hasOne(Division::class, 'id', 'division_id');
    }
    function relationtodistrict(){
        return $this->hasOne(District::class, 'id', 'district_id');
    }
    function relationtoupazila(){
        return $this->hasOne(Upazila::class, 'id', 'upazila_id');
    }
    function relationtounion(){
        return $this->hasOne(Union::class, 'id', 'union_id');
    }
}
