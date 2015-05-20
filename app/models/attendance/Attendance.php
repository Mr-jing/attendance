<?php

namespace App\Models\Attendance;

use Carbon\Carbon;


abstract class Attendance
{

    // 正常
    const NORMAL = 0;
    // 异常
    const ABNORMAL = 1;
    // 迟到
    const BE_LATE = 2;
    // 早退
    const LEAVE_EARLY = 3;
    // 迟到并且早退
    const BE_LATE_LEAVE_EARLY = 4;
    // 休息
    const REST = 5;
    // 休息日加班
    const OVERTIME_IN_HOLIDAY = 6;

    // 最早上班时间
    public static $minComeTime = '08:30:00';
    // 最迟上班时间
    public static $maxComeTime = '09:30:00';
    // 中午午餐时间
    public static $lunchTime = '11:45:00';
    // 下午上班时间
    public static $afternoonWorkTime = '13:15:00';
    // 最早正常下班时间
    public static $minLeaveTime = '17:30:00';
    // 最晚正常上班时间
    public static $maxLeaveTime = '18:30:00';
    protected $startTime;
    protected $endTime;


    public function __construct($startTime, $endTime)
    {
        $this->startTime = '' === $startTime ? null : $startTime;
        $this->endTime = '' === $endTime ? null : $endTime;
    }


    public function getStartTime()
    {
        return $this->startTime;
    }


    public function getEndTime()
    {
        return $this->endTime;
    }


    public static function getFriendlyStatus($status)
    {
        $ret = '';
        switch ($status) {
            case self::NORMAL:
                $ret = '正常';
                break;
            case self::ABNORMAL:
                $ret = '异常';
                break;
            case self::BE_LATE:
                $ret = '迟到';
                break;
            case self::LEAVE_EARLY:
                $ret = '早退';
                break;
            case self::BE_LATE_LEAVE_EARLY:
                $ret = '迟到并且早退';
                break;
            case self::REST:
                $ret = '休息';
                break;
            case self::OVERTIME_IN_HOLIDAY:
                $ret = '休息日加班';
                break;
            default:
                break;
        }
        return $ret;
    }


    /**
     * 获取全天上班时长
     *
     * @return int
     */
    abstract public function getWorkDuration();


    /**
     * 获取加班时长
     *
     * @return int
     */
    abstract public function getOvertime();


    /**
     * 获取开始加班的时间
     *
     * @return string|null
     */
    abstract public function getOvertimeStartTime();


    /**
     * 获取考勤状态
     *
     * @return int
     */
    abstract public function getStatus();


    /**
     * 获取上午上班时长
     *
     * @param string $start
     * @param string $end
     * @return int
     */
    public static function getAmWorkDuration($start, $end)
    {
        if (is_null($start) || is_null($end) || '' === $start || '' === $end) {
            return 0;
        }

        $startTime = Carbon::parse($start);
        $endTime = Carbon::parse($end);
        $minComeTimeCarbon = Carbon::parse(self::$minComeTime);
        $lunchTimeCarbon = Carbon::parse(self::$lunchTime);

        if ($startTime->lt($minComeTimeCarbon)) {
            if ($endTime->lt($minComeTimeCarbon)) {
                return 0;
            } else if ($endTime->gte($minComeTimeCarbon) && $endTime->lt($lunchTimeCarbon)) {
                return $endTime->diffInSeconds($minComeTimeCarbon);
            } else {
                return $lunchTimeCarbon->diffInSeconds($minComeTimeCarbon);
            }
        } else if ($startTime->gte($minComeTimeCarbon) && $startTime->lt($lunchTimeCarbon)) {
            if ($endTime->gte($minComeTimeCarbon) && $endTime->lt($lunchTimeCarbon)) {
                return $endTime->diffInSeconds($startTime);
            } else {
                return $lunchTimeCarbon->diffInSeconds($startTime);
            }
        } else {
            return 0;
        }
    }


    /**
     * 获取下午上班时长
     *
     * @param string $start
     * @param string $end
     * @return int
     */
    public static function getPmWorkDuration($start, $end)
    {
        if (is_null($start) || is_null($end) || '' === $start || '' === $end) {
            return 0;
        }

        $startTime = Carbon::parse($start);
        $endTime = Carbon::parse($end);
        $afternoonWorkTimeCarbon = Carbon::parse(self::$afternoonWorkTime);

        if ($endTime->lte($afternoonWorkTimeCarbon)) {
            return 0;
        } else {
            if ($startTime->lt($afternoonWorkTimeCarbon)) {
                return $endTime->diffInSeconds($afternoonWorkTimeCarbon);
            } else {
                return $endTime->diffInSeconds($startTime);
            }
        }
    }

}
