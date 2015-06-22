<?php

require '../vendor/autoload.php';

use App\Models\Attendance\WorkdayAttendance;


class WorkdayAttendanceTest extends PHPUnit_Framework_TestCase {


    /**
     * @dataProvider workDurationData
     */
    public function testGetWorkDuration($duration, $start, $end) {
        $workdayAttendance = new WorkdayAttendance($start, $end);
        $this->assertSame($duration, $workdayAttendance->getWorkDuration());
    }


    public function workDurationData() {
        return array(
            array(0, '', ''),
            array(0, '09:00:00', ''),
            array(0, '', '09:00:00'),
            array(0, '06:00:00', '08:29:59'),
            array(0, '06:00:00', '08:30:00'),
            array(1, '06:00:00', '08:30:01'),
            array(3600, '08:30:00', '09:30:00'),
            array(11700, '08:30:00', '11:45:00'),
            array(11700, '08:30:00', '13:15:00'),
            array(27000, '08:30:00', '17:30:00'),
            array(30600, '08:30:00', '18:30:00'),
            array(34200, '08:30:00', '19:30:00'),
            array(1, '09:30:00', '09:30:01'),
            array(8100, '09:30:00', '11:45:00'),
            array(8100, '09:30:00', '13:15:00'),
            array(23400, '09:30:00', '17:30:00'),
            array(27000, '09:30:00', '18:30:00'),
            array(30600, '09:30:00', '19:30:00'),
            array(0, '11:45:00', '11:45:01'),
            array(0, '11:45:00', '13:15:00'),
            array(15300, '11:45:00', '17:30:00'),
            array(18900, '11:45:00', '18:30:00'),
            array(22500, '11:45:00', '19:30:00'),
            array(1, '13:15:00', '13:15:01'),
            array(15300, '13:15:00', '17:30:00'),
            array(18900, '13:15:00', '18:30:00'),
            array(22500, '13:15:00', '19:30:00'),
            array(1, '17:30:00', '17:30:01'),
            array(3600, '17:30:00', '18:30:00'),
            array(7200, '17:30:00', '19:30:00'),
            array(1, '18:30:00', '18:30:01'),
            array(3600, '18:30:00', '19:30:00'),
            array(1800, '19:00:00', '19:30:00'),
            array(3600, '20:00:00', '21:00:00'),
        );
    }


    /**
     * @dataProvider overtimeData
     */
    public function testGetOvertime($duration, $start, $end) {
        $workdayAttendance = new WorkdayAttendance($start, $end);
        $this->assertSame($duration, $workdayAttendance->getOvertime());
    }


    public function overtimeData() {
        return array(
            array(0, '', ''),
            array(0, '09:00:00', ''),
            array(0, '', '09:00:00'),
            array(0, '06:00:00', '08:29:59'),
            array(0, '06:00:00', '08:30:00'),
            array(0, '06:00:00', '08:30:01'),
            array(0, '08:30:00', '09:30:00'),
            array(0, '08:30:00', '11:45:00'),
            array(0, '08:30:00', '13:15:00'),
            array(0, '08:30:00', '17:30:00'),
            array(3600, '08:30:00', '18:30:00'),
            array(7200, '08:30:00', '19:30:00'),
            array(0, '09:30:00', '09:30:01'),
            array(0, '09:30:00', '11:45:00'),
            array(0, '09:30:00', '13:15:00'),
            array(0, '09:30:00', '17:30:00'),
            array(0, '09:30:00', '18:30:00'),
            array(3600, '09:30:00', '19:30:00'),
            array(0, '11:45:00', '11:45:01'),
            array(0, '11:45:00', '13:15:00'),
            array(0, '11:45:00', '17:30:00'),
            array(0, '11:45:00', '18:30:00'),
            array(3600, '11:45:00', '19:30:00'),
            array(0, '13:15:00', '13:15:01'),
            array(0, '13:15:00', '17:30:00'),
            array(0, '13:15:00', '18:30:00'),
            array(3600, '13:15:00', '19:30:00'),
            array(0, '17:30:00', '17:30:01'),
            array(0, '17:30:00', '18:30:00'),
            array(3600, '17:30:00', '19:30:00'),
            array(1, '18:30:00', '18:30:01'),
            array(3600, '18:30:00', '19:30:00'),
            array(1800, '19:00:00', '19:30:00'),
            array(3600, '20:00:00', '21:00:00'),
        );
    }


    /**
     * @dataProvider statusData
     */
    public function testGetStatus($status, $start, $end) {
        $workdayAttendance = new WorkdayAttendance($start, $end);
        $this->assertSame($status, $workdayAttendance->getStatus());
    }


    public function statusData() {
        return array(
            array(WorkdayAttendance::NORMAL, '09:15:00', '18:45:00'),
            array(WorkdayAttendance::ABNORMAL, '', '09:00:00'),
            array(WorkdayAttendance::BE_LATE, '09:30:01', '19:00:00'),
            array(WorkdayAttendance::LEAVE_EARLY, '09:00:00', '17:30:00'),
            array(WorkdayAttendance::BE_LATE_LEAVE_EARLY, '10:00:00', '18:29:59'),
        );
    }


    /**
     * @dataProvider overtimeStartTimeData
     */
    public function testGetOvertimeStartTime($ret, $start, $end) {
        $workdayAttendance = new WorkdayAttendance($start, $end);
        $this->assertSame($ret, $workdayAttendance->getOvertimeStartTime());
    }


    public function overtimeStartTimeData() {
        return array(
            array(null, '', '20:00:00'),
            array(null, '09:00:00', ''),
            array(null, '', ''),
            array(null, '09:00:00', '18:00:00'),
            array('18:00:00', '09:00:00', '18:00:01'),
        );
    }

}
