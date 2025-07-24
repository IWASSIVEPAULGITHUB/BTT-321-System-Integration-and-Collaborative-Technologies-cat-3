<?php
include 'db.php';
$title = $_POST['title'];
$stmt = $conn->prepare("INSERT INTO tasks (title, status) VALUES (?, 'To Do')");
$stmt->bind_param("s", $title);
$stmt->execute();
header("Location: index1.php");
