<?php

namespace App\Http\Controllers;


use App\Models\Rank;
use App\Models\User;

class PageController extends Controller
{
    public function getRank()
    {
        $redis = \App::make('RedisSingleton');
        $rank = new Rank($redis);

        // 各种排行榜
        $dayRankings = $rank->getYesterdayTop10();
        $weekRankings = $rank->getLastWeekTop10();
        $monthRankings = $rank->getLastMonthTop10();
        $yearRankings = $rank->getThisYearTop10();

        // 用户ID
        $uids = array_unique(array_merge(
            array_keys($dayRankings),
            array_keys($weekRankings),
            array_keys($monthRankings),
            array_keys($yearRankings)
        ));

        // 用户名
        $names = User::whereIn('job_num', $uids)->lists('name', 'job_num');

        $vars = compact(
            'dayRankings',
            'weekRankings',
            'monthRankings',
            'yearRankings',
            'names'
        );
        return view('page.rank', $vars);
    }
}
