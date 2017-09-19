<?php
//循环触发 swoole_timer_tick
//单次触发 swoole_timer_after
swoole_timer_tick(50, function ($timer_id) {
    echo "数据测试--tick-50ms--".rand(100,9999)."\n";
});
swoole_timer_after(60, function () {
    echo "数据测试--after--60ms\n";
});