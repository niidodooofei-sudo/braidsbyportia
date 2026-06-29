<?php
// If CMS has saved an availability override, use it; otherwise return built-in defaults
$_av_override = dirname(__DIR__) . '/data/availability.json';
if (is_file($_av_override)) {
    $_ = json_decode(file_get_contents($_av_override), true);
    if (is_array($_)) { unset($_av_override); return $_; }
}
unset($_av_override);

return [
    'working_days'    => [2, 3, 4, 5, 6], // 0=Sun … 6=Sat  (Tue–Sat)
    'start_time'      => '09:00',
    'end_time'        => '19:00',
    'slot_interval'   => 30,   // minutes between slot start times
    'max_weeks_ahead' => 8,    // how far clients can book in advance
    'blocked_dates'   => [],   // e.g. ['2026-07-04', '2026-12-25']
];
