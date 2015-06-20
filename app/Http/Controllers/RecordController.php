<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Statistics;
use Carbon\Carbon;


class RecordController extends Controller
{

    public function __construct()
    {
    }


    public function getIndex()
    {
        $jobNum = \Request::cookie('job_num');
        if (!isset($jobNum) || !is_numeric($jobNum)) {
            return redirect('/login');
        }
        return redirect('/record/' . date('Y/m'));
    }


    public function getRecord($year, $month)
    {
        $jobNum = \Request::cookie('job_num');
        if (!isset($jobNum) || !is_numeric($jobNum)) {
            return redirect('/login');
        }

        $user = User::findOrFail($jobNum);

        $statistics = new Statistics($user, $year, $month);
        $records = $statistics->getRecords();
        $overtimeRecords = $statistics->getOvertimeRecords();
        $mealAllowanceRecords = $statistics->getMealAllowanceRecords();

        if (Carbon::now()->gt(Carbon::create($year, $month, 1, 8, 0, 0)->addMonth())) {
            $allTsutomu = $statistics->isAllTsutomu() ? '是' : '否';
        } else {
            $allTsutomu = '还没有到统计的时候';
        }

        $vars = compact(
            'user',
            'month',
            'records',
            'overtimeRecords',
            'mealAllowanceRecords',
            'allTsutomu'
        );
        return view('record.record', $vars);
    }
}
