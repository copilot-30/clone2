<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GPTController extends Controller
{

    function __construct()
    {
        $this -> api_key = env('GEMINI_API_KEY');
    }

    public function getMedicalSuggestion(Request $request)
    {


       $apiKey =  $this -> api_key;
       if (empty($apiKey)) {
           return response()->json(['error' => 'GEMINI_API_KEY not set in environment.'], 500);
       }


        $userInput = $request->input('query');

        // Define system instructions as a single string for Gemini
        $systemInstructions = 'You are a helpful and knowledgeable medical tutor for students. Suggest a medical suggestion based on the user\'s query. ' .
                              'If you are asked to diagnose a condition, respond with a list of possible diagnoses and their associated symptoms. ' .
                              'If you are asked how to set an appointment, **you must use the `create_link` tool** to provide a clickable link to the appointment selection page. For example, if a user asks to set an appointment, you should respond by using the `create_link` tool with `url` as `route(\'patient.select-doctor\')` and `text` as "Book an Appointment". ' .
                              'When providing a link, always use the `create_link` tool instead of directly embedding markdown links.' .
                              'You are designed to be a helpful assistant for medical information, but you are not a medical professional. Always include a clear disclaimer that you cannot provide diagnoses or replace professional medical advice. ';
                            //   'When providing wall of text, make it easy to read in terms of layout and spaces. ';

       // Define the tools available to the model
       $tools = [
           [
               "functionDeclarations" => [
                   [
                       "name" => "create_link",
                       "description" => "Creates a clickable HTML link for the user. Use this when the user needs to navigate to a specific page.",
                       "parameters" => [
                           "type" => "object",
                           "properties" => [
                               "url" => [
                                   "type" => "string",
                                   "description" => 'The route name for the link, e.g., "patient.select-doctor".'
                                  ],
                                  "text" => [
                                      "type" => "string",
                                      "description" => "The display text for the link."
                                  ]
                              ],
                              "required" => ["url", "text"]
                          ]
                      ],
                      [
                           "name" => "get_patient_details",
                           "description" => "Retrieves basic medical information about the currently authenticated patient.",
                           "parameters" => [
                               "type" => "object",
                               "properties" => (object)[], // Corrected: use an empty object for properties
                               "required" => []
                           ]
                      ]
                  ]
              ]
          ];

       $messages = [
           [
               'role' => 'user',
               'parts' => [
                   ['text' => $systemInstructions],
                   ['text' =>  "I’m a medical student and this is a study case for a class. Act like you are the doctor correcting the student. The student says ".$userInput]
               ]
           ]
       ];

        // This is a temporary variable to hold the current conversation state for function calling
        // In a real application, you'd manage conversation history more robustly (e.g., in session or database)
        $conversationHistory = [
            // Add previous messages here if you want the model to have context of prior turns
            // For this example, we'll assume a fresh turn.
        ];

        // Add the user's current message to the conversation history
        $conversationHistory[] = [
            'role' => 'user',
            'parts' => [['text' => $userInput]]
        ];

        // Initial call to Gemini
        $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}", [
            'contents' => $conversationHistory, // Use conversation history
            'tools' => $tools,
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 800,
            ],
        ]);

        $responseData = $response->json();
        $candidate = $responseData['candidates'][0] ?? null;

        if ($candidate && isset($candidate['content']['parts'])) {
            $toolCall = null;
            foreach ($candidate['content']['parts'] as $part) {
                if (isset($part['functionCall'])) {
                    $toolCall = $part['functionCall'];
                    break;
                }
            }

            if ($toolCall) {
                $toolResponse = null;
                if ($toolCall['name'] === 'create_link') {
                    $url = route(str_replace(['route(\'', '\')'], '', $toolCall['args']['url']));
                    $text = $toolCall['args']['text'];
                    $toolResponse = "<a href=\"{$url}\" target=\"_blank\" class=\"text-blue-500 hover:underline\">{$text}</a>";
                } elseif ($toolCall['name'] === 'get_patient_details') {
                    // Make an internal API request to get patient details
                    // We'll simulate authentication for this internal call
                    $internalApiRequest = Request::create(route('api.patient.details'), 'GET');
                    $internalApiRequest->headers->set('Authorization', 'Bearer ' . $request->bearerToken()); // Pass current user's token
                    $patientDetailsResponse = app()->handle($internalApiRequest);
                    $patientDetails = json_decode($patientDetailsResponse->getContent(), true);
                    $toolResponse = json_encode($patientDetails);
                }

                if ($toolResponse !== null) {
                    // Send tool output back to Gemini
                    $conversationHistory[] = [
                        'role' => 'function',
                        'parts' => [
                            ['functionResponse' => ['name' => $toolCall['name'], 'response' => ['content' => $toolResponse]]]
                        ]
                    ];
                    $secondResponse = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                        'contents' => $conversationHistory,
                        'tools' => $tools,
                        'generationConfig' => [
                            'temperature' => 0.7,
                            'maxOutputTokens' => 800,
                        ],
                    ]);
                    $secondResponseData = $secondResponse->json();
                    $secondCandidate = $secondResponseData['candidates'][0] ?? null;

                    if ($secondCandidate && isset($secondCandidate['content']['parts'][0]['text'])) {
                        return response()->json([
                            'response' => $secondCandidate['content']['parts'][0]['text']
                        ]);
                    } else {
                        return response()->json([
                            'error' => 'Failed to get a follow-up response from Gemini after tool execution.'
                        ], 500);
                    }
                }
            } elseif (isset($candidate['content']['parts'][0]['text'])) {
                // Regular text response from Gemini
                return response()->json([
                    'response' => $candidate['content']['parts'][0]['text']
                ]);
            }
        }


       $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
           'contents' => $messages,
           'tools' => $tools, // Include tool definitions
           'generationConfig' => [
               'temperature' => 0.7,
               'maxOutputTokens' => 800,
           ],
       ]);
       
       if ($response->successful()) {
           $responseData = $response->json();
           $candidate = $responseData['candidates'][0] ?? null;
           $fullResponse = '';

           if ($candidate && isset($candidate['content']['parts'])) {
               foreach ($candidate['content']['parts'] as $part) {
                   if (isset($part['functionCall'])) {
                       $functionCall = $part['functionCall'];
                       if ($functionCall['name'] === 'create_link') {
                           $url = route(str_replace(['route(\'', '\')'], '', $functionCall['args']['url'])) ?? '#'; // Dynamically resolve route
                           $text = $functionCall['args']['text'] ?? 'Link';
                           $fullResponse .= "<a href=\"{$url}\" target=\"_blank\" class=\"text-blue-500 hover:underline\">{$text}</a>";
                       }
                       // Add handling for other function calls if necessary
                   } elseif (isset($part['text'])) {
                       $fullResponse .= $part['text'];
                   }
               }
               if (!empty($fullResponse)) {
                   return response()->json([
                       'response' => $fullResponse
                   ]);
               }
           }
           return response()->json([
               'error' => 'Failed to parse Gemini response: No valid content or function call found. Response: ' . json_encode($responseData)
           ], 500);

       }

       return response()->json([
           'error' => 'Failed to get response from Gemini API.',
           'details' => $response->body()
       ], 500);
   }

   // Helper function to simulate tool execution (if needed for complex tools)
   // For `create_link`, we directly generate HTML in the controller.
   // public function executeTool($functionCall)
   // {
   //     if ($functionCall['name'] === 'create_link') {
   //         $url = $functionCall['args']['url'];
   //         $text = $functionCall['args']['text'];
   //         return "<a href=\"{$url}\" target=\"_blank\" class=\"text-blue-500 hover:underline\">{$text}</a>";
   //     }
   //     return "Tool not found or arguments invalid.";
   // }
}