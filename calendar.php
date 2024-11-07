<?php
require_once('db-connect.php');

if (!isset($_GET['calendar_code'])) {
    echo "Codul calendarului este necesar.";
    exit;
}

$calendar_code = $_GET['calendar_code'];

// Verifică dacă calendarul există în baza de date
$stmt = $conn->prepare("SELECT * FROM calendars WHERE calendar_code = ?");
$stmt->bind_param("s", $calendar_code);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Calendarul nu a fost găsit.";
    exit;
}

// Afișează calendarul dacă codul este valid
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Calendarul Meu</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Calendarul meu - Cod: <?= htmlspecialchars($calendar_code) ?></h1>
        <!-- Integrarea calendarului existent -->
        <!-- Folosiți codul deja creat pentru afișarea calendarului, în index.php -->
    </div>
</body>
</html>
