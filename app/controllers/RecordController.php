<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Statistics;
use App\Models\Record;
use App\Models\RecordCreator;
use App\Models\UserCreator;
use Carbon\Carbon;


class RecordController {


    public function getRecord() {
        if (!isset($_COOKIE['job_num']) ||
                !is_numeric($_COOKIE['job_num'])) {
            header('Location:login');
            exit;
        }
        header('Location:' . BASE_URL . '/record/' . date('Y/m'));
        exit;
    }


    public function getIndex() {
        if (!isset($_COOKIE['job_num']) ||
                !is_numeric($_COOKIE['job_num'])) {
            header('Location:login');
            exit;
        }

        $user = User::find($_COOKIE['job_num']);
        if (empty($user)) {
            header('Location:login');
            exit;
        }

        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $yearAndMonth = array_slice(explode('/', $uri), -2);
        $year = $yearAndMonth[0];
        $month = $yearAndMonth[1];

        $statistics = new Statistics($user, $year, $month);
        $records = $statistics->getRecords();
        $overtimeRecords = $statistics->getOvertimeRecords();
        $mealAllowanceRecords = $statistics->getMealAllowanceRecords();

        if (Carbon::now()->gt(Carbon::create($year, $month, 1, 8, 0, 0)->addMonth())) {
            $allTsutomu = $statistics->isAllTsutomu() ? '是' : '否';
        } else {
            $allTsutomu = '还没有到统计的时候';
        }

        require VIEW_PATH . '/record.php';
    }


    public function postStore() {
        if (!isset($_POST['job_num']) ||
                !isset($_POST['name']) ||
                !isset($_POST['year']) ||
                !isset($_POST['month']) ||
                !isset($_POST['records'])) {
            exit;
        }
        UserCreator::create(array(
            'job_num' => intval($_POST['job_num']),
            'name' => trim($_POST['name']),
        ));
        return;
        $records = json_decode($_POST['records'], true);
        foreach ($records as $record) {
            $record['job_num'] = intval($_POST['job_num']);
            $record['year'] = intval($_POST['year']);
            $record['month'] = intval($_POST['month']);
            RecordCreator::create($record);
        }
    }


    public function postAjaxMonth() {
        if (isset($_REQUEST['year'])) {
            $year = $_REQUEST['year'];
        } else {
            $year = date('Y');
        }

        $user = User::find($_COOKIE['job_num']);
        if (empty($user)) {
            $ret = json_encode(array(
                'status' => false,
                'msg' => '请先登录',
                'data' => array(),
            ));
            exit($ret);
        }

        $months = Record::where('job_num', $user->job_num)
                ->where('year', $year)
                ->lists('month');
        $ret = json_encode(array(
            'status' => true,
            'msg' => '获取成功',
            'data' => array_unique($months),
        ));
        exit($ret);
    }

}
