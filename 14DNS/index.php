<?php
swoole_async_dns_lookup("www.jiagongwu.com", function ($host, $ip) {
    echo "$host\n$ip";
});