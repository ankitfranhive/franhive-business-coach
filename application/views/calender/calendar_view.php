<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Calendar</title>

    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500&display=swap" rel="stylesheet">

    <link href="<?= base_url('vendors/fullcalendar/packages/core/main.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('vendors/fullcalendar/packages/daygrid/main.css') ?>" rel="stylesheet" />

    <link rel="stylesheet" href="<?= base_url('vendors/css/style.css') ?>">

    <?php $this->load->view('includes/header'); ?>

    <style>
        /* Page background */
        body {
            background: #f4f7fb;
        }

        /* Main Calendar Card */
        .cal-card {
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(20, 20, 20, 0.08);
            overflow: hidden;
        }

        /* Header */
        .cal-header {
            padding: 18px 18px 12px 18px;
            background: linear-gradient(135deg, #fff7e6, #ffffff);
            border-bottom: 1px solid #f0f0f0;
        }

        .cal-header h4 {
            margin: 0;
            font-weight: 600;
            color: #222;
        }

        .cal-subtitle {
            margin-top: 4px;
            font-size: 13px;
            color: #666;
        }

        /* Top controls row */
        .cal-controls {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 14px;
        }

        /* Filter toggles */
        .cal-filters {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
        }

        .filter-pill {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            border: 1px solid #e9e9e9;
            background: #fff;
            cursor: pointer;
            user-select: none;
            transition: 0.15s ease;
        }

        .filter-pill:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.06);
        }

        .filter-pill input {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        /* Legend */
        .cal-legend {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 12px;
            font-size: 13px;
            color: #444;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: 999px;
            background: #fafafa;
            border: 1px solid #f0f0f0;
        }

        .legend-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        /* FullCalendar tweaks */
        #calendar {
            padding: 14px;
        }

        .fc .fc-toolbar {
            flex-wrap: wrap;
            gap: 10px;
        }

        .fc .fc-toolbar-title {
            font-size: 18px;
            font-weight: 600;
            color: #222;
        }

        .fc-button {
            border-radius: 10px !important;
            padding: 6px 10px !important;
        }

        /* Softer day cell */
        .fc .fc-daygrid-day-frame {
            border-radius: 10px;
            transition: 0.15s ease;
        }

        .fc .fc-daygrid-day-frame:hover {
            background: rgba(0,0,0,0.03);
        }

        /* Highlight days that have events */
        .fc-daygrid-day.has-event .fc-daygrid-day-frame {
            background: rgba(252, 178, 43, 0.10); /* soft yellow */
        }

        /* Events look nicer */
        .fc-daygrid-event {
            border-radius: 10px !important;
            padding: 3px 6px !important;
            font-size: 12px;
            border: none !important;
        }

        /* Make mobile padding smaller */
        @media (max-width: 576px) {
            #calendar {
                padding: 10px;
            }
            .cal-header {
                padding: 14px 14px 10px 14px;
            }
            .fc .fc-toolbar-title {
                font-size: 16px;
            }
        }
    </style>
</head>

<body>
<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title">
                        <h4>Calendar</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="cal-card mb-30">
            <div class="cal-header">
                <h4>Holidays & Festivals</h4>
                <div class="cal-subtitle">
                    Indian Festivals, Global Holidays, and Australian Holidays — filtered and color coded
                </div>

                <div class="cal-controls">
                    <!-- Filters -->
                    <div class="cal-filters">
                        <label class="filter-pill">
                            <input type="checkbox" id="catIndia" checked>
                            <span>Indian Festivals</span>
                        </label>

                        <label class="filter-pill">
                            <input type="checkbox" id="catGlobal" checked>
                            <span>Global Holidays</span>
                        </label>

                        <label class="filter-pill">
                            <input type="checkbox" id="catAu" checked>
                            <span>Australian Holidays</span>
                        </label>
                    </div>

                    <!-- Legend -->
                    <div class="cal-legend">
                        <div class="legend-item">
                            <span class="legend-dot" style="background:#f39c12;"></span>
                            Indian
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot" style="background:#3498db;"></span>
                            Global
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot" style="background:#2ecc71;"></span>
                            Australia
                        </div>
                    </div>
                </div>
            </div>

            <div id="calendar"></div>
        </div>

    </div>
</div>

<?php $this->load->view('includes/footer'); ?>

<script src="<?= base_url('vendors/scripts/popper.min.js') ?>"></script>
<script src="<?= base_url('vendors/fullcalendar/packages/core/main.js') ?>"></script>
<script src="<?= base_url('vendors/fullcalendar/packages/interaction/main.js') ?>"></script>
<script src="<?= base_url('vendors/fullcalendar/packages/daygrid/main.js') ?>"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    function categoryEnabled(cat) {
        if (cat === 'INDIA_FESTIVAL') return document.getElementById('catIndia').checked;
        if (cat === 'GLOBAL_HOLIDAY') return document.getElementById('catGlobal').checked;
        if (cat === 'AU_HOLIDAY')     return document.getElementById('catAu').checked;
        return true;
    }

    var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: ['interaction', 'dayGrid'],
        timeZone: 'local',
        initialView: 'dayGridMonth',
        fixedWeekCount: false,

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek,dayGridDay'
        },

        editable: false,
        eventLimit: true,
        height: 'auto',

        events: function(fetchInfo, successCallback, failureCallback) {
            var visibleYear = fetchInfo.start.getFullYear();

            fetch("<?= base_url('calendar/events') ?>?year=" + visibleYear)
                .then(r => r.json())
                .then(allEvents => {
                    var filtered = allEvents.filter(ev => categoryEnabled(ev.extendedProps?.category));
                    successCallback(filtered);
                })
                .catch(err => failureCallback(err));
        },

        // ✅ Add background highlight on days which have events
        datesSet: function() {
            setTimeout(markEventDays, 200);
        },
        eventDidMount: function() {
            setTimeout(markEventDays, 200);
        }
    });

    calendar.render();

    function markEventDays() {
        // remove old marks
        document.querySelectorAll('.fc-daygrid-day.has-event').forEach(el => el.classList.remove('has-event'));

        // mark days for current visible events
        var events = calendar.getEvents();
        events.forEach(function(ev) {
            var d = ev.start;
            if (!d) return;

            // Format YYYY-MM-DD
            var yyyy = d.getFullYear();
            var mm = String(d.getMonth()+1).padStart(2,'0');
            var dd = String(d.getDate()).padStart(2,'0');
            var dateStr = yyyy + "-" + mm + "-" + dd;

            var cell = document.querySelector('.fc-daygrid-day[data-date="' + dateStr + '"]');
            if (cell) cell.classList.add('has-event');
        });
    }

    // Re-filter events when toggles change
    ['catIndia','catGlobal','catAu'].forEach(function(id) {
        var el = document.getElementById(id);
        el.addEventListener('change', function() {
            calendar.refetchEvents();
            setTimeout(markEventDays, 250);
        });
    });
});
</script>

<script src="<?= base_url('vendors/scripts/main.js') ?>"></script>
</body>
</html>
