<?php
return [
    'working_days'   => [2, 3, 4, 5, 6], // 0=Sun … 6=Sat  (Tue–Sat)
    'start_time'     => '09:00',
    'end_time'       => '19:00',
    'slot_interval'  => 30,   // minutes between slot start times
    'max_weeks_ahead'=> 8,    // how far clients can book in advance
    'blocked_dates'  => [],   // e.g. ['2026-07-04', '2026-12-25']
];
