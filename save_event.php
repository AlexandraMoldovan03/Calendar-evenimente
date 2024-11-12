<?php
require_once('db-connect.php');

$title = $_POST['title'];
$description = $_POST['description'];
$start_datetime = $_POST['start_datetime'];
$end_datetime = $_POST['end_datetime'];
$calendar_code = $_POST['calendar_code']; // Primim `calendar_code`

if (isset($_POST['id']) && !empty($_POST['id'])) {
    // Actualizăm un eveniment existent
    $id = $_POST['id'];
    $sql = "UPDATE `schedule_list` SET 
            `title` = '$title', 
            `description` = '$description', 
            `start_datetime` = '$start_datetime', 
            `end_datetime` = '$end_datetime' 
            WHERE `id` = '$id' AND `calendar_code` = '$calendar_code'";
} else {
    // Adăugăm un eveniment nou
    $sql = "INSERT INTO `schedule_list` (`title`, `description`, `start_datetime`, `end_datetime`, `calendar_code`) 
            VALUES ('$title', '$description', '$start_datetime', '$end_datetime', '$calendar_code')";
}

if ($conn->query($sql)) {
    echo "Event saved successfully.";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
