<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class article extends Model
{
    protected $table='article';
    protected $primaryKey='art_id';
    public $timestamps=false;
    protected $guarded=[];
}
