<?php
require_once('db-connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_datetime = $_POST['start_datetime'];
    $end_datetime = $_POST['end_datetime'];

    $stmt = $conn->prepare("INSERT INTO `schedule_list` (title, description, start_datetime, end_datetime) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $description, $start_datetime, $end_datetime);

    if ($stmt->execute()) {
        echo "Programare salvată!";
    } else {
        echo "Eroare: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
header("Location: index.php");
