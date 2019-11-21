<?php

namespace App\Http\Controllers;

use App\Number;
use App\NumberRecord;
use Illuminate\Http\Request;

class CodeController extends BaseController

{
    public function start()
    {
        $answer = mt_rand(1, 9);
        $create = Number::create([
            'name' => null,
            'min' => 1,
            'max' => 100,
            'answer' => $answer,
        ]);
        return response()->json($create);
    }

    public function play(Request $request)
    {

        try {
            $last = Number::latest()->first();
            $min = Number::latest()->first()->min;
            $max = Number::latest()->first()->max;
            $answer = Number::latest()->first()->answer;
            $request->validate([
                'name' => ['required', 'string'],
                'guess' => ['required', 'integer'],
            ]);

            if ($request['guess'] == $answer) {
                $response['message'] = 'boomb';
                $response['answer'] = $answer;
                NumberRecord::create([
                    'loser' => $request['name'],
                ]);
                return $this->sendResponse($response, 200);

            } else if ($request['guess'] < $answer) {
                $min = $request['guess'];
            } else {
                $max = $request['guess'];
            }
            $response['min'] = $min;
            $response['max'] = $max;
            $last->update([
                'name' => $request['name'],
                'min' => $min,
                'max' => $max,
                'answer' => $answer,
            ]);
            return $this->sendResponse($response, 200);

        } catch (Exception $error) {
            return $this->sendError($error->getMessage(), 400);
        }
    }

}
