<?php

namespace App\Models\Attendance;

use Carbon\Carbon;


class HolidayAttendance extends Attendance {


    /**
     * 获取全天上班时长
     * 
     * @return int
     */
    public function getWorkDuration() {
        $start = $this->startTime;
        $end = $this->endTime;

        return self::getAmWorkDuration($start, $end) + self::getPmWorkDuration($start, $end);
    }


    /**
     * 获取加班时长
     * 
     * @return int
     */
    public function getOvertime() {
        // 休息日加班时长就等于全天工作时长
        return self::getWorkDuration();
    }


    /**
     * 获取考勤状态
     * 
     * @return int
     */
    public function getStatus() {
        $start = $this->startTime;
        $end = $this->endTime;

        if (is_null($start) && is_null($end)) {
            // 没有打卡，正常休息
            return self::REST;
        } else if (!is_null($start) && !is_null($end)) {
            if (self::getOvertime($start, $end) > 0) {
                return self::OVERTIME_IN_HOLIDAY;
            } else {
                // 打卡2次，但是不在正常工作时间内
                return self::ABNORMAL;
            }
        } else {
            // 只打卡1次，异常
            return self::ABNORMAL;
        }
    }


    /**
     * 获取开始加班的时间
     * 
     * @return string|null
     */
    public function getOvertimeStartTime() {
        if (is_null($this->startTime) || is_null($this->endTime)) {
            return null;
        }

        $startTimeCarbon = Carbon::parse($this->startTime);
        $endTimeCarbon = Carbon::parse($this->endTime);
        $minComeTimeCarbon = Carbon::parse(self::$minComeTime);

        if ($startTimeCarbon->lt($minComeTimeCarbon)) {
            if ($endTimeCarbon->lt($minComeTimeCarbon)) {
                return null;
            } else {
                return self::$minComeTime;
            }
        } else {
            return $this->startTime;
        }
    }

}
