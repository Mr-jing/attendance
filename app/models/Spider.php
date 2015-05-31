<?php

namespace App\Models;

use Carbon\Carbon;

class Spider
{

    protected $jobNum;
    protected $year;
    protected $month;
    protected $url;
    protected $content;


    public function __construct($jobNum, $year, $month)
    {
        $this->jobNum = $jobNum;

        // 当前时间
        $currentCarbon = Carbon::now();

        // 查询日期的当月最大天数
        $days = Carbon::create($year, $month, 1)->daysInMonth;

        // 查询的截止日期
        $endCarbon = Carbon::create($year, $month, $days);

        // 查询未来？臣妾做不到啊
        if ($endCarbon->gt($currentCarbon)) {
            // 最多就只能查到到前一天的内容
            $endCarbon = $currentCarbon->subDay();
        }
        // 查询的起始日期
        $startCarbon = Carbon::create($endCarbon->year, $endCarbon->month, 1);

        $this->year = $endCarbon->year;
        $this->month = $endCarbon->month;
        $startDate = $startCarbon->toDateString();
        $endDate = $endCarbon->toDateString();
        $this->url = "http://outtoa.transn.net/2014/htdocs/i.php?do=kq_viewPerson_weixin&u={$this->jobNum}&sDate={$startDate}&eDate={$endDate}";
    }


    public function getContent()
    {
        if (empty($this->content)) {
            $this->content = file_get_contents($this->url);
        }
        return $this->content;
    }


    public function getName()
    {
        $content = $this->getContent();
        $pattern = '#<div class="username">(.*?)</div><div class="datearea">#i';
        $result = preg_match($pattern, $content, $matches);

        if (1 === $result && '' !== $matches[1]) {
            return $matches[1];
        }
        return null;
    }


    public function getRecords()
    {
        $content = $this->getContent();
        $pattern = '#">(\d{2})</div><div class=\'item\'><div class="dakatime">(.*?)</div><div class="dakatime">(.*?)</div>#i';
        $result = preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

        return array_map(function ($value) {
            return array(
                'day' => intval($value[1]),
                'start' => $value[2],
                'end' => $value[3]
            );
        }, $matches);
    }


    public function getYear()
    {
        return $this->year;
    }


    public function getMonth()
    {
        return $this->month;
    }

    public function  getData()
    {
        $name = $this->getName();
        if (empty($name)) {
            return array();
        }

        return array(
            'job_num' => $this->jobNum,
            'name' => $name,
            'year' => $this->getYear(),
            'month' => $this->getMonth(),
            'records' => $this->getRecords(),
        );

    }

}

