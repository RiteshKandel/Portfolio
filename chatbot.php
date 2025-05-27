<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Replace this with your OpenRouter API Key
$apiKey = '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';

$request = json_decode(file_get_contents("php://input"), true);
$message = $request["message"] ?? "";

$curl = curl_init("https://openrouter.ai/api/v1/chat/completions");

$data = [
    "model" => "openai/gpt-3.5-turbo", // You can also try "mistralai/mistral-7b-instruct" or "anthropic/claude-3-opus"
    "messages" => [
    ["role" => "system", "content" => "
You are a helpful, professional AI assistant named Noob, created by Ritesh Kandel. 

Your job is to act as Ritesh’s personal AI and answer on his behalf when people ask about him.

Here is everything you know about Ritesh:
Ritesh is a computer engineering student from Kathmandu, Nepal.
- Full Name: Ritesh Kandel
- Nickname: RK
- University: Pandit Deendayal Energy University (PDEU)
- Location: Gandhinagar, Gujarat, India
- Field of Study: Computer Science
- Interests: Artificial Intelligence, Software Development
- Personality: Friendly, helpful, ambitious, and tech-savvy
- Email: kandelritesh@gmail.com
- Availability: Usually responds to emails within 24 hours.

When anyone asks about Ritesh or tries to talk to him, respond on his behalf as his assistant (e.g., “Ritesh is currently unavailable but here’s the information you requested…”).

If a message is about something else (general questions), respond like a smart AI assistant.

Avoid making up any information about Ritesh that is not provided.
"],

    ["role" => "user", "content" => $message]
]

];

curl_setopt_array($curl, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $apiKey",
        "Content-Type: application/json",
        "HTTP-Referer: yourdomain.com", // <-- Optional: replace with your site or leave as is
        "X-Title: AI Chatbot" // Optional: Title for OpenRouter dashboard
    ],
    CURLOPT_POSTFIELDS => json_encode($data),
]);

$response = curl_exec($curl);
curl_close($curl);

$decoded = json_decode($response, true);

if (isset($decoded["choices"][0]["message"]["content"])) {
    $reply = $decoded["choices"][0]["message"]["content"];
} else {
    $reply = "OpenRouter Error: " . json_encode($decoded);
}

echo json_encode(["reply" => $reply]);
