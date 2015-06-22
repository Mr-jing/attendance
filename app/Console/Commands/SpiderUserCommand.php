<?php namespace App\Console\Commands;

use App\Models\Spider;
use App\Models\UserCreator;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class SpiderUserCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'spider:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Spider the users.';

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

        // 获取参数并验证
        $from = $this->option('from');
        $to = $this->option('to');

        if (!is_numeric($from)) {
            throw new \Exception('form 必填，并且为数字');
        }
        if (!is_numeric($to)) {
            throw new \Exception('to 必填，并且为数字');
        }

        $from = intval($from);
        $to = intval($to);

        if ($from <= 0) {
            throw new \Exception('from 必须大于 0');
        }
        if ($from >= $to) {
            throw new \Exception('to 必须大于 from');
        }

        $start_time = microtime(true);

        for ($uid = $from; $uid <= $to; $uid++) {
            // 爬取名字
            $spider = new Spider($uid, date('Y'), date('m'));
            $name = $spider->getName();

            if (empty($name)) {
                continue;
            }

            // 创建用户，保存到数据库中
            $status = UserCreator::create(array(
                'job_num' => $uid,
                'name' => $name,
            ));
            $status = $status ? 'true' : 'false';
            $this->info("uid: {$uid}, status: {$status}");
        }

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
            ['from', null, InputOption::VALUE_OPTIONAL, '', 1],
            ['to', null, InputOption::VALUE_REQUIRED, '', null],
        ];
    }

}
