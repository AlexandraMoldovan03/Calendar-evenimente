<?php require_once('db-connect.php') ?>
<!DOCTYPE html>
<html lang="ro">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programare</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./fullcalendar/lib/main.min.css">
    <script src="./js/jquery-3.6.0.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./fullcalendar/lib/main.min.js"></script>
    <style>
        :root {
            --bs-success-rgb: 71, 222, 152 !important;
        }
        html, body {
            height: 100%;
            width: 100%;
            font-family: Arial, sans-serif;
        }
        .btn-info.text-light:hover, .btn-info.text-light:focus {
            background: #000;
        }
        table, tbody, td, tfoot, th, thead, tr {
            border-color: #ededed !important;
            border-style: solid;
            border-width: 1px !important;
        }
    </style>
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark bg-gradient" id="topNavBar">
        <div class="container">
            <a class="navbar-brand" href="#">
                Planificator
            </a>
        </div>
    </nav>
    <div class="container py-5" id="page-container">
        <div class="row">
            <div class="col-md-9">
                <div id="calendar"></div>
            </div>
            <div class="col-md-3">
                <div class="card rounded-0 shadow">
                    <div class="card-header bg-gradient bg-primary text-light">
                        <h5 class="card-title">Formular Programare</h5>
                    </div>
                    <div class="card-body">
                        <div class="container-fluid">
                            <form action="save_schedule.php" method="post" id="schedule-form">
                                <input type="hidden" name="id" value="">
                                <div class="form-group mb-2">
                                    <label for="title" class="control-label">Titlu</label>
                                    <input type="text" class="form-control form-control-sm rounded-0" name="title" id="title" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="description" class="control-label">Descriere</label>
                                    <textarea rows="3" class="form-control form-control-sm rounded-0" name="description" id="description" required></textarea>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="start_datetime" class="control-label">Început</label>
                                    <input type="datetime-local" class="form-control form-control-sm rounded-0" name="start_datetime" id="start_datetime" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="end_datetime" class="control-label">Sfârșit</label>
                                    <input type="datetime-local" class="form-control form-control-sm rounded-0" name="end_datetime" id="end_datetime" required>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-center">
                            <button class="btn btn-primary btn-sm rounded-0" type="submit" form="schedule-form"><i class="fa fa-save"></i> Salvează</button>
                            <button class="btn btn-default border btn-sm rounded-0" type="reset" form="schedule-form"><i class="fa fa-reset"></i> Anulează</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Detalii Eveniment -->
    <div class="modal fade" tabindex="-1" data-bs-backdrop="static" id="event-details-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-0">
                <div class="modal-header rounded-0">
                    <h5 class="modal-title">Detalii Programare</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body rounded-0">
                    <div class="container-fluid">
                        <dl>
                            <dt class="text-muted">Titlu</dt>
                            <dd id="event-title" class="fw-bold fs-4"></dd>
                            <dt class="text-muted">Descriere</dt>
                            <dd id="event-description" class=""></dd>
                            <dt class="text-muted">Început</dt>
                            <dd id="event-start" class=""></dd>
                            <dt class="text-muted">Sfârșit</dt>
                            <dd id="event-end" class=""></dd>
                        </dl>
                    </div>
                </div>
                <div class="modal-footer rounded-0">
                    <div class="text-end">
                        <button type="button" class="btn btn-primary btn-sm rounded-0" id="edit" data-id="">Editează</button>
                        <button type="button" class="btn btn-danger btn-sm rounded-0" id="delete" data-id="">Șterge</button>
                        <button type="button" class="btn btn-secondary btn-sm rounded-0" data-bs-dismiss="modal">Închide</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php 
    $schedules = $conn->query("SELECT * FROM `schedule_list`");
    $sched_res = [];
    foreach($schedules->fetch_all(MYSQLI_ASSOC) as $row){
        $row['sdate'] = date("d.m.Y H:i", strtotime($row['start_datetime']));
        $row['edate'] = date("d.m.Y H:i", strtotime($row['end_datetime']));
        $sched_res[$row['id']] = $row;
    }
    if(isset($conn)) $conn->close();
    ?>
</body>
<script>
    var scheds = <?= json_encode($sched_res) ?>;

    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: Object.values(scheds).map(function(sched) {
                return {
                    id: sched.id,
                    title: sched.title,
                    start: sched.start_datetime,
                    end: sched.end_datetime
                };
            }),
            eventClick: function(info) {
                $('#event-details-modal').modal('show');
                $('#event-details-modal #event-title').text(info.event.title);
                $('#event-details-modal #event-description').text(scheds[info.event.id].description);
                $('#event-details-modal #event-start').text(scheds[info.event.id].sdate);
                $('#event-details-modal #event-end').text(scheds[info.event.id].edate);
                $('#event-details-modal #edit').data('id', info.event.id);
                $('#event-details-modal #delete').data('id', info.event.id);
            }
        });
        calendar.render();
    });
</script>
<script src="./js/script.js"></script>
</html>
