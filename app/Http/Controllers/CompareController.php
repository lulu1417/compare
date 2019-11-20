<?php

namespace App\Http\Controllers;

use App\Compare;
use App\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompareController extends BaseController
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string','unique:compares'],
            ]);
            $users = Compare::where('isIn', 1)->get();
            $number = count($users->toArray());
            if ($number < 2) {
                $create = Compare::create([
                    'name' => $request['name'],
                    'isIn' => 1,
                ]);
                if ($create) {
                    return response()->json("Enter the room.");
                }
            } else {
                return response()->json("The room is full.");
            }

        } catch (Exception $error) {
            return $this->sendError($error->getMessage(), 400);
        }

    }

    public function room()
    {
        try {
            $users = Compare::where('isIn', 1)->get();
            $number = count($users->toArray());
            return $this->sendResponse($number, 200);

        } catch (Exception $error) {
            return $this->sendError($error->getMessage(), 400);
        }
    }

    public function send(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string'],
                'answer' => ['required', 'integer'],
            ]);

            $user = Compare::where('name', $request['name'])->get()->first();
            $user->update([
                'name' => $request['name'],
                'answer' => $request['answer'],
            ]);

            return response()->json("Answer send successfully.");

        } catch (Exception $error) {
            return $this->sendError($error->getMessage(), 400);
        }
    }

    public function game(Request $request)
    {
        try {

            $a_answer = Compare::where('id', 1)->get()->first()->answer;
            $b_answer = Compare::where('id', 2)->get()->first()->answer;
            if (($a_answer !==null) && ($b_answer!==null)) {
                if ($a_answer > $b_answer) {
                    DB::table('compares')->truncate();
                    $result['winner'] = Compare::where('id', 1)->get()->first()->name;
                } else if ($a_answer < $b_answer) {
                    $result['winner'] = Compare::where('id', 2)->get()->first()->name;
                    DB::table('compares')->truncate();

                } else{
                    DB::table('compares')->truncate();
                    $result['winner'] = 'Nobody';
                }
                Record::create([
                    'winner' => $result['winner'],
                ]);
            }else{
                $result['winner'] = 'Not ysss';
            }

            $result['compares'] = Compare::all()->toArray();
            return $this->sendResponse($result, 200);


        } catch
        (Exception $error) {
            return $this->sendError($error->getMessage(), 400);
        }
    }
    public function record()
    {
        try {
           $result = Record::all();
            return $this->sendResponse($result, 200);

        } catch (Exception $error) {
            return $this->sendError($error->getMessage(), 400);
        }
    }
}
