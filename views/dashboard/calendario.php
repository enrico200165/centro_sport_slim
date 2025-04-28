<!-- Nota: in /public/assets/js/eventi.php dovremo mettere 
 una piccola API che restituisce gli eventi in formato JSON 
 per FullCalendar. 
-->
<?php include __DIR__ . '/../partials/header.php'; ?>

<h2>Calendario Eventi</h2>

<div id="calendar"></div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: '/public/assets/js/eventi.php' // Questa sarà la sorgente eventi (che creeremo a parte)
    });

    calendar.render();
});
</script>

<br><a href="/dashboard">Torna alla Dashboard</a>

<?php include __DIR__ . '/../partials/footer.php'; ?>
