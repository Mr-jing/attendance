<?php

function getFriendlyStatus($status)
{
    return App\Models\Attendance\Attendance::getFriendlyStatus($status);
}


function cssStatus($status)
{
    $ret = '';
    switch ($status) {
        case App\Models\Attendance\Attendance::ABNORMAL:
            $ret = 'danger';
            break;
        case App\Models\Attendance\Attendance::BE_LATE:
        case App\Models\Attendance\Attendance::LEAVE_EARLY:
        case App\Models\Attendance\Attendance::BE_LATE_LEAVE_EARLY:
            $ret = 'warning';
            break;
        default:
            break;
    }
    return $ret;
}

function post($url, $data)
{
    $cu = curl_init();

    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $data,
    );

    curl_setopt_array($cu, $options);
    curl_exec($cu);
    $code = curl_getinfo($cu, CURLINFO_HTTP_CODE);
    curl_close($cu);

    return $code;
}


function last_week_days()
{
    // 上周的第一天
    $date = \Carbon\Carbon::now()->subWeek()->startOfWeek();

    $dates = array();
    $dates[] = $date->copy();
    for ($i = 2; $i <= 7; $i++) {
        $dates[] = $date->addDay()->copy();
    }
    return $dates;
}


function last_month_days()
{
    // 本月第一天再减去一天，就是上月的最后一天。最后重置为当月第一天。
    $date = \Carbon\Carbon::now()->startOfMonth()->subDay()->startOfMonth();

    // 当月总天数
    $days = $date->daysInMonth;

    $dates = array();
    $dates[] = $date->copy();
    for ($i = 2; $i <= $days; $i++) {
        $dates[] = $date->addDay()->copy();
    }
    return $dates;
}
