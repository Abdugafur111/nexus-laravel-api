<?php

namespace App\Http\Controllers;
use Orhanerday\OpenAi\OpenAi;

use Illuminate\Http\Request;

class AIController extends Controller
{
    public function result(Request $request){


        $topic = $request->topic;

        $open_ai = new OpenAi(env('OPEN_AI_API_KEY'));

        $prompt="I have app which is called Nexus. It is a platform which combines all Cloud platforms
        like GDrive, Azure, AmazonAWS and shows usage statistics about them. I am using you as a chat bot if 
        I ask about company answer as a chatbot please". $topic." \n";
        $OpenAiOutput = $open_ai->completion([
            'model' => 'text-davinci-002',
            'prompt' => $prompt,
            'temperature' => 0.9,
            'max_tokens' => 150,
            'frequency_penalty' => 0,
            'presence_penalty' => 0.6,
         ]);
         $output = \json_decode( $OpenAiOutput,true);
         $outputText = $output["choices"][0]["text"];
        return  [
            'message' => $outputText
        ];
    }
}
