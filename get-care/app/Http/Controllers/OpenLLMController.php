<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Doctor;
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

        $this -> doctors = Doctor::all();
    }

    //1. ai must give information about the user's concern 
    //2. Able to suggest doctor base ai's explanation of user's concern
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
                              'You cannot provide definitive medical advice or replace a professional medical diagnosis. ' .
                            //   '**IMPORTANT**: If the user asks anything about setting an appointment, finding a doctor, or booking a consultation, you MUST use the `create_link` tool. Respond with a JSON object like this: `{"tool_call": "create_link", "url": "patient.select-doctor", "text": "Click here to Book an Appointment"}`. ' .
                            //   '**IMPORTANT**: If you need to retrieve patient details, generate a JSON object in your response like this: `{"tool_call": "get_patient_details"}`. ' .
                            //   '**IMPORTANT**: If the user asks for doctor recommendations or a list of doctors, you should use the `get_doctors` tool by generating a JSON object like this: `{"tool_call": "get_doctors"}`. ' .
                              'Always ensure your JSON output is valid and surrounded by triple backticks (```json ... ```).';

        // Llama 3 models typically respond well to a structured prompt for chat.
        // The "prompt" field in the curl command suggests a direct string input.
        // Using a conversational format within the prompt is generally effective.
        $prompt = "<|begin_of_text|><|start_header_id|>system<|end_header_id|>\n{$systemInstructions}<|eot_id|><|start_header_id|>user<|end_header_id|>\nI’m a medical student and this is a study case for a class. Act like you are the doctor correcting the student. The student says " . $userInput . "<|eot_id|><|start_header_id|>assistant<|end_header_id|>\n";

        $fullResponse = ''; // Initialize full response string

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
                    'do_sample' => true,
                    // Llama 3 special tokens for response generation stopping.
                    // This might need adjustment based on how the model is fine-tuned.
                    // 'stop' => ["<|eot_id|>", "<|end_of_text|>"],
                ],
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                $generatedText = '';

                if (isset($responseData['generated_text'])) {
                    $generatedText = $responseData['generated_text'];
                } elseif (isset($responseData['choices'][0]['text'])) {
                    $generatedText = $responseData['choices'][0]['text'];
                }

                // Attempt to parse tool call from the generated text
                if (preg_match('/```json\s*(\{.*\})\s*```/', $generatedText, $matches)) {
                    $jsonString = $matches[1];
                    $toolCall = json_decode($jsonString, true);

                    if ($toolCall && isset($toolCall['tool_call'])) {
                        $toolResponse = null;
                        if ($toolCall['tool_call'] === 'create_link') {
                            $url = route(str_replace(['route(\'', '\')'], '', $toolCall['url'])) ?? '#';
                            $text = $toolCall['text'] ?? 'Link';
                            $fullResponse .= "<a href=\"{$url}\" target=\"_blank\" class=\"text-blue-500 hover:underline\">{$text}</a>";
                        } elseif ($toolCall['tool_call'] === 'get_patient_details') {
                            // Make an internal API request to get patient details
                            $internalApiRequest = Request::create(route('api.patient.details'), 'GET');
                            $internalApiRequest->headers->set('Authorization', 'Bearer ' . $request->bearerToken());
                            $patientDetailsResponse = app()->handle($internalApiRequest);
                            $patientDetails = json_decode($patientDetailsResponse->getContent(), true);
                            $toolResponse = json_encode($patientDetails);

                            // Send tool output back to LLM to generate a natural language response
                            $followUpPrompt = $prompt . "\nTool Output (get_patient_details): " . $toolResponse . "\nAssistant:";
                             $secondResponse = Http::withHeaders([
                                'Authorization' => 'Bearer ' . $this->hf_api_token,
                                'Content-Type' => 'application/json',
                            ])->post($this->base_endpoint, [
                                'model' => $this->model_id,
                                'prompt' => $followUpPrompt,
                                'parameters' => [
                                    'max_new_tokens' => 800,
                                    'temperature' => 0.7,
                                    'do_sample' => true,
                                ],
                            ]);

                            if ($secondResponse->successful() && isset($secondResponse->json()[0]['generated_text'])) {
                                $fullResponse .= $secondResponse->json()[0]['generated_text'];
                            } else {
                                $fullResponse .= "I retrieved some information, but encountered an issue processing it.";
                            }
                        } elseif ($toolCall['tool_call'] === 'get_doctors') {
                            // Make an internal API request to get doctor details
                            $internalApiRequest = Request::create(route('api.doctors'), 'GET');
                            $internalApiRequest->headers->set('Authorization', 'Bearer ' . $request->bearerToken());
                            $doctorsResponse = app()->handle($internalApiRequest);
                            $doctors = json_decode($doctorsResponse->getContent(), true);
                            $toolResponse = json_encode($doctors);

                            // Send tool output back to LLM to generate a natural language response
                            $followUpPrompt = $prompt . "\nTool Output (get_doctors): " . $toolResponse . "\nAssistant:";
                             $secondResponse = Http::withHeaders([
                                'Authorization' => 'Bearer ' . $this->hf_api_token,
                                'Content-Type' => 'application/json',
                            ])->post($this->base_endpoint, [
                                'model' => $this->model_id,
                                'prompt' => $followUpPrompt,
                                'parameters' => [
                                    'max_new_tokens' => 800,
                                    'temperature' => 0.7,
                                    'do_sample' => true,
                                ],
                            ]);

                            if ($secondResponse->successful() && isset($secondResponse->json()[0]['generated_text'])) {
                                $fullResponse .= $secondResponse->json()[0]['generated_text'];
                            } else {
                                $fullResponse .= "I retrieved doctor information, but encountered an issue processing it.";
                            }
                        }
                    } else {
                        // If it's a JSON but not a recognized tool call, just append it as text.
                        $fullResponse .= $generatedText;
                    }
                } else {
                    // No JSON found, append as plain text
                    $fullResponse .= $generatedText;
                }

                if (!empty($fullResponse)) {
                    return response()->json([
                        'response' => $fullResponse,
                        'model' => $this->model_id
                    ]);
                }
            }
            
            return response()->json([
                'error' => 'Failed to get response from Hugging Face Inference API. Status: ' . $response->status(),
                'details' => $response->body()
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while communicating with the Hugging Face Inference API.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }
}