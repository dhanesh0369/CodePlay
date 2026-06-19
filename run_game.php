<?php
// run_game.php - connects to Judge0 API to compile code
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$source_code = $data["source_code"] ?? "";
$language_id = $data["language_id"] ?? "";

if (empty($source_code) || empty($language_id)) {
    echo json_encode(["error" => "Missing source code or language ID"]);
    exit;
}

$api_url = "https://judge0-ce.p.rapidapi.com/submissions?base64_encoded=false&wait=true";

$payload = json_encode([
    "source_code" => $source_code,
    "language_id" => $language_id
]);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "content-type: application/json",
    "X-RapidAPI-Key: d9861e7b76mshdb78acf5b0ca150p163464jsn83dbdc492d46",
    "X-RapidAPI-Host: judge0-ce.p.rapidapi.com"
]);

$response = curl_exec($ch);
curl_close($ch);

echo $response;
