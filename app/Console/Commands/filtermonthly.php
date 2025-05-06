<?php

namespace App\Console\Commands;

use App\Models\event;
use Carbon\Carbon;
use Illuminate\Console\Command;

class filtermonthly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:filterMonthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
            $date = Carbon::now()->subDays(30);
        event::where("date", "<",$date)->delete();
        $this->comment("filtered");
    }
}
