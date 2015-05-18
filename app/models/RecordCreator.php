<?php

namespace App\Models;

use App\Models\Attendance\WorkdayAttendance;
use App\Models\Attendance\HolidayAttendance;


class RecordCreator {


    public static function create($data) {
        $record = Record::where('job_num', $data['job_num'])
                ->where('year', $data['year'])
                ->where('month', $data['month'])
                ->where('day', $data['day'])
                ->first();
        if (empty($record)) {
            $record = new Record();
        }

        $isHoliday = Holiday::isHoliday($data['year'], $data['month'], $data['day']);

        if ($isHoliday) {
            $attendance = new HolidayAttendance($data['start'], $data['end']);
        } else {
            $attendance = new WorkdayAttendance($data['start'], $data['end']);
        }

        $record->job_num = $data['job_num'];
        $record->year = $data['year'];
        $record->month = $data['month'];
        $record->day = $data['day'];
        $record->is_holiday = $isHoliday ? 1 : 0;
        $record->start_time = $attendance->getStartTime();
        $record->end_time = $attendance->getEndTime();
        $record->status = $attendance->getStatus();
        $record->work_time = $attendance->getWorkDuration();
        $record->overtime = $attendance->getOvertime();
        $record->overtime_start = $attendance->getOvertimeStartTime();

        return $record->save();
    }

}
