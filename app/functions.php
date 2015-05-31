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

