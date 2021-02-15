<?php
$codePage=2;  
$title ="Planning de l'agent";
ob_start();
?>
<script>
	
	
      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, 
        {
        	initialView: 'timeGridWeek',
          locale : 'fr',
          headerToolbar : 
          {
          	start :'prev,next today',
          	center : 'title',
          	end : 'dayGridMonth,timeGridWeek'
          },
            hiddenDays:[0],
            slotMintime: 
          {
          	duration: "6:00:00"
          },
          slotMaxtime: 
          {
          	duration: "19:00:00"
          },
          height:820,
         	buttonText:
          {
  				  today:    'Aujourd\'hui',
  				  month:    'Mois',
  				  week:     'Semaine',
  				  day:      'Jour'
          },
          events : <?php echo $statusEvenements;  ?>,
          eventClick: function(info)
          {
            confirm(info.event.start);
          }
        });
        calendar.updateSize();
        calendar.render();
      });

    </script>
 <div class="row g-3 justify-content-center">
 	<div>
 		<h3>Planning Hebdomadaire / Mensuel de l'agent</h3>
 	</div>
 </div>
<div class="row g-2 justify-content-center">
	<div class="col-lg-10">
		<div id='calendar'></div>
	</div>
</div>

<?php
$content = ob_get_clean();
require ('template.php');
?>