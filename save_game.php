<?php
// save_game.php - Save or update user game code
header("Content-Type: application/json");
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized. Please log in."]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Get POST JSON data
$data = json_decode(file_get_contents("php://input"), true);

$game_id = isset($data['game_id']) ? (int)$data['game_id'] : null;
$game_title = isset($data['game_title']) ? trim($data['game_title']) : '';
$template_type = isset($data['template_type']) ? trim($data['template_type']) : 'custom';
$code = isset($data['code']) ? $data['code'] : '';

if (empty($game_title)) {
    echo json_encode(["status" => "error", "message" => "Game title is required."]);
    exit;
}

try {
    if ($game_id) {
        // Update existing game (ensure it belongs to the logged-in user)
        $stmt = $pdo->prepare("UPDATE games SET game_title = ?, template_type = ?, code = ? WHERE id = ? AND user_id = ?");
        $stmt->execute([$game_title, $template_type, $code, $game_id, $user_id]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(["status" => "success", "message" => "Game updated successfully!", "game_id" => $game_id]);
        } else {
            // Check if it exists at all
            $check = $pdo->prepare("SELECT id FROM games WHERE id = ? AND user_id = ?");
            $check->execute([$game_id, $user_id]);
            if ($check->fetch()) {
                // It exists but no changes were made
                echo json_encode(["status" => "success", "message" => "No changes made to the game.", "game_id" => $game_id]);
            } else {
                echo json_encode(["status" => "error", "message" => "Game not found or access denied."]);
            }
        }
    } else {
        // Insert new game (or update if duplicate title exists for this user)
        // First check if the title already exists for this user to avoid database unique constraint crash
        $check = $pdo->prepare("SELECT id FROM games WHERE user_id = ? AND game_title = ?");
        $check->execute([$user_id, $game_title]);
        $existing = $check->fetch();

        if ($existing) {
            // Update the existing game by title
            $stmt = $pdo->prepare("UPDATE games SET template_type = ?, code = ? WHERE id = ?");
            $stmt->execute([$template_type, $code, $existing['id']]);
            echo json_encode(["status" => "success", "message" => "Game updated successfully!", "game_id" => $existing['id']]);
        } else {
            // Insert brand new
            $stmt = $pdo->prepare("INSERT INTO games (user_id, game_title, template_type, code) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $game_title, $template_type, $code]);
            $new_id = $pdo->lastInsertId();
            echo json_encode(["status" => "success", "message" => "Game created and saved!", "game_id" => $new_id]);
        }
    }
} catch (\PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>
