<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'label_id',
        'ver_index',
        'name',
        'version',
        'start_date',
        'end_date',
        'due_date',
        'progress',
        'points',
        'initial_condition',
        'procedure',
        'expected_result'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function label(){
        return $this->belongsTo('App\Label');
    }
}
