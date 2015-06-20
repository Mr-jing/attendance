<?php namespace App\Console\Commands;

use App\Models\Rank;
use App\Models\Record;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class RankCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'rank';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh the rankings';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        date_default_timezone_set('Asia/Shanghai');

        $year = $this->option('year');
        $month = $this->option('month');
        $isAll = $this->option('all');

        if (empty($year) || empty($month)) {
            $date = Carbon::yesterday();
            $year = $date->year;
            $month = $date->month;
        }
        $this->info("year: {$year} month: {$month} isAll: {$isAll}");

        $start_time = microtime(true);

        // 查询加班的记录
        if ($isAll) {
            $records = Record::where('overtime', '>', 0)
                ->get()->toArray();
        } else {
            $records = Record::where('year', $year)
                ->where('month', $month)
                ->where('overtime', '>', 0)
                ->get()->toArray();
        }

        $redis = \App::make('RedisSingleton');
        $rank = new Rank($redis);

        // 生成单日榜单
        foreach ($records as $record) {
            $key = sprintf('%d%02d%02d', $record['year'], $record['month'], $record['day']);
            $rank->setScores($key, $record['job_num'], $record['overtime']);
        }

        // 生成周榜
        $rank->setLastWeekRankings();

        // 生成月榜
        $rank->setLastMonthRankings();

        // 生成年榜
        $rank->setThisYearRankings();

        $end_time = microtime(true);
        $exec_time = $end_time - $start_time;
        $this->info("OK.\nexec_time: {$exec_time}\nend_at:" . date('Y-m-d H:i:s'));
    }


    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
//            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['year', 'y', InputOption::VALUE_OPTIONAL, '', null],
            ['month', 'm', InputOption::VALUE_OPTIONAL, '', null],
            ['all', null, InputOption::VALUE_NONE, '', null],
        ];
    }

}
