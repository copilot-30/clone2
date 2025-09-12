<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GPTController extends Controller
{
    public function getMedicalSuggestion(Request $request)
    {
        $userInput = $request->input('query');

        // Define the system and user messages
        $messages = [
            [
                'role' => 'system',
                'content' => 'You are a helpful and knowledgeable medical tutor for patients. Give actual medical advice or diagnoses but put disclaimers.'
            ],
            [
                'role' => 'system',
                'content' => 'If you are asked to diagnose a condition, respond with a list of possible diagnoses and their associated symptoms.',
            ],
            [
                'role' => 'system',
                'content' => 'If you are asked how to set an appointment, redirect to '. route('patient.select-doctor'),
            ],
            [
                'role' => 'user',
                'content' => $userInput
            ]
        ];

        $response = Http::withToken(env('OPENAI_API_KEY'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo', // You can use 'gpt-4' or 'gpt-3.5-turbo' if you want cheaper option
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 800,
            ]);

        if ($response->successful()) {
            return response()->json([
                'response' => $response['choices'][0]['message']['content']
            ]);
        }

        return response()->json([
            'error' => 'Failed to get response from OpenAI'
        ], 500);
    }
}