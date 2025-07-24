<?php
include 'db.php';
$id = $_POST['id'];

if (isset($_POST['delete'])) {
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id=?");
    $stmt->bind_param("i", $id);
} else {
    $new_status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE tasks SET status=? WHERE id=?");
    $stmt->bind_param("si", $new_status, $id);
}
$stmt->execute();
header("Location: index1.php");
