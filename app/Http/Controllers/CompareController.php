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
                'name' => ['required', 'string', 'unique:compares'],
            ]);
            $users = Compare::where('isIn', 1)->get();
            $number = count($users->toArray());

            if ($number < 2) {
                $create = Compare::create([
                    'name' => $request['name'],
                    'isIn' => 1,
                    'isRecord' => 0,
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
            $users = Compare::where('isIn', '!=', 0)->get();
            return $this->sendResponse($users, 200);

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

    public function game()
    {
        try {
            $players = Compare::where('answer', '!=', null)->get();
            if (count($players) < 2) {
                $result['winner'] = 'Not yet';
                return $this->sendResponse($result, 200);
            } else {
                if ($players[0]["answer"] > $players[1]["answer"]) {
                    $result['winner'] = $players[0]['name'];
                } else if ($players[0]["answer"] < $players[1]["answer"]) {
                    $result['winner'] = $players[1]['name'];
                } else {
                    $result['winner'] = 'Nobody';
                }
            }
            if (!$players[0]["isRecord"]) {
                Record::create([
                    'winner' => $result['winner'],
                ]);
                for($i=0;$i<2;$i++){
                    $players[$i]->update([
                        'isRecord' => 1,
                    ]);
                }

            }

            $result['compares'] = $players;
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

    public function leave()
    {
        DB::table('compares')->truncate();
        return $this->sendResponse("Leave successfully", 200);
    }
}
