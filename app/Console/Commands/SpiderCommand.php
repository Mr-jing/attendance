<?php namespace App\Console\Commands;

use App\Models\RecordCreator;
use App\Models\Spider;
use App\Models\User;
use App\Models\UserCreator;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class SpiderCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'spider:record';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Spider the records.';

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

        $uid = $this->option('uid');
        $year = $this->option('year');
        $month = $this->option('month');

        $start_time = microtime(true);

        if (is_numeric($uid)) {
            $uids = array($uid);
        } else {
            $uids = User::lists('job_num');
        }

        foreach ($uids as $uid) {
            $spider = new Spider($uid, $year, $month);
            $data = $spider->getData();
            $this->insertData($data);
            unset($spider);
            $this->info("{$uid} {$year} {$month}");
        }

        $end_time = microtime(true);
        $exec_time = $end_time - $start_time;
        $this->info("OK.\nexec_time: {$exec_time}\nend_at:" . date('Y-m-d H:i:s'));
    }

    protected function insertData($data)
    {
        if (!isset($data['job_num']) ||
            !isset($data['name']) ||
            !isset($data['year']) ||
            !isset($data['month']) ||
            !isset($data['records'])
        ) {
            return;
        }

        $records = $data['records'];
        foreach ($records as $record) {
            $record['job_num'] = $data['job_num'];
            $record['year'] = $data['year'];
            $record['month'] = $data['month'];
            RecordCreator::create($record);
        }
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
            ['uid', null, InputOption::VALUE_OPTIONAL, '', null],
            ['year', null, InputOption::VALUE_OPTIONAL, '', intval(date('Y'))],
            ['month', null, InputOption::VALUE_OPTIONAL, '', intval(date('m'))],
        ];
    }

}
