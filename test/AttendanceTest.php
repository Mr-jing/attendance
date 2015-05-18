<?php

require '../vendor/autoload.php';

use App\Models\Attendance\Attendance;


class AttendanceTest extends PHPUnit_Framework_TestCase {


    /**
     * @dataProvider amWorkDurationData
     */
    public function testGetAmWorkDuration($duration, $start, $end) {
        $this->assertSame($duration, Attendance::getAmWorkDuration($start, $end));
    }


    public function amWorkDurationData() {
        return array(
            array(0, '', ''),
            array(0, '09:00:00', ''),
            array(0, '', '09:00:00'),
            array(0, '06:00:00', '08:30:00'),
            array(0, '08:29:59', '08:30:00'),
            array(0, '08:30:00', '08:30:00'),
            array(1, '08:29:59', '08:30:01'),
            array(3600, '08:00:00', '09:30:00'),
            array(11700, '08:00:00', '11:45:00'),
            array(11700, '08:00:00', '12:00:00'),
            array(1800, '09:00:00', '09:30:00'),
            array(9900, '09:00:00', '11:45:00'),
            array(9900, '09:00:00', '12:00:00'),
            array(7200, '09:45:00', '11:45:00'),
            array(7200, '09:45:00', '15:45:00'),
            array(0, '11:45:00', '15:45:00'),
            array(0, '12:00:00', '18:45:00'),
        );
    }


    /**
     * @dataProvider pmWorkDurationData
     */
    public function testGetPmWorkDuration($duration, $start, $end) {
        $this->assertSame($duration, Attendance::getPmWorkDuration($start, $end));
    }


    public function pmWorkDurationData() {
        return array(
            array(0, '', ''),
            array(0, '09:00:00', ''),
            array(0, '', '09:00:00'),
            array(0, '08:00:00', '11:45:00'),
            array(0, '09:30:00', '13:15:00'),
            array(0, '09:30:00', '13:15:00'),
            array(1, '09:30:00', '13:15:01'),
            array(14400, '09:30:00', '17:15:00'),
            array(15300, '09:30:00', '17:30:00'),
            array(18900, '09:30:00', '18:30:00'),
            array(33300, '09:30:00', '22:30:00'),
            array(17100, '12:00:00', '18:00:00'),
            array(14400, '13:15:00', '17:15:00'),
            array(14400, '14:00:00', '18:00:00'),
        );
    }

}
