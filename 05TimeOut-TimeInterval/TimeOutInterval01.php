<?php
//循环触发 swoole_timer_tick
//单次触发 swoole_timer_after
swoole_timer_tick(2000, function ($timer_id) {
    echo "tick-2000ms\n";
});
swoole_timer_after(3000, function () {
    echo "after-3000ms\n";
});