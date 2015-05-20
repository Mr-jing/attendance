<?php

namespace App\Models\Attendance;

use Carbon\Carbon;


class WorkdayAttendance extends Attendance
{


    /**
     * 获取全天上班时长
     *
     * @return int
     */
    public function getWorkDuration()
    {
        $start = $this->startTime;
        $end = $this->endTime;

        return self::getAmWorkDuration($start, $end) + self::getPmWorkDuration($start, $end);
    }


    /**
     * 获取加班时长
     *
     * @return int
     */
    public function getOvertime()
    {
        $start = $this->startTime;
        $end = $this->endTime;

        if (is_null($start) || is_null($end)) {
            return 0;
        }

        $startTime = Carbon::parse($start);
        $endTime = Carbon::parse($end);

        // 正常下班时间
        $normalTime = self::getAllowableClosingTime($start);

        if ($endTime->lte($normalTime)) {
            return 0;
        }
        if ($startTime->lte($normalTime)) {
            return $endTime->diffInSeconds($normalTime);
        } else {
            return $endTime->diffInSeconds($startTime);
        }
    }


    /**
     * 获取正常下班时间
     *
     * @param string $start
     * @return Carbon\Carbon
     */
    public static function getAllowableClosingTime($start)
    {
        $startTime = Carbon::parse($start);

        $minComeTimeCarbon = Carbon::parse(self::$minComeTime);
        $maxComeTimeCarbon = Carbon::parse(self::$maxComeTime);

        // 计算正常下班时间
        if ($startTime->lt($minComeTimeCarbon)) {
            $normalTime = Carbon::parse(self::$minLeaveTime);
        } else if ($startTime->gte($minComeTimeCarbon) && $startTime->lt($maxComeTimeCarbon)) {
            $normalTime = Carbon::parse($start)->addHours(9);
        } else {
            $normalTime = Carbon::parse(self::$maxLeaveTime);
        }

        return $normalTime;
    }


    /**
     * 获取考勤状态
     *
     * @return int
     */
    public function getStatus()
    {
        $start = $this->startTime;
        $end = $this->endTime;

        if (is_null($start) || is_null($end)) {
            return self::ABNORMAL;
        }

        $isBeLate = self::isBeLate($start);
        $isLeaveEarly = self::isLeaveEarly($start, $end);

        if (!$isBeLate && !$isLeaveEarly) {
            return self::NORMAL;
        } else if ($isBeLate && !$isLeaveEarly) {
            return self::BE_LATE;
        } else if (!$isBeLate && $isLeaveEarly) {
            return self::LEAVE_EARLY;
        } else {
            return self::BE_LATE_LEAVE_EARLY;
        }
    }


    /**
     * 获取开始加班的时间
     *
     * @return string|null
     */
    public function getOvertimeStartTime()
    {
        if (is_null($this->startTime) || is_null($this->endTime)) {
            return null;
        }
        // 正常下班时间
        $allowableClosingTime = $this->getAllowableClosingTime($this->startTime);

        // 如果最后打卡时间比正常下班时间晚，正常上班时间就是开始加班的时间
        if ($allowableClosingTime->lt(Carbon::parse($this->endTime))) {
            return $allowableClosingTime->toTimeString();
        }
        return null;
    }


    /**
     * 是否迟到
     *
     * @param string $start
     * @return boolean
     */
    public static function isBeLate($start)
    {
        $startTime = Carbon::parse($start);
        $maxComeTime = Carbon::parse(self::$maxComeTime);

        // 迟到
        if ($startTime->gt($maxComeTime)) {
            return true;
        }
        return false;
    }


    /**
     * 是否早退
     *
     * @param string $start
     * @param string $end
     * @return boolean
     */
    public static function isLeaveEarly($start, $end)
    {
        // 正常下班时间
        $normalTime = self::getAllowableClosingTime($start);

        // 实际下班时间
        $endTime = Carbon::parse($end);

        if ($endTime->lt($normalTime)) {
            return true;
        }
        return false;
    }

}
