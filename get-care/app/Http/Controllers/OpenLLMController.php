<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use App\Doctor;


class OpenLLMController extends Controller
{
    protected $base_endpoint;
    protected $model_id;

    public function __construct()
    {
        // Use the local Ollama endpoint
        $this->base_endpoint = 'http://host.docker.internal:8001/index.php';
        // The model ID being pulled by Ollama
        $this->model_id = 'koesn/llama3-openbiollm-8b';

        $this -> doctors = Doctor::all();
    }

    //1. ai must give information about the user's concern 
    //2. Able to suggest doctor base ai's explanation of user's concern
     public function getMedicalSuggestion(Request $request)
    {

        $userInput = $request->input('query');
        // The provided curl command uses a "prompt" field.
        // We'll concatenate system and user instructions into a single prompt string.
        $systemInstructions = "You are OpenBioLLM, an expert in healthcare and biomedical domains with extensive medical knowledge from Saama AI Labs. Your purpose is to provide comprehensive and accessible explanations to user queries. When providing an explanation, incorporate your deep medical expertise, referencing relevant anatomical structures, physiological processes, diagnostic criteria, and treatment guidelines where appropriate. Use precise medical terminology, but ensure the explanation is clear and understandable for a general audience. After explaining the user's concern, you will suggest doctor specialties based on the condition. Always present the doctor suggestion in a JSON format after the explanation, like this: {\"suggested_specialties\": [\"Cardiology\", \"Neurology\"]}.";


        // Llama 3 models typically respond well to a structured prompt for chat.
        // The "prompt" field in the curl command suggests a direct string input.
        // Using a conversational format within the prompt is generally effective.
        $prompt = $userInput;

        $fullResponse = ''; // Initialize full response string

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->base_endpoint, [
                'model' => $this->model_id,
                'prompt' => $prompt,
                'stream' => false, // Enable streaming
                'options' => [
                    'num_predict' => 800,
                    'temperature' => 0.7,
                    'top_k' => 40,
                    'top_p' => 0.9,
                ],
            ]);

            $fullResponse = '';
            if ($response->successful()) {
                // When 'stream' is true, $response->body() will contain the entire streamed content
                // once the stream is complete. We then parse it line by line.
                $body = $response->body();
                $chunks = explode("\n", trim($body));

                foreach ($chunks as $chunk) {
                    if (empty($chunk)) {
                        continue;
                    }
                    $data = json_decode($chunk, true);
                    if (isset($data['response'])) {
                        $fullResponse .= $data['response'];
                    }
                }
                $generatedText = $fullResponse; // Set generatedText from the accumulated streamed response

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
                             // For follow-up prompts, we might not need streaming for simplicity,
                             // or implement full streaming logic if required for complex tool use.
                             $secondResponse = Http::withHeaders([
                                 'Content-Type' => 'application/json',
                             ])->post($this->base_endpoint, [
                                 'model' => $this->model_id,
                                 'prompt' => $followUpPrompt,
                                 'stream' => false, // Keep false for simplicity in follow-up, or implement streaming
                                 'options' => [
                                     'num_predict' => 800,
                                     'temperature' => 0.7,
                                 ],
                             ]);

                            if ($secondResponse->successful()) {
                                $secondGeneratedText = '';
                                if ($secondResponse->json('response')) {
                                    $secondGeneratedText = $secondResponse->json('response');
                                }
                                $fullResponse .= $secondGeneratedText;
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
                                 'Content-Type' => 'application/json',
                             ])->post($this->base_endpoint, [
                                 'model' => $this->model_id,
                                 'prompt' => $followUpPrompt,
                                 'stream' => false, // Keep false for simplicity in follow-up, or implement streaming
                                 'options' => [
                                     'num_predict' => 800,
                                     'temperature' => 0.7,
                                 ],
                             ]);

                            if ($secondResponse->successful()) {
                                $secondGeneratedText = '';
                                if ($secondResponse->json('response')) {
                                    $secondGeneratedText = $secondResponse->json('response');
                                }
                                $fullResponse .= $secondGeneratedText;
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
                if (!empty($fullResponse)) {
                    return response()->json([
                        'response' => $fullResponse,
                        'model' => $this->model_id
                    ]);
                }
            }
            // If response was not successful, or no content was generated
            return response()->json([
                'error' => 'Failed to get response from Ollama API. Status: ' . $response->status(),
                'details' => $response->body()
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while communicating with the Ollama API.',
                'exception' => $e->getMessage()
            ], 500);
        }
    }
}