<?php

namespace App\Http\Controllers;

use App\Constants;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class SoundsController extends Controller
{
    //

    public function getAllSounds(Request $request)
    {
        if ($request->api_username != Constants::$API_USERNAME && $request->api_password != Constants::$API_PASSOWRD) {
            return response()->json([
                'code' => Response::HTTP_FORBIDDEN, 'message' => "Wrong api credentials"
            ], Response::HTTP_OK);
        } else {
            $sounds = DB::table('sounds')->orderBy('id', 'desc')->limit(200)->get();
            return response()->json([
                'code' => Response::HTTP_OK, 'message' => "false", 'sounds' => $sounds

                ,
            ], Response::HTTP_OK);
        }

    }
}
