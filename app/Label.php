<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    protected $fillable = [
        'name',
        'hor_index',
        'txt_color',
        'bg_color'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function task(){
        return $this->hasMany('App\Task')->orderBy('ver_index', 'asc');
    }
}
