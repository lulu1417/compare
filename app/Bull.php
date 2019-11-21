<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bull extends Model
{
    protected $fillable = [
      'name', 'guess', 'hint', 'answer'
    ];
    protected $hidden = [
        'answer', 'created_at', 'updated_at'
    ];
    static function check($guess, $answer)
    {
        try {
            $MAX_LENGTH = 4;

            $guess = str_split($guess);
            $answer = str_split($answer);
            $A = 0;
            $ans_appear_times = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0] ;
            $guess_appear_times = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0] ;

            //calculate A
            for ($i = 0; $i < $MAX_LENGTH; $i++) {
                if ($guess[$i] == $answer[$i]) {
                    $A++;
                } else {
                    $ans_appear_times[$answer[$i]]++;
                    $guess_appear_times[$guess[$i]]++;
                }
            }
            //calculate B
            $B = 0;
            for ($i = 0; $i < 10; $i++) {
                if ($guess_appear_times[$i] >= $ans_appear_times[$i]) {
                    $B += $ans_appear_times[$i];
                } else {
                    $B += $guess_appear_times[$i];
                }
            }
            if ($A == $MAX_LENGTH && $B == 0) {
                return "Answer Correct";
            }
            $hint = $A . "A" . $B . "B";
            return $hint;

        } catch (Exception $error) {
            return response()->json($error, 400);
        }
    }
}
