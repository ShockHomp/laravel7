<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestMake extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:make {num : 模拟次数}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '模拟抽奖';

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
    public function handle()
    {
        $forCounts = $this->argument('num');
        $this->info('模拟抽奖开始');
        $bar = $this->output->createProgressBar($forCounts);
        $bar->start();
        for ($i=1; $i<=$forCounts; $i++)
        {
            $sql = <<<EOF
SELECT *
FROM raffle_jackpots AS t1 
JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM raffle_jackpots)-(SELECT MIN(id) FROM raffle_jackpots))+(SELECT MIN(id) FROM raffle_jackpots)) AS id) AS t2
WHERE t1.id >= t2.id
ORDER BY t1.id LIMIT 1;
EOF;
            $item = DB::select($sql);
            $this->comment('第'.$i.'次模拟抽奖');
            $itemId = reset($item)->id;
            $delSql = <<<EOF
delete from raffle_jackpots where raffle_jackpots.id = {$itemId}
EOF;
            DB::select($delSql);
            $bar->advance();
        }
        $bar->finish();

    }
}
