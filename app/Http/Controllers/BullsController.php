<?php

namespace App\Http\Controllers;

use App\Number;
use Illuminate\Http\Request;

class BullsController extends BaseController
{
    public function start()
    {
        try{
            for($i=0;$i<4;$i++){
                $answer[$i] = mt_rand(0, 10);
            }
            $answer = implode($answer);
            $create = Number::create([
                'name' => null,
                'hint' => null,
                'guess' => null,
                'answer' => $answer,
            ]);
            return response()->json($create);
        }catch (Exception $error){
            return $this->sendError($error->getMessage(), 400);
        }

    }
    public function play(Request $request)
    {

    }
}
