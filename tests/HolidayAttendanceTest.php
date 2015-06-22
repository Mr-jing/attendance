<?php

require '../vendor/autoload.php';

use App\Models\Attendance\HolidayAttendance;


class HolidayAttendanceTest extends PHPUnit_Framework_TestCase {


    /**
     * @dataProvider workDurationData
     */
    public function testGetWorkDuration($duration, $start, $end) {
        $holidayAttendance = new HolidayAttendance($start, $end);
        $this->assertSame($duration, $holidayAttendance->getWorkDuration());
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
     * @dataProvider workDurationData
     */
    public function testGetOvertime($duration, $start, $end) {
        $holidayAttendance = new HolidayAttendance($start, $end);
        $this->assertSame($duration, $holidayAttendance->getOvertime());
    }


    /**
     * @dataProvider statusData
     */
    public function testGetStatus($status, $start, $end) {
        $holidayAttendance = new HolidayAttendance($start, $end);
        $this->assertSame($status, $holidayAttendance->getStatus());
    }


    public function statusData() {
        return array(
            array(HolidayAttendance::REST, '', ''),
            array(HolidayAttendance::ABNORMAL, '09:00:00', ''),
            array(HolidayAttendance::ABNORMAL, '', '09:00:00'),
            array(HolidayAttendance::OVERTIME_IN_HOLIDAY, '09:00:00', '17:30:00'),
            array(HolidayAttendance::ABNORMAL, '12:00:00', '13:00:00'),
        );
    }


    /**
     * @dataProvider overtimeStartTimeData
     */
    public function testGetOvertimeStartTime($ret, $start, $end) {
        $holidayAttendance = new HolidayAttendance($start, $end);
        $this->assertSame($ret, $holidayAttendance->getOvertimeStartTime());
    }


    public function overtimeStartTimeData() {
        return array(
            array(null, '', '20:00:00'),
            array(null, '09:00:00', ''),
            array(null, '', ''),
            array(null, '08:00:00', '08:29:59'),
            array('08:30:00', '08:00:00', '08:30:00'),
            array('08:30:00', '08:00:00', '18:00:00'),
            array('08:30:00', '08:30:00', '18:00:00'),
            array('09:00:00', '09:00:00', '18:00:00'),
            array('18:00:00', '18:00:00', '18:00:01'),
        );
    }

}
