<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Carbon\Carbon;


class Holiday extends Eloquent
{

    protected $table = 'holidays';
    public $timestamps = false;


    public static function isHoliday($year, $month, $day)
    {
        $date = Carbon::create($year, $month, $day)->toDateString();
        if (self::where('holiday', '=', $date)->first()) {
            return true;
        }
        return false;
    }


    public static function getWorkdays($year, $month)
    {
        // 该月第一天
        $dt = Carbon::create($year, $month, 1);

        // 该月总天数
        $days = $dt->daysInMonth;

        // 该月休息日天数
        $holidays = self::where('holiday', '>=', $dt->toDateString())
            ->where('holiday', '<=', sprintf('%d-%d-%02d', $year, $month, $days))
            ->count();

        // 该月该月工作日天数
        return $days - $holidays;
    }

}
