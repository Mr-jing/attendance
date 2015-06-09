<?php

namespace App\Models;

use Carbon\Carbon;


class Rank
{

    const PREFIX = 'att:overtime:rank:';
    const LAST_WEEK = 'att:overtime:last_week';
    const LAST_MONTH = 'att:overtime:last_month';
    const THIS_YEAR = 'att:overtime:this_year';

    protected $redis = null;


    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }


    public function setScores($date, $member, $scores)
    {
        $key = self::PREFIX . $date;
        return $this->redis->zAdd($key, $scores, $member);
    }


    public function setMultiRankings($dates, $outKey)
    {
        $keys = array_map(function ($date) {
            return self::PREFIX . $date;
        }, $dates);

        $weights = array_fill(0, count($keys), 1);
        return $this->redis->zUnion($outKey, $keys, $weights);
    }


    /**
     * 获取昨日top10
     */
    public function getYesterdayTop10()
    {
        $date = Carbon::yesterday()->format('Ymd');
        return $this->redis->zRevRange(self::PREFIX . $date, 0, 9, true);
    }


    /**
     * 统计上周排行
     */
    public function setLastWeekRankings()
    {
        $days = last_week_days();

        $dates = array();
        foreach ($days as $day) {
            $dates[] = $day->format('Ymd');
        }
        $this->setMultiRankings($dates, self::LAST_WEEK);
    }


    /**
     * 获取上周top10
     */
    public function getLastWeekTop10()
    {
        return $this->redis->zRevRange(self::LAST_WEEK, 0, 9, true);
    }


    /**
     * 统计上月排行
     */
    public function setLastMonthRankings()
    {
        $days = last_month_days();

        $dates = array();
        foreach ($days as $day) {
            $dates[] = $day->format('Ymd');
        }
        $this->setMultiRankings($dates, self::LAST_MONTH);
    }


    /**
     * 获取上月top10
     */
    public function getLastMonthTop10()
    {
        return $this->redis->zRevRange(self::LAST_MONTH, 0, 9, true);
    }


    /**
     * 统计今年截至到昨天的排行
     */
    public function setThisYearRankings()
    {
        $date = Carbon::today();

        // 今天是今年中的第几天
        $daysTotal = $date->dayOfYear;

        // 重置到今年的第一天
        $date->startOfYear();

        $result = array();
        $result[] = $date->copy()->format('Ymd');
        for ($i = 2; $i <= $daysTotal; $i++) {
            $result[] = $date->addDay()->copy()->format('Ymd');
        }

        $this->setMultiRankings($result, self::THIS_YEAR);
    }


    /**
     * 获取今年截至到昨天的top10
     */
    public function getThisYearTop10()
    {
        return $this->redis->zRevRange(self::THIS_YEAR, 0, 9, true);
    }


}
