<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina de Start - Calendar</title>
    <link rel="stylesheet" href="./css/home.css">
    <script src="./js/jquery-3.6.0.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
</head>
<body>
<div class="container d-flex align-items-center justify-content-center vh-100">
    <!-- Image Container -->
    <div class="image-container" id="image-container">
        <img src="elements/corp.jpg" alt="Decorative Image">
    </div>

    <!-- Card container for form content -->
    <div class="card p-4 shadow" style="width: 100%; max-width: 400px;" id="form-container">
        <h3 class="text-center mb-4">Bine ați venit!</h3>
        <p class="text-center">Alegeți una dintre opțiunile de mai jos:</p>
        <div class="mt-3">
            <button class="btn btn-primary w-100 mb-3" id="create-calendar-btn">Creează un calendar nou</button>
            <button class="btn btn-secondary w-100" id="access-calendar-btn">Accesează un calendar existent</button>
        </div>
        <div id="access-calendar-form" class="mt-4" style="display: none;">
            <form action="calendar.php" method="get">
                <div class="form-group">
                    <label for="calendar-code">Introdu codul calendarului:</label>
                    <input type="text" class="form-control" name="calendar_code" id="calendar-code" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-3">Accesează Calendarul</button>
            </form>
        </div>
    </div>
</div>


    <script>
        $(document).ready(function() {
            $('#create-calendar-btn').click(function() {
                window.location.href = 'create_calendar.php';
            });
            
            $('#access-calendar-btn').click(function() {
                $('#access-calendar-form').slideToggle();
            });
        });
    </script>
</body>
</html>