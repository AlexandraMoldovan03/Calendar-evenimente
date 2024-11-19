var calendar;
var Calendar = FullCalendar.Calendar;
var events = [];

$(function() {
    if (!!scheds) {
        Object.keys(scheds).map(k => {
            var row = scheds[k];
            events.push({ id: row.id, title: row.title, start: row.start_datetime, end: row.end_datetime });
        });
    }

    calendar = new Calendar(document.getElementById('calendar'), {
        headerToolbar: {
            left: 'prev,next today',
            right: 'dayGridMonth,dayGridWeek,list',
            center: 'title',
        },
        selectable: true,
        themeSystem: 'bootstrap',
        events: events,
        editable: true,

        select: function(info) {
            var title = prompt('Introduceți titlul evenimentului:');
            var description = prompt('Introduceți descrierea evenimentului:');
            if (title) {
                $.ajax({
                    url: 'save_event.php',
                    method: 'POST',
                    data: {
                        title: title,
                        description: description,
                        start_datetime: info.startStr,
                        end_datetime: info.endStr,
                        calendar_code: "<?php echo $calendar_code; ?>" // Folosim calendar_code din PHP
                    },
                    success: function(response) {
                        alert('Evenimentul a fost adăugat cu succes');
                        calendar.refetchEvents();
                    },
                    error: function() {
                        alert('Eroare la salvarea evenimentului.');
                    }
                });
            }
            calendar.unselect();
        },

        eventClick: function(info) {
            var _details = $('#event-details-modal');
            var id = info.event.id;
            if (!!scheds[id]) {
                _details.find('#title').text(scheds[id].title);
                _details.find('#description').text(scheds[id].description);
                _details.find('#start').text(scheds[id].sdate);
                _details.find('#end').text(scheds[id].edate);
                _details.find('#edit,#delete').attr('data-id', id);
                _details.modal('show');
            } else {
                alert("Event is undefined");
            }
        },
        
        eventDidMount: function(info) {
            var title = prompt('Introduceți titlul evenimentului:');
            var description = prompt('Introduceți descrierea evenimentului:');
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
                        alert('Evenimentul a fost adăugat cu succes');
                        calendar.refetchEvents();
                    }
                });
            }
            calendar.unselect();
        }
    });

    calendar.render();
});

// Funcție pentru încărcarea calendarului
function loadCalendar() {
    var code = $('#calendar_code').val();
    $.ajax({
        url: 'get_schedule.php',
        method: 'POST',
        data: { code: code },
        success: function(response) {
            scheds = $.parseJSON(response);
            calendar.removeAllEvents();
            Object.keys(scheds).forEach(function(key) {
                var row = scheds[key];
                calendar.addEvent({
                    id: row.id,
                    title: row.title,
                    start: row.start_datetime,
                    end: row.end_datetime
                });
            });
        },
        error: function() {
            alert('Cod invalid sau evenimente inexistente');
        }
    });
}
