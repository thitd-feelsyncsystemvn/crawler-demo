<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Google_KeyWord extends Model
{
    protected $table = "key_word";
    public $timestamps = false;	

    protected $fillable = ['id','word','status','input_date','total_link','number_link'];
}
