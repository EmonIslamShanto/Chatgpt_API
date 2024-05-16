<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;

class OpenAIController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index(): JsonResponse
    {
        $search = "laravel get ip address";

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('OPENAI_API_KEY'),
          ])
          ->post("https://api.openai.com/v1/chat/completions", [
            "model" => "gpt-3.5-turbo",
            'messages' => [
                [
                   "role" => "user",
                   "content" => $search
               ]
            ],
            'temperature' => 0.5,
            "max_tokens" => 200,
            "top_p" => 1.0,
            "frequency_penalty" => 0.52,
            "presence_penalty" => 0.5,
            "stop" => ["11."],
          ]);

// Check if the response is successful
if ($response->successful()) {
    $data = $response->json();

    // Check if 'choices' key exists in the response
    if (isset($data['choices'])) {
        return response()->json($data['choices'][0]['message'], 200, array(), JSON_PRETTY_PRINT);
    } else {
        // Handle case where 'choices' key is missing
        return response()->json(['error' => 'Unexpected response format'], 500);
    }
} else {
    // Handle failed API request
    return response()->json(['error' => 'Failed to retrieve response from OpenAI API'], $response->status());
}
}
}
