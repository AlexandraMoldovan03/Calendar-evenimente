<?php
require_once('db-connect.php');

// Verificăm dacă numele calendarului a fost trimis prin POST
if (isset($_POST['calendar_name'])) {
    $calendar_name = $_POST['calendar_name'];

    // Generăm un cod unic pentru calendar
    $calendar_code = uniqid();

    // Salvăm calendarul în baza de date cu numele și codul specificate
    $stmt = $conn->prepare("INSERT INTO calendars (name, access_code) VALUES (?, ?)");
    $stmt->bind_param("ss", $calendar_name, $calendar_code);

    if ($stmt->execute()) {
        // Afișăm mesajul cu codul calendarului
        echo "<h2>Calendarul a fost creat cu succes!</h2>";
        echo "<p>Codul noului calendar este: <strong>$calendar_code</strong></p>";
        echo "<p><a href='calendar.php?calendar_code=" . $calendar_code . "'>Accesează calendarul</a></p>";
        echo "<p>Puteți salva acest cod pentru a accesa calendarul și adăuga evenimente.</p>";
    } else {
        echo "Eroare la crearea calendarului. Încercați din nou.";
    }

    $stmt->close();
} else {
    echo "Vă rugăm să introduceți un nume pentru calendar.";
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Creare Calendar Nou</title>
</head>
<body>
    <h1>Creează un Calendar Nou</h1>
    <form action="create_calendar.php" method="post">
        <label for="calendar_name">Nume Calendar:</label>
        <input type="text" id="calendar_name" name="calendar_name" required>
        <button type="submit">Creează Calendarul</button>
    </form>
</body>
</html>
