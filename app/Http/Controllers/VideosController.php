<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Sounds;
use App\User;
use App\Videos;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class VideosController extends Controller
{
    //


    public function uploadFile(Request $request)
    {
        $milliseconds = round(microtime(true) * 1000);
        if ($request->has('photo')) {
            $file_name = $milliseconds . '.jpg';
            $path = $request->file('photo')->move(public_path("/images"), $file_name);
            $photo_url = url('/images/' . $file_name);
            echo $file_name;
//            return response()->json([
//                'code' => Response::HTTP_OK, 'message' => "false", 'url' => $file_name
//                ,
//            ], Response::HTTP_OK);
        } else if ($request->has('audio')) {
            $file_name = $milliseconds . '.mp3';
            $path = $request->file('audio')->move(public_path("/sounds"), $file_name);
            echo $file_name;
//            return response()->json([
//                'code' => Response::HTTP_OK, 'message' => "false", 'url' => $file_name
//                ,
//            ], Response::HTTP_OK);
        } else if ($request->has('video')) {
            $file_name = $milliseconds . '.mp4';
            $path = $request->file('video')->move(public_path("/videos"), $file_name);
            $photo_url = url('/videos/' . $file_name);
            echo $file_name;
        } else {
            return response()->json([
                'code' => 401, 'message' => "false", 'url' => "sdfsdfsd"
                ,
            ], 401);

        }
    }

    public function saveVideoToServer(Request $request)
    {
        if ($request->api_username != Constants::$API_USERNAME && $request->api_password != Constants::$API_PASSOWRD) {
            return response()->json([
                'code' => Response::HTTP_FORBIDDEN, 'message' => "Wrong api credentials"
            ], Response::HTTP_OK);
        } else {
            $video = new Videos();
            $video->url = $request->url;
            $video->pic_url = $request->pic_url;
            $video->user_id = $request->user_id;
            if ($request->sound_id == -1) {
                $sound = new Sounds();
                $sound->url = $request->sound_url;
                $sound->title = $request->sound_name;
                $sound->subtitle = "";
                $sound->duration = "0:15";
                $sound->save();
                $video->sound_id = $sound->id;
            } else {
                $video->sound_id = $request->sound_id;
            }
            $video->status = $request->status;
            $video->time = $request->time;
            $video->content = $request->description;

            $video->save();

            return response()->json([
                'code' => 200, 'message' => "false"
                ,
            ], 200);
        }

    }

    protected function getRecommendedVideos(Request $request)
    {
        if ($request->api_username != Constants::$API_USERNAME && $request->api_password != Constants::$API_PASSOWRD) {
            return response()->json([
                'code' => Response::HTTP_FORBIDDEN, 'message' => "Wrong api credentials"
            ], Response::HTTP_OK);
        } else {

            $videos = DB::table('videos')->orderBy('id', 'DESC')->get();
            foreach ($videos as $video) {
                $comment = DB::table('comments')->where('video_id', $video->id)->count();
                $video->commentCount = $comment;
                $video->likeCount = 10;
                $video->shareCount = 15;
                $user = User::find($video->user_id);
                if ($video->sound_id != -1) {

                    $sound = DB::table('sounds')->where('id', $video->sound_id)
                        ->select("title")->limit(1)->pluck('title');
                    $video->soundName = $sound[0];
                } else {
                    $video->soundName = 'Original sound by ' . $user->name;
                }
                $video->user = $user;
            }
            return response()->json([
                'code' => 200, 'message' => "false", 'videos' => $videos
                ,
            ], 200);
        }
    }

    protected
    function getUserVideos(Request $request)
    {
        if ($request->api_username != Constants::$API_USERNAME && $request->api_password != Constants::$API_PASSOWRD) {
            return response()->json([
                'code' => Response::HTTP_FORBIDDEN, 'message' => "Wrong api credentials"
            ], Response::HTTP_OK);
        } else {

            $videos = DB::table('videos')->where('user_id', $request->user_id)->orderBy('id', 'DESC')->get();
            foreach ($videos as $video) {
                $comment = DB::table('comments')->where('video_id', $video->id)->count();
                $video->commentCount = $comment;
                $video->likeCount = 10;
                $video->shareCount = 15;
                $user = User::find($video->user_id);
                if ($video->sound_id != -1) {

                    $sound = DB::table('sounds')->where('id', $video->sound_id)
                        ->select("title")->limit(1)->pluck('title');
                    $video->soundName = $sound[0];
                } else {
                    $video->soundName = 'Original sound by ' . $user->name;
                }
                $video->user = $user;
            }
            return response()->json([
                'code' => 200, 'message' => "false", 'videos' => $videos
                ,
            ], 200);
        }
    }

    protected
    function getSoundVideos(Request $request)
    {
        if ($request->api_username != Constants::$API_USERNAME && $request->api_password != Constants::$API_PASSOWRD) {
            return response()->json([
                'code' => Response::HTTP_FORBIDDEN, 'message' => "Wrong api credentials"
            ], Response::HTTP_OK);
        } else {

            $videos = DB::table('videos')->where('sound_id', $request->sound_id)->orderBy('id', 'DESC')->get();
            foreach ($videos as $video) {
                $comment = DB::table('comments')->where('video_id', $video->id)->count();
                $video->commentCount = $comment;
                $video->likeCount = 10;
                $video->shareCount = 15;
                $user = User::find($video->user_id);
                if ($video->sound_id != -1) {

                    $sound = DB::table('sounds')->where('id', $video->sound_id)
                        ->select("title")->limit(1)->pluck('title');
                    $video->soundName = $sound[0];
                } else {
                    $video->soundName = 'Original sound by ' . $user->name;
                }
                $video->user = $user;
            }
            $sound = Sounds::find($request->sound_id);
            return response()->json([
                'code' => 200, 'message' => "false", 'videos' => $videos, "sound" => $sound
                ,
            ], 200);
        }
    }
}

