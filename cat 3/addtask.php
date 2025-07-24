<?php
include 'db.php';
if (!isset($_SESSION['user_id'])) exit();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $uid = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO tasks (title, status, user_id) VALUES (?, 'To Do', ?)");
    $stmt->bind_param("si", $title, $uid);
    $stmt->execute();

    echo json_encode(['success' => true, 'id' => $conn->insert_id, 'title' => htmlspecialchars($title)]);
}
?>
