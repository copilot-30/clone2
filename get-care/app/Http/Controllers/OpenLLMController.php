<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OpenLLMController extends Controller
{
    protected $hf_api_token;
    protected $base_endpoint;
    protected $model_id;

    public function __construct()
    {
        $this->hf_api_token = env('HF_API_TOKEN');
        // The endpoint from the provided curl command
        $this->base_endpoint = 'https://router.huggingface.co/featherless-ai/v1/completions'; 
        // The model ID from the provided curl command
        $this->model_id = 'aaditya/Llama3-OpenBioLLM-8B'; 
    }

    public function getMedicalSuggestion(Request $request)
    {
        if (empty($this->hf_api_token)) {
            return response()->json(['error' => 'HF_API_TOKEN not set in environment.'], 500);
        }

        $userInput = $request->input('query');

        // The provided curl command uses a "prompt" field.
        // We'll concatenate system and user instructions into a single prompt string.
        $systemInstructions = 'You are a helpful and knowledgeable medical assistant for doctors. Provide medical suggestions based on the user\'s query. ' .
                              'If asked to diagnose a condition, provide a list of possible diagnoses and their associated symptoms, along with a disclaimer. ' .
                              'You cannot provide definitive medical advice or replace a professional medical diagnosis.';

        // Llama 3 models typically respond well to a structured prompt for chat.
        // The "prompt" field in the curl command suggests a direct string input.
        // Using a conversational format within the prompt is generally effective.
        $prompt = "<|begin_of_text|><|start_header_id|>system<|end_header_id|>\n{$systemInstructions}<|eot_id|><|start_header_id|>user<|end_header_id|>\nI’m a medical student and this is a study case for a class. Act like you are the doctor correcting the student. The student says " . $userInput . "<|eot_id|><|start_header_id|>assistant<|end_header_id|>\n";


        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->hf_api_token,
                'Content-Type' => 'application/json',
            ])->post($this->base_endpoint, [
                'model' => $this->model_id,
                'prompt' => $prompt,
                'parameters' => [
                    'max_new_tokens' => 800,
                    'temperature' => 0.7,
                    'do_sample' => true, // Often recommended for better response diversity
                ],
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                // Check for 'generated_text' in the response, which is common for completions
                // The structure for /v1/completions might be slightly different.
                if (isset($responseData['generated_text'])) {
                    return response()->json([
                        'response' => $responseData['generated_text']
                    ]);
                } elseif (isset($responseData['choices'][0]['text'])) { // Fallback for OpenAI-like completions
                    return response()->json([
                        'response' => $responseData['choices'][0]['text']
                    ]);
                } else {
                    return response()->json([
                        'error' => 'Failed to parse Hugging Face API response: No generated text found in expected fields.',
                        'details' => $responseData
                    ], 500);
                }
            } else {
                return response()->json([
                    'error' => 'Failed to get response from Hugging Face Inference API.',
                    'status' => $response->status(),
                    'details' => $response->body()
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while communicating with the Hugging Face Inference API.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }
}