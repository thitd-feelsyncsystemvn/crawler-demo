<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = "page";
    
    public $timestamps = false;

    protected $fillable = ['id','keyword_id','title','link','meta_description','total_link','number_link'];
}
