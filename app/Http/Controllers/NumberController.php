<?php

namespace App\Http\Controllers;

use App\Compare;
use Illuminate\Http\Request;

class NumberController extends BaseController

{
    protected $min = 1;
    protected $max = 100;
    protected $answer = 100;

    public function play(Request $request)
    {
        $answer =mt_rand(1, 100);

        try {

            $request->validate([
                'name' => ['required', 'string'],
                'guess' => ['required', 'integer'],
            ]);

            if($request['guess'] > $answer){
                $max = $request['guess'];
                $response['min'] = $min;
                $response['max'] = $max;
                return $this->sendResponse($response, 200);
            }
            return response()->json("send successfully.");

        } catch (Exception $error) {
            return $this->sendError($error->getMessage(), 400);
        }
    }
}
