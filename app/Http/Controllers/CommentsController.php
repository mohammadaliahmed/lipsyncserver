<?php

namespace App\Http\Controllers;

use App\Comments;
use App\Constants;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CommentsController extends Controller
{
    //

    public function getVideoComments(Request $request)
    {
        if ($request->api_username != Constants::$API_USERNAME && $request->api_password != Constants::$API_PASSOWRD) {
            return response()->json([
                'code' => Response::HTTP_FORBIDDEN, 'message' => "Wrong api credentials"
            ], Response::HTTP_OK);
        } else {

            $comments = DB::table('comments')->where('video_id', $request->video_id)
                ->orderBy('id', 'DESC')->limit(100)->get();
            foreach ($comments as $comment) {
                $comment->user = User::find($comment->user_id);
            }
            return response()->json([
                'code' => Response::HTTP_OK, 'message' => "false", 'comments' => $comments
            ], Response::HTTP_OK);
        }
    }

    public function postComment(Request $request)
    {
        if ($request->api_username != Constants::$API_USERNAME && $request->api_password != Constants::$API_PASSOWRD) {
            return response()->json([
                'code' => Response::HTTP_FORBIDDEN, 'message' => "Wrong api credentials"
            ], Response::HTTP_OK);
        } else {
            $milliseconds = round(microtime(true) * 1000);

            $comment = new Comments();
            $comment->video_id = $request->video_id;
            $comment->user_id = $request->user_id;
            $comment->content = $request->comment;
            $comment->time = $milliseconds;
            $comment->video_id = $request->video_id;
            $comment->save();
            return response()->json([
                'code' => Response::HTTP_OK, 'message' => "false"
            ], Response::HTTP_OK);
        }
    }


}
