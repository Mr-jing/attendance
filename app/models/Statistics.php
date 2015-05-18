<?php

namespace App\Models;


class Statistics {

    protected $user;
    protected $year;
    protected $month;


    public function __construct(User $user, $year, $month) {
        $this->user = $user;
        $this->year = $year;
        $this->month = $month;
    }


    /**
     * 获取考勤记录
     * 
     * @return array
     */
    public function getRecords() {
        $result = $this->user->records()
                ->where('year', $this->year)
                ->where('month', $this->month)
                ->get()
                ->toArray();
        return $result;
    }


    /**
     * 获取加班超过一个小时的考勤记录
     * 
     * @return array
     */
    public function getOvertimeRecords() {
        $result = $this->user->records()
                ->select('year', 'month', 'day', 'overtime_start', 'end_time', 'overtime')
                ->where('year', $this->year)
                ->where('month', $this->month)
                ->where('overtime', '>=', 3600)
                ->get()
                ->toArray();
        return $result;
    }


    /**
     * 获取加班超过20:00:00的考勤记录
     * 
     * @return array
     */
    public function getMealAllowanceRecords() {
        $result = $this->user->records()
                ->select('year', 'month', 'day', 'overtime_start', 'end_time')
                ->where('year', $this->year)
                ->where('month', $this->month)
                ->where('end_time', '>=', '20:00:00')
                ->get()
                ->toArray();
        return $result;
    }


    /**
     * 是否全勤
     * 
     * @return bool
     */
    public function isAllTsutomu() {
        // 该月工作日天数
        $workdays = Holiday::getWorkdays($this->year, $this->month);

        // 该月实际正常工作的天数
        $normalWorkdays = $this->user->records()
                ->where('year', $this->year)
                ->where('month', $this->month)
                ->where('is_holiday', 0)
                ->where('status', 0)
                ->count();

        return $workdays === $normalWorkdays;
    }

}
