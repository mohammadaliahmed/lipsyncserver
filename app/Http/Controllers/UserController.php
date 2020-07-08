<?php

namespace App\Http\Controllers;

use App\Constants;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //
    public function registerTempUser(Request $request)
    {
        if ($request->api_username != Constants::$API_USERNAME || $request->api_password != Constants::$API_PASSOWRD) {
            return response()->json([
                'code' => Response::HTTP_FORBIDDEN, 'message' => "Wrong api credentials"
            ], Response::HTTP_OK);
        } else {
            $milliseconds = round(microtime(true) * 1000);

            $tempuser = null;
            $user = new User();
            $user->name = $request->name;
            $user->username = $request->username;
            $user->password = $request->username;
            $user->email = $request->email;
            $user->time = $milliseconds;
            $user->save();
            $tempuser = User::find($user->id);

            return response()->json([
                'code' => 200, 'message' => "false", 'tempUser' => $tempuser
                ,
            ], Response::HTTP_OK);
        }
    }

    public function loginUser(Request $request)
    {
        if ($request->api_username != Constants::$API_USERNAME || $request->api_password != Constants::$API_PASSOWRD) {
            return response()->json([
                'code' => Response::HTTP_FORBIDDEN, 'message' => "Wrong api credentials"
            ], Response::HTTP_OK);
        } else {
            $milliseconds = round(microtime(true) * 1000);

            $user = DB::table('users')
                ->where('email', $request->email)
                ->first();
            if ($user == null) {
                $user = User::find($request->user_id);
                $user->name = $request->name;
                $user->email = $request->email;
                $user->pic_url = $request->pic_url;
                $user->username = $request->username;
                $user->time = $milliseconds;
                $user->update();
            }
            return response()->json([
                'code' => 200, 'message' => "false", 'user' => $user
                ,
            ], Response::HTTP_OK);
        }
    }
}
