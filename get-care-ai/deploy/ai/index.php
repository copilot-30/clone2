<?php

require __DIR__ . '/vendor/autoload.php';
 
use ArdaGnsrn\Ollama\Ollama;
use Ardagnsrn\Ollama\Requests\ChatRequest;
use Ardagnsrn\Ollama\Requests\CompletionRequest;

$getcare_api = "http://host.docker.internal:8000/api/doctors";

$doctors_str = file_get_contents($getcare_api);

$doctors = json_decode($doctors_str, true);

for ($i = 0; $i < count($doctors); $i++) {
    $doctors[$i]['full_name'] = $doctors[$i]['first_name'] . ' ' . $doctors[$i]['last_name'];
}
 

class_exists(Ollama::class) || die('Ollama class not found. Please make sure you have installed the ollama-php package.');

$model = 'koesn/llama3-openbiollm-8b';//:q6_1K

$ollama_host = "http://192.168.2.109:11434"; //change to your windows ollama ipconfig ip address.
// $ollama_host = "http://ollama:11434"; //docker
// $ollama_host = "http://host.docker.internal:11435"; //docker from host
header('Content-Type: application/json');

$ollama =  Ollama::client($ollama_host); // 'ollama' is the service name in docker-compose

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $system = "You are a medical professional and a medical assistant chatbot.You must first explain and answer the user's question and find the most appropriate tool or tools to execute, along with the parameters required to run the tool.  When answering any question, respond with a detailed and easy to understand words, do not use vague words. Always recommend a doctor based on the specialization of the doctor. When ask a question about a doctor, use the following format: Dr. John Doe - Cardiologist.
    When ask about consultation or schedule a consultation, respond with 'http://localhost:3000/consultation'
    Here are the tools available for you to use: ";

    $tools = [];

    array_push($tools, [
            'type' => 'function',
            'function' => [
                'name' => 'get_doctors',
                'description' => 'Recommend a doctor based on the specialization of the doctor from '.json_encode($doctors). "When answering use the following format: Dr. John Doe - Cardiologist",
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'specialization' => [
                            'type' => 'string',
                            'description' => 'The specialization of the doctor, eg. Cardiologist',
                        ],
                        'doctor' => [
                            'type' => 'string',
                            'description' => 'The name of the doctor and specialization, eg. Dr. John Doe - Cardiologist',
                        ]
                    ],
                    'required' => ['specialization','doctor'],
                ],
            ],
        ]);

    if (isset($input['prompt'])) {
        // Handle completion request    
        $messages = [
            [
                "role" => "system",
                "content" => $system. json_encode($tools)
            ],
            [
                "role" => "user",
                "content" => $input['prompt']
            ]
            ];
        $completions = $ollama->chat()->create([ 
            "model" => $model,
            'messages' => $messages,
        ]);
 

        echo json_encode(
            [
            'completions' => $completions,
            'messages' => $messages
            ]
            );

    } elseif (isset($input['messages'])) {
        // Handle chat request
        $chatRequest = new ChatRequest(
            $model,
            $input['messages'],
            false
        );
        $response = $ollama->chat($chatRequest);
        echo json_encode($response->json());
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request body. Missing "prompt" or "messages".']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed. Only POST requests are supported.']);
}

?>