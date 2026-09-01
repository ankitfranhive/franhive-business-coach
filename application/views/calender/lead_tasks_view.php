<!DOCTYPE html>
<html>
<!-- Required meta tags -->
<link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">

<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500&display=swap" rel="stylesheet">



<link href='vendors/fullcalendar/packages/core/main.css' rel='stylesheet' />
<link href='vendors/fullcalendar/packages/daygrid/main.css' rel='stylesheet' />


<!-- Bootstrap CSS -->

<!-- Style -->
<link rel="stylesheet" href="vendors/css/style.css">
<?php $this->load->view('includes/header'); ?>
<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title">
                        <h4>View Lead Tasks</h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 text-right">

                </div>
            </div>
        </div>
        <div class="pd-20 card-box mb-30">
            <div id='calendar'></div>
        </div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>
<script src="vendors/scripts/popper.min.js"></script>

<script src='vendors/fullcalendar/packages/core/main.js'></script>
<script src='vendors/fullcalendar/packages/interaction/main.js'></script>
<script src='vendors/fullcalendar/packages/daygrid/main.js'></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const events = <?= json_encode($event_list) ?>;
        // fetch('https://fullcalendar.io/api/demo-feeds/events.json').t
        console.log('events', typeof JSON.parse(events));
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: ['interaction', 'dayGrid',
                'dayGridWeek', 'dayGridDay'
            ],
            timeZone: 'UTC',
            initialView: 'dayGridWeek',
            headerToolbar: {
                left: 'prev,next',
                center: 'title',
                right: 'dayGridWeek,dayGridDay'
            },
            editable: false,
            eventLimit: true, // allow "more" link when too many events
            events: JSON.parse(events),
            dateClick: function(info) {
                console.log('Clicked on: ' + info.dateStr);
                console.log('Coordinates: ' + info.jsEvent.pageX + ',' + info.jsEvent.pageY);
                console.log('Current view: ' + info.view.type);
                // change the day's background color just for fun
                info.dayEl.style.backgroundColor = 'red';
            }
        });

        calendar.render();
    });
</script>

<script src="vendors/scripts/main.js"></script>

</html>