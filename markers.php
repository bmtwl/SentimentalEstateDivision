<?php
require 'db.php';

header('Content-Type: application/json');

try {
    $photo = $_POST['photo'];
    $x = (int)$_POST['x'];
    $y = (int)$_POST['y'];
    $name = $_POST['name'];

    // Check if marker exists for this user
    $stmt = $pdo->prepare("SELECT 1 FROM markers WHERE photo = ? AND x = ? AND y = ? AND name = ?");
    $stmt->execute([$photo, $x, $y, $name]);

    if($stmt->fetch()) {
        // Delete existing marker
        $pdo->prepare("DELETE FROM markers WHERE photo = ? AND x = ? AND y = ? AND name = ?")
            ->execute([$photo, $x, $y, $name]);
    } else {
        // Insert new marker (upsert)
        $pdo->prepare("INSERT INTO markers (photo, x, y, name) VALUES (?,?,?,?)
            ON CONFLICT (photo, x, y) DO UPDATE SET name = EXCLUDED.name")
            ->execute([$photo, $x, $y, $name]);
    }

    echo json_encode(['success' => true]);
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
