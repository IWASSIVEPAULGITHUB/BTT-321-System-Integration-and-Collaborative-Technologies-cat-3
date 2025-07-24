<?php
include 'db.php';
if (!isset($_SESSION['user_id'])) exit();

$id = $_POST['id'];

if (isset($_POST['delete'])) {
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id=? AND user_id=?");
    $stmt->bind_param("ii", $id, $_SESSION['user_id']);
} else {
    $new_status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE tasks SET status=? WHERE id=? AND user_id=?");
    $stmt->bind_param("sii", $new_status, $id, $_SESSION['user_id']);
}
$stmt->execute();
header("Location: index1.php");
