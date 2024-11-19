<?php
require_once('db-connect.php');

// Verificăm dacă `calendar_code` este transmis prin URL
if (isset($_GET['calendar_code'])) {
    $calendar_code = $_GET['calendar_code'];
} else {
    echo "Codul calendarului nu a fost specificat.";
    exit; // Ieșim din script dacă nu există codul calendarului
}

// Extragem evenimentele doar pentru calendarul specificat
$events = [];

$sql = "SELECT * FROM `schedule_list` WHERE `calendar_code` = '$calendar_code'";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $events[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'description' => $row['description'],
            'start' => $row['start_datetime'],
            'end' => $row['end_datetime'],
        ];
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Calendar</title>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Calendar</h1>
    <div id="calendar"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek', // Vizualizare săptămânală cu intervale orare
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,list'
                },
                selectable: true,
                selectMirror: true,
                editable: true,
                slotDuration: '00:30:00', // Interval de 30 de minute
                events: <?php echo json_encode($events); ?>,
                
                eventClick: function(info) {
                    alert('Event: ' + info.event.title + '\nDescription: ' + info.event.extendedProps.description);
                },
                
                select: function(info) {
                    var title = prompt('Enter Event Title:');
                    var description = prompt('Enter Event Description:');
                    if (title) {
                        $.ajax({
                            url: 'save_event.php',
                            method: 'POST',
                            data: {
                                title: title,
                                description: description,
                                start_datetime: info.startStr,
                                end_datetime: info.endStr,
                                calendar_code: "<?php echo $calendar_code; ?>" // Adăugăm `calendar_code`
                            },
                            success: function(response) {
                                alert('Event added successfully');
                                calendar.refetchEvents();
                            }
                        });
                    }
                    calendar.unselect();
                },

                eventDrop: function(info) {
                    updateEvent(info.event);
                },
                eventResize: function(info) {
                    updateEvent(info.event);
                }
            });

            calendar.render();

            function updateEvent(event) {
                $.ajax({
                    url: 'save_event.php',
                    method: 'POST',
                    data: {
                        id: event.id,
                        title: event.title,
                        description: event.extendedProps.description,
                        start_datetime: event.start.toISOString(),
                        end_datetime: event.end.toISOString(),
                        calendar_code: "<?php echo $calendar_code; ?>" // Adăugăm `calendar_code`
                    },
                    success: function(response) {
                        alert('Event updated successfully');
                    }
                });
            }
        });
    </script>
</body>
</html>
