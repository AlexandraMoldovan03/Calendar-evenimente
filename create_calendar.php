<?php
require_once('db-connect.php');

// Generăm un cod unic pentru calendar
$calendar_code = uniqid();

// Salvăm calendarul cu `calendar_code` în baza de date
$stmt = $conn->prepare("INSERT INTO calendars (name, calendar_code) VALUES (?, ?)");
$name = "Calendar Nou";
$stmt->bind_param("ss", $name, $calendar_code);

if ($stmt->execute()) {
    header("Location: calendar.php?calendar_code=" . $calendar_code);
    exit;
} else {
    echo "Eroare la crearea calendarului. Încercați din nou.";
}

$stmt->close();
$conn->close();
?>
