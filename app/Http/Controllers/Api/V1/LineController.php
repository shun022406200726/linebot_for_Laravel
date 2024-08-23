<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class LineController extends Controller
{
    // メッセージ送信
    public function delivery()
    {
        // TODO: ここに具体的に実装

        // 1. 登録されている友だちにメッセージを送信
        $httpClient = new CurlHTTPClient(env('LINE_CHANNEL_ACCESS_TOKEN'));
        $bot = new LINEBot($httpClient, ['channelSecret' => env('CHANNEL_SECRET')]);
        $messageBuilder=new TextMessageBuilder('test');
        $bot->broadcast($messageBuilder);
  
        return response()->json(['message' => 'sent']);
    }

    // メッセージを受け取って返信
    public function callback(Request $request)
    {
        // TODO: ここに具体的に実装

        // 1. 受け取った情報からメッセージの情報を取り出す
        // Log::debug($request->getContent());
        $eventsObj = json_decode($request->getContent());
        $replyToken='';
        $replyMessage='';
        if (is_null($eventsObj)|| is_null($eventsObj->events)) {
            return response()->json(['message'=>'received(no events)']);
        }

        foreach ($eventsObj->events as $event) {
            if ($event->type == 'message') {
                $replyToken=$event->replyToken;
                $message=$event->message;
            }
        }

        // 2. 受け取ったメッセージの内容から返信するメッセージを生成
        switch ($message->text) {
            case '今日の天気は？':
                # code...
                $replyMessage='はれ';
                break;
            case '元気？';
                $replyMessage='元気';
                break;           
            default:
                if (strops($message->text,'?')!==false) {
                    # code...
                    $replyMessage='質問は？'
                }else{
                    $replyMessage='は？'
                }
                break;
        }
        // 3. 返信メッセージを返信先に送信

        return response()->json(['message' => 'received']);
    }
}

