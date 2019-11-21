<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compare extends Model
{
    protected $fillable = [
      'name', 'answer', 'isIn' ,'isRecord'
    ];
    function getAnswer($name){
        $answer = $this->where('name', $name)->first()->answer;
        return $answer;
    }
}
