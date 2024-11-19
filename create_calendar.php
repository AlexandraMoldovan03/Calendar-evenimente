<?php
require_once('db-connect.php');

// Initialize variables for success message and calendar code
$calendarCreated = false;
$calendarCode = "";

// Check if the calendar name was sent via POST
if (isset($_POST['calendar_name'])) {
    $calendar_name = $_POST['calendar_name'];

    // Generate a unique code for the calendar
    $calendar_code = uniqid();

    // Save the calendar to the database with the specified name and code
    $stmt = $conn->prepare("INSERT INTO calendars (name, access_code) VALUES (?, ?)");
    $stmt->bind_param("ss", $calendar_name, $calendar_code);

    if ($stmt->execute()) {
        // Set variables for success message and calendar code
        $calendarCreated = true;
        $calendarCode = $calendar_code;
    } else {
        echo "<p>Eroare la crearea calendarului. Încercați din nou.</p>";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/create_calendar.css">
    <title>Creare Calendar Nou</title>
    <style>
        /* Add CSS styles for your container and form */
    </style>
</head>
<body>
    <div class='container'>
        <div class='container-head'>
            <h1>Creează un Calendar Nou</h1>
        </div>
        <form action="create_calendar.php" method="post">
            <label for="calendar_name">Nume Calendar:</label>
            <input type="text" id="calendar_name" name="calendar_name" required>
            <button type="submit">Creează Calendarul</button>
        </form>

        <?php if ($calendarCreated): ?>
            <div class="announcements">
                <h2>Calendarul a fost creat cu succes!</h2>
                <p>Codul noului calendar este: <strong><?php echo htmlspecialchars($calendarCode); ?></strong></p>
                <p><a href="calendar.php?calendar_code=<?php echo urlencode($calendarCode); ?>">Accesează calendarul</a></p>
                <p>Puteți salva acest cod pentru a accesa calendarul și adăuga evenimente.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>