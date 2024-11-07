<?php
require_once('db-connect.php');

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM `schedule_list` WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Programare ștearsă!";
    } else {
        echo "Eroare: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
header("Location: index.php");
