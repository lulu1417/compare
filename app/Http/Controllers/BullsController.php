<?php

namespace App\Http\Controllers;

use App\Bull;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class BullsController extends BaseController
{


    public function start()
    {
        try {
            DB::table('bulls')->truncate();
            for ($i = 0; $i < 4; $i++) {
                $answer[$i] = mt_rand(0, 9);
            }
            $answer = implode($answer);
            $create = Bull::create([
                'name' => null,
                'hint' => null,
                'guess' => null,
                'answer' => $answer,
            ]);
            return $this->sendResponse("Game started.", 200);
        } catch (Exception $error) {
            return $this->sendError($error->getMessage(), 400);
        }

    }

    public function play(Request $request)
    {
        try {
            $startTime = Bull::find(1)->created_at->toArray()["timestamp"];

            $request->validate([
                'name' => ['required'],
                'guess' => ['required', 'string', 'min:4', 'max:4']
            ]);
            $guess = $request['guess'];
            $answer = Bull::where('answer', '!=', null)->first()->answer;
            $hint = Bull::check($guess, "$answer");
            Bull::create([
                'name' => $request['name'],
                'hint' => $hint,
                'guess' => $guess,
                'answer' => $answer,
            ]);
            if ($hint == "Answer Correct") {
                $endTime = Bull::latest()->first()->created_at->toArray()["timestamp"];
                $totalTime = $endTime - $startTime;
                if ($totalTime > 60) {
                    $spentMin = (int)(($endTime - $startTime) / 60);
                } else {
                    $spentMin = 0;
                }
                $spentSec = ($endTime - $startTime) % 60;
                $totalTime = "$spentMin. $spentSec";
                return $totalTime;
            }
            return Bull::all();
        } catch (Exception $error) {
            return $this->sendError($error->getMessage(), 400);
        }


    }



}
